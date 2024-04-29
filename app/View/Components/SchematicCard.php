<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Schematic;

class SchematicCard extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public Schematic $schematic)
    {
        //
        info($this->schematic);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.schematic-card');
    }
}
