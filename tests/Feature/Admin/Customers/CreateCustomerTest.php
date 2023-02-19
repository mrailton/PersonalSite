<?php

use App\Models\Customer;

test('a user can create a new customer', function () {
    expect(Customer::count())->toBe(0);

    authenticatedUser()->get(route('admin.customers.create'))
        ->assertSee('Add Customer')
        ->assertSee('Name:');

    authenticatedUser()->post(route('admin.customers.store'), ['name' => 'Test Customer'])
        ->assertSessionDoesntHaveErrors()
        ->assertRedirectToRoute('admin.customers.list');

    expect(Customer::count())->toBe(1);

    authenticatedUser()->get(route('admin.customers.list'))
        ->assertSee('Test Customer');
});
