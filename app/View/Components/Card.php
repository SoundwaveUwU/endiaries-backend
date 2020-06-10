<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class Card extends Component
{
    /**
     * @var string
     */
    public $text;

    /**
     * @var string
     */
    public $background;

    /**
     * @var string
     */
    public $border;

    /**
     * Create a new component instance.
     *
     * @param string $text
     * @param string $background
     * @param string $border
     */
    public function __construct($text = 'text-gray-900', $background = 'bg-gray-100', $border = 'border-gray-300')
    {
        $this->text = $text;
        $this->background = $background;
        $this->border = $border;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|string
     */
    public function render()
    {
        return view('components.card');
    }
}
