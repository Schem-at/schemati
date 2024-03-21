<a href="/schematics/{{ $schematic->id }}" wire:navigate id="schematic-card_{{ $schematic->id }}"
    class="transition-transform duration-300 transform hover:scale-[1.02] active:scale-[0.98]">
    <div class="w-full max-w-sm rounded-lg bg-base-100 shadow-lg">
        <div class="relative p-2">
            {{--  @if ($schematic->preview_video)
                <video class="w-full h-48 object-cover rounded-lg bg-base-200 shadow-[inset_0_4px_4px_rgba(1,0,0,0.6)]"
                    src="{{ $schematic->preview_video }}" loop id="preview-video_{{ $schematic->id }}" muted></video>
            @elseif ($schematic->preview_image)
                <img class="w-full h-48 object-cover rounded-lg shadow-[inset_0_4px_4px_rgba(1,0,0,0.6)] bg-base-200"
                    src="{{ $schematic->preview_image }}" alt="{{ $schematic->name }}">
            @else
                <div class="w-full h-48 bg-gray-200 rounded-lg shadow-[inset_0_4px_4px_rgba(1,0,0,0.6)] bg-base-200">
                </div>
            @endif  --}}
        </div>

        <div class="px-5 pb-5">

            <h5 class="text-xl font-semibold tracking-tight text-gray-900 dark:text-white">
                {{ $schematic->name }}
            </h5>
            <div class="flex items-center mt-2.5 mb-5">
                <div class="flex items-center space-x-1 rtl:space-x-reverse">
                    @foreach ($schematic->authors as $player)
                        <img src="{{ $player->headUrl }}" alt="{{ $player->lastSeenName }}"
                            class="w-8 h-8 rounded-full m-1">
                    @endforeach
                </div>
                <span
                    class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800 ms-3">
                    tags-tbd
                </span>
            </div>

        </div>
    </div>
</a>

@push('scripts')
    <script>
        const video_{{ $schematic->string_id }} = document.getElementById('preview-video_{{ $schematic->id }}')
        const card_{{ $schematic->string_id }} = document.getElementById('schematic-card_{{ $schematic->id }}')
        //only play when the mouse is over the video
        card_{{ $schematic->string_id }}.addEventListener('mouseover', () => {
            video_{{ $schematic->string_id }}.play();
        });
        card_{{ $schematic->string_id }}.addEventListener('mouseout', () => {
            video_{{ $schematic->string_id }}.pause();
        });
    </script>
@endpush
