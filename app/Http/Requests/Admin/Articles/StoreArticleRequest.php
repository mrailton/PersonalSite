<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Articles;

use Illuminate\Foundation\Http\FormRequest;

class StoreArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'unique:articles'],
            'content' => ['required', 'string'],
            'published_at' => ['sometimes', 'date', 'nullable'],
        ];
    }
}
