<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    public function definition(): array
    {
        $balance = random_int(16000, 50000);

        return [
            'customer_id' => Customer::first()?->id ?: Customer::factory()->create()->id,
            'issued_on' => now(),
            'due_on' => now()->addDays(7),
            'amount' => $balance,
            'balance' => $balance,
            'status' => 'sent',
        ];
    }
}
