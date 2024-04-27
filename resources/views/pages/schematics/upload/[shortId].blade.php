<?php
use function Livewire\Volt\{mount, state, computed};
use App\Models\Schematic;
use function Laravel\Folio\name;
use Illuminate\Support\Facades\Cache;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Contracts\View\View;
use App\Models\Player;
use Illuminate\Support\Str;

use App\Forms\Components\SchematicPreviewRenderer;
use Livewire\Volt\Component;
name('schematics.upload');

new class extends Component implements HasForms {
    use InteractsWithForms;

    public $shortId;
    public $schematicBase64;
    public $author;
    public ?array $data = [];

    public function mount($shortId)
    {
        $cacheKey = Cache::get("schematic-temporary-short-links:{$shortId}");
        if (!$cacheKey || strlen($cacheKey) === 0) {
            $this->redirect('/schematics');
        }
        $author = explode(':', $cacheKey)[1];
        if ($author) {
            $this->author = $author;
            info('Author is set to: ' . $author);
        } else {
            //redirect to schematics since the author is not set
            $this->redirect('/schematics');
        }
        $schematicFile = Cache::get($cacheKey);
        $this->schematicBase64 = base64_encode($schematicFile);
        $this->form->fill();
    }

    protected function getRedirectUrl(): ?string
    {
        return static::getResource()::getUrl('index') . '/schematics/';
    }

    public function messages(): array
    {
        return [
            'data.schematicPreview.webm.required' => 'The schematic preview must include a webm file.',
            'data.schematicPreview.png.required' => 'The schematic preview must include a png file.',
        ];
    }

    public function rules(): array
    {
        return [
            'data.title' => ['required'],
            'data.description' => ['required'],
            'data.schematicPreview' => ['required', 'array', 'size:2'],
            'data.schematicPreview.webm' => ['required', 'string'],
            'data.schematicPreview.png' => ['required', 'string'],
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')->required(),
                SchematicPreviewRenderer::make('schematicPreview')
                    ->viewData([
                        'schematicBase64' => $this->schematicBase64,
                        'schematicId' => $this->shortId,
                    ])
                    ->required()
                    ->rules(['required', 'array', 'size:2']),
                RichEditor::make('description')->required(),
            ])
            ->statePath('data');
    }
    public function create()
    {
        $this->validate();
        $schematicUUID = Str::uuid();
        $authors = explode(',', $this->author);
        info($authors);
        info('Creating schematic...');

        try {
            $schematic = new Schematic([
                'id' => $schematicUUID,
                'name' => $this->data['title'],
                'description' => $this->data['description'],
            ]);
            info('Created schematic with id: ' . $schematicUUID);
            $schematic->save();

            foreach ($authors as $author) {
                $player = Player::firstOrCreate(['id' => $author]);
                if ($player) {
                    $schematic->authors()->attach($player);
                }
            }

            $schematic = Schematic::find($schematicUUID);
            $schematic
                ->addMediaFromBase64($this->schematicBase64)
                ->usingFileName($schematicUUID . '.schem')
                ->toMediaCollection('schematic');
            $schematic
                ->addMediaFromBase64($this->data['schematicPreview']['webm'])
                ->usingFileName($schematicUUID . '.webm')
                ->toMediaCollection('preview_video');
            $schematic
                ->addMediaFromBase64($this->data['schematicPreview']['png'])
                ->usingFileName($schematicUUID . '.png')
                ->toMediaCollection('preview_image');
        } catch (\Exception $e) {
            $schematic = Schematic::find($schematicUUID);
            if ($schematic) {
                $schematic->authors()->detach();
                $schematic->delete();
            }

            $schematic->clearMediaCollection('schematic');
            $schematic->clearMediaCollection('preview_video');
            $schematic->clearMediaCollection('preview_image');
            throw $e;
        }
    }
};
?>


<x-app-layout>
    @volt
        <div class="max-w-7xl mx-auto pt-4">
            <form wire:submit="create">

                <div class="bg-base-300 overflow-hidden shadow-xl sm:rounded-lg p-4">
                    <div class="flex items-center justify-between">

                        <div class="p-4">
                            <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight p-0 m-0">
                                Upload Schematic
                            </h1>
                            <span class="text-gray-500 text-sm">
                                {{ $shortId }}
                            </span>
                        </div>
                        <div>
                            <button type="submit" wire:loading.remove
                                class="bg-primary rounded-lg px-4 py-2 text-sm font-semibold hover:bg-secondary active:bg-secondary tranform hover:scale-105 transition duration-300 ease-in-out active:scale-95">
                                Submit
                            </button>
                            <button wire:loading
                                class="bg-primary rounded-lg px-4 py-2 text-sm font-semibold hover:bg-secondary active:bg-secondary tranform hover:scale-105 transition duration-300 ease-in-out active:scale-95">
                                Submitting... <i class="fas fa-spinner animate-spin"></i>
                            </button>
                        </div>
                    </div>
                    {{ $this->form }}



                </div>
            </form>

        </div>
    @endvolt
</x-app-layout>
