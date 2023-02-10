<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Certificates;

use Illuminate\Foundation\Http\FormRequest;

class StoreCertificateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'issued_by' => ['required', 'string', 'max:255'],
            'issued_on' => ['required', 'date'],
            'expires_on' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
            'image' => ['file', 'mimes:jpeg,jpg,png,pdf', 'max:20480'],
        ];
    }
}
