<?php

declare(strict_types=1);

use App\Models\Customer;

test('a user can view a list of customers', function () {
    $customers = Customer::factory()->count(3)->create();

    authenticatedUser()->get(route('admin.customers.list'))
        ->assertSee($customers[0]->name)
        ->assertSee('€' . number_format($customers[1]->balance / 100, 2))
        ->assertSee('€' . number_format($customers[2]->paid_to_date / 100, 2));
});
