<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\Admin\Customers;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'hourly_rate' => ['nullable', 'decimal:2'],
        ];
    }
}
