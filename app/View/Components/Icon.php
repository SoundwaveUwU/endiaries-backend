<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class Icon extends Component
{
    public $prefix;

    public $icon;

    /**
     * Create a new component instance.
     *
     * @param $icon
     * @param string $prefix
     */
    public function __construct($icon, $prefix = 'fa')
    {
        $this->prefix = $prefix;
        $this->icon = $icon;

        if (is_array($icon))
        {
            $this->prefix = $icon[0];
            $this->icon = $icon[1];
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|string
     */
    public function render()
    {
        return view('components.icon');
    }
}
