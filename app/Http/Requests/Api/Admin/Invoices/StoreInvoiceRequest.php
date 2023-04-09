<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\Admin\Invoices;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
            'issued_on' => ['required', 'date'],
            'due_on' => ['required', 'date'],
            'items' => ['required', 'array'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
