<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Navigation extends Component
{
    public $currentRoute;

    public function __construct()
    {
        $this->currentRoute = request()->route()->getName();
    }

    public function render()
    {
        return view('components.navigation');
    }
}