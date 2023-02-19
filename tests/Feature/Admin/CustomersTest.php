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

test('a user can view a list of customers', function () {
    $customers = Customer::factory()->count(3)->create();

    authenticatedUser()->get(route('admin.customers.list'))
        ->assertSee($customers[0]->name)
        ->assertSee('€' . number_format($customers[1]->balance / 100, 2))
        ->assertSee('€' . number_format($customers[2]->paid_to_date / 100, 2));
});

test('a user can view a customer', function () {
    $customer = Customer::factory()->create();

    authenticatedUser()->get(route('admin.customers.show', ['customer' => $customer]))
        ->assertSee($customer->name)
        ->assertSee('€' . number_format($customer->balance / 100, 2))
        ->assertSee('Update Customer')
        ->assertSee('Delete Customer');
});

test('a user can update a customer', function() {
    $customer = Customer::factory()->create();

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

test('a user can delete a customer', function () {
    $customer = Customer::factory()->create();

    authenticatedUser()->delete(route('admin.customers.delete', ['customer' => $customer]))
        ->assertSessionDoesntHaveErrors()
        ->assertRedirectToRoute('admin.customers.list');

    authenticatedUser()->get(route('admin.customers.list'))
        ->assertDontSee($customer->name);
});
