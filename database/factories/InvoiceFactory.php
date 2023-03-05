<?php

namespace Database\Factories;

use App\Enums\InvoiceStatus;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    public function definition(): array
    {
        $balance = random_int(160, 500);

        return [
            'customer_id' => Customer::first()?->id ?: Customer::factory()->create()->id,
            'issued_on' => now(),
            'due_on' => now()->addDays(7),
            'amount' => $balance,
            'balance' => $balance,
            'status' => InvoiceStatus::Sent,
        ];
    }
}
