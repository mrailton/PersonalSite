<?php

declare(strict_types=1);

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AdminLayout extends Component
{
    public function __construct(public string $title)
    {
    }

    public function render(): View
    {
        return view('components.admin-layout');
    }
}
