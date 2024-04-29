<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Schematic;

use WireElements\WireExtender\Attributes\Embeddable;

#[Embeddable]
class SchematicView extends Component
{
    public Schematic $schematic;
    public string $schematicId;
    public string $schematicBase64;
    public string $schematicName = 'untitled';

    public function mount($schematicUUID = 'c592c9ed-18ee-4447-8d56-721dcb663b48')
    {
        $this->schematic = Schematic::where('id', $schematicUUID)->firstOrFail();
        $this->schematicId = $this->schematic->string_id;
        $this->schematicBase64 = $this->schematic->base64;
        $this->schematicName = $this->schematic->name;
    }

    public function render()
    {
        return view('livewire.schematic-view');
    }
}
