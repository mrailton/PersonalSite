<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\InvoiceStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Invoices\StoreInvoiceRequest;
use App\Http\Requests\Admin\Invoices\StorePaymentRequest;
use App\Models\Customer;
use App\Models\Invoice;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class InvoicesController extends Controller
{
    public function list(Request $request): View
    {
        $invoices = Invoice::with('customer')->orderByDesc('issued_on')->paginate(10);

        return view('admin.invoices.list', ['invoices' => $invoices]);
    }

    public function create(Request $request): View
    {
        $customers = Customer::query()->get();

        $hourlyRates = [];

        foreach ($customers as $customer) {
            $hourlyRates[$customer->id] = $customer->hourly_rate;
        }

        return view('admin.invoices.create', ['customers' => $customers, 'hourlyRates' => $hourlyRates]);
    }

    public function store(StoreInvoiceRequest $request): RedirectResponse
    {
        $invoiceAmount = 0;
        $customer = Customer::query()->with('invoices')->find($request->validated('customer_id'));

        $invoice = $customer->invoices()->create([
            'issued_on' => $request->validated('issued_on'),
            'due_on' => $request->validated('due_on'),
            'status' => InvoiceStatus::Draft,
            'notes' => $request->validated('notes'),
        ]);

        foreach ($request->validated('items') as $item) {
            $subtotal = $item['amount'] * $item['quantity'];

            $invoice->items()->create([
                'description' => $item['description'],
                'amount' => $item['amount'],
                'quantity' => $item['quantity'],
                'subtotal' => $subtotal,
            ]);

            $invoiceAmount += $subtotal;
        }

        $invoice->amount += $invoiceAmount;
        $invoice->balance += $invoiceAmount;
        $invoice->save();

        $customer->balance += $invoiceAmount;
        $customer->save();

        return redirect()->route('admin.invoices.show', ['invoice' => $invoice]);
    }

    public function show(Request $request, Invoice $invoice): View
    {
        return view('admin.invoices.show', ['invoice' => $invoice]);
    }

    public function markSent(Request $request, Invoice $invoice): RedirectResponse
    {
        $invoice->update(['status' => InvoiceStatus::Sent]);

        return redirect()->route('admin.invoices.show', ['invoice' => $invoice]);
    }

    public function addPayment(StorePaymentRequest $request, Invoice $invoice): RedirectResponse
    {
        $payment = $invoice->payment()->create([
            'amount' => $request->validated('amount'),
            'paid_on' => $request->validated('paid_on'),
            'notes' => $request->validated('notes'),
        ]);

        $invoice->update([
            'status' => InvoiceStatus::Paid,
            'balance' => $invoice->balance - $payment->amount,
        ]);

        $invoice->customer->update([
            'balance' => $invoice->customer->balance - $payment->amount,
            'paid_to_date' => $invoice->customer->paid_to_date + $payment->amount,
        ]);

        return redirect()->route('admin.invoices.list');
    }
}
