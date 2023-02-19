<?php

use App\Models\Invoice;

test('a user can view a list of invoices', function () {
    $invoices = Invoice::factory()->count(3)->create();

    authenticatedUser()->get(route('admin.invoices.list'))
        ->assertSee($invoices[0]->customer->name)
        ->assertSee('€' . number_format($invoices[1]->amount / 100, 2))
        ->assertSee($invoices[2]->due_on->format('jS M Y'));
});
