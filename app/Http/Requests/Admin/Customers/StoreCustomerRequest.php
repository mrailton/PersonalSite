<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Customers;

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
            //
        ];
    }
}
