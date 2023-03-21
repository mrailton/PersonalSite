<?php

use App\Enums\InvoiceStatus;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Payment;

test('a user can view a list of invoices', function () {
    $invoices = Invoice::factory()->count(3)->create();

    authenticatedUser()->get(route('admin.invoices.list'))
        ->assertSee('Create Invoice')
        ->assertSee($invoices[0]->customer->name)
        ->assertSee('€' . number_format($invoices[1]->amount, 2))
        ->assertSee($invoices[2]->due_on->format('jS M Y'));
});

test('a user can create a new draft invoice', function () {
    $customer = Customer::factory()->create();
    expect(Invoice::count())->toBe(0);

    $data = [
        'customer_id' => $customer->id,
        'issued_on' => now()->format('Y-m-d'),
        'due_on' => now()->addDays(7)->format('Y-m-d'),
        'items' => [
            ['description' => 'Test Item 1', 'quantity' => 1, 'amount' => 100],
            ['description' => 'Test Item 2', 'quantity' => 3, 'amount' => 150],
        ],
    ];

    authenticatedUser()->get(route('admin.invoices.create'))
        ->assertSee('Create Invoice')
        ->assertSee('Submit');

    $res = authenticatedUser()->post(route('admin.invoices.store'), $data)
        ->assertSessionDoesntHaveErrors();

    $res->assertRedirectToRoute('admin.invoices.show', ['invoice' => Invoice::first()]);

    authenticatedUser()->get(route('admin.invoices.list'))
        ->assertSee($customer->name)
        ->assertSee('€' . number_format(550, 2));

    expect(Invoice::first()->amount)->toBe(number_format(550.00, 2))
        ->and(Invoice::first()->status)->toBe(InvoiceStatus::Draft);
});

test('a user can mark a draft invoice as sent', function () {
    $invoice = Invoice::factory()->create(['status' => InvoiceStatus::Draft]);

    authenticatedUser()->get(route('admin.invoices.show', ['invoice' => $invoice]))
        ->assertSee(InvoiceStatus::Draft->value);

    authenticatedUser()->post(route('admin.invoices.mark-sent', ['invoice' => $invoice]))
        ->assertSessionDoesntHaveErrors()
        ->assertRedirectToRoute('admin.invoices.show', ['invoice' => $invoice]);

    expect(Invoice::first()->status)->toBe(InvoiceStatus::Sent);
});

test('a user can add a payment to an invoice', function () {
    $invoice = Invoice::factory()->create(['status' => InvoiceStatus::Sent, 'balance' => 186.00]);

    authenticatedUser()->post(route('admin.invoices.add-payment', ['invoice' => $invoice]), ['amount' => '186.00', 'paid_on' => now()->format('Y-m-d')])
        ->assertSessionDoesntHaveErrors()
        ->assertRedirectToRoute('admin.invoices.list');

    $invoice->refresh();

    expect($invoice->status)->toBe(InvoiceStatus::Paid)
        ->and($invoice->balance)->toBe('0.00')
        ->and(Payment::count())->toBe(1);
});
