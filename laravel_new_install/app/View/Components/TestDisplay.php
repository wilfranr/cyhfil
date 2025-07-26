<?php

namespace App\View\Components;

use Illuminate\View\Component;

class TestDisplay extends Component
{
    public $message;

    public function __construct()
    {
        $this->message = 'Hola desde el componente!';
    }

    public function render()
    {
        return view('components.test-display');
    }
}
