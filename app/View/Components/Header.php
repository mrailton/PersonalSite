<?php

declare(strict_types=1);

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Header extends Component
{
    public function __construct()
    {
        //
    }

    public function render(): View
    {
        return view('components.header');
    }
}
