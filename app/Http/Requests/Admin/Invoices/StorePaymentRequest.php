<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Invoices;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'amount' => ['required', 'decimal:2'],
            'paid_on' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
