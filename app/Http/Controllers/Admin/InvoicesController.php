<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class InvoicesController extends Controller
{
    public function list(Request $request): View
    {
        $invoices = Invoice::with('customer')->get();

        return view('admin.invoices.list', ['invoices' => $invoices]);
    }
}
