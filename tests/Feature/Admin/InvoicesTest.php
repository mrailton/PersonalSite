<?php

use App\Enums\InvoiceStatus;
use App\Models\Customer;
use App\Models\Invoice;

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

    authenticatedUser()->post(route('admin.invoices.store'), $data)
        ->assertSessionDoesntHaveErrors()
        ->assertRedirectToRoute('admin.invoices.list');

    authenticatedUser()->get(route('admin.invoices.list'))
        ->assertSee($customer->name)
        ->assertSee('€' . number_format(550, 2));

    expect(Invoice::first()->amount)->toBe(number_format(550.00, 2))
        ->and(Invoice::first()->status)->toBe(InvoiceStatus::Draft);
});
