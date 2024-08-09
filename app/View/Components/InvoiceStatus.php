<?php

declare(strict_types=1);

namespace App\View\Components;

use App\Enums\InvoiceStatus as InvoiceStatusEnum;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class InvoiceStatus extends Component
{
    public function __construct(public string $colour, public InvoiceStatusEnum $status) {}

    public function render(): View
    {
        return view('components.invoice-status');
    }
}
