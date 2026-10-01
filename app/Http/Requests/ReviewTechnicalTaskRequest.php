<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewTechnicalTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'score' => 'required|integer|min:0|max:100',
            'feedback' => 'required|string',
            'status' => 'nullable|string|in:Reviewed,In Progress',
        ];
    }
}
