<?php

use App\Models\Customer;

test('a user can update a customer', function() {
    $customer = Customer::factory()->create();

    authenticatedUser()->get(route('admin.customers.show', ['customer' => $customer]))
        ->assertSee($customer->name)
        ->assertSee('€' . number_format($customer->balance / 100, 2))
        ->assertSee('Update Customer');

    authenticatedUser()->get(route('admin.customers.edit', ['customer' => $customer]))
        ->assertSee($customer->name)
        ->assertDontSee('€' . number_format($customer->balance / 100, 2))
        ->assertSee('Submit');

    authenticatedUser()->put(route('admin.customers.update', ['customer' => $customer]), ['name' => 'Updated Customer'])
        ->assertSessionDoesntHaveErrors()
        ->assertRedirectToRoute('admin.customers.list');

    authenticatedUser()->get(route('admin.customers.list'))
        ->assertSee('Updated Customer')
        ->assertDontSee($customer->name);
});
