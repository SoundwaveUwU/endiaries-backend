<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class Button extends Component
{
    /**
     * @var bool|string
     */
    public $icon;

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
     * @var string
     */
    public $classes;

    /**
     * @var string
     */
    public $tag;

    /**
     * @var string
     */
    public $href;

    /**
     * @var string
     */
    public $type;

    /**
     * Create a new component instance.
     *
     * @param $label
     * @param string|null $href
     * @param string $type
     * @param false|string $icon
     * @param string $text
     * @param string $background
     * @param string $border
     * @param string $size
     */
    public function __construct($href = null, $type = "submit", $icon = false, $text = 'text-gray-900', $background = 'bg-gray-100', $border = 'border-transparent', $size = 'normal')
    {
        $this->icon = $icon;
        $this->text = $text;
        $this->background = $background;
        $this->border = $border;

        if (!is_null($href) && $href) {
            $this->tag = 'a';
            $this->href = $href;
        } else {
            $this->tag = 'button';
            $this->type = $type;
        }

        $classes = collect([
            'rounded',
            'shadow-sm',
            'text-center',
            'border',
            'm-1',
            'focus:outline-none',
            'active:shadow-none',
            'hover:shadow',
            'flex',
            'justify-between',
            'items-center',
            $this->background,
            $this->text,
            $this->border,
        ]);

        switch($size)
        {
            case 'normal':
                $classes = $classes->merge([
                    'px-2 md:px-3',
                    'py-1 md:py-2',
                    'font-bold',
                    'text-sm md:text-regular'
                ]);
                break;
            case 'large':
                $classes = $classes->merge([
                    'px-4',
                    'py-3',
                    'text-xl',
                ]);
                break;
            case 'small':
                $classes = $classes->merge([
                    'px-2',
                    'py-1',
                    'text-sm'
                ]);
                break;
        }

        $this->classes = $classes->join(' ');
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|string
     */
    public function render()
    {
        return view('components.button');
    }
}
