<?php

namespace App\Http\Requests\Camera;

use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'first' =>  'nullable|integer|min:1|max:100',
            'page' => 'nullable|integer|min:1',
            'sort' => 'nullable|string|in:id,name,url,created_at',
            'sort_order' => 'nullable|string|in:asc,desc',
            'search' => 'nullable|string|max:255',
        ];
    }
}
