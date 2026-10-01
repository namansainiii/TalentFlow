<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmitTechnicalTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'repository_url' => 'nullable|url|max:255',
            'notes' => 'nullable|string|max:2000',
            'file' => 'nullable|file|max:20480',
        ];
    }
}
