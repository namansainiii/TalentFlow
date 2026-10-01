<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreJobRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'description' => 'required|string',
            'experience' => 'required|string|max:100',
            'salary_range' => 'nullable|string|max:100',
            'application_deadline' => 'required|date|after_or_equal:today',
            'status' => 'nullable|string|in:open,closed,draft',
            'mandatory_skills' => 'nullable|array',
            'mandatory_skills.*' => 'string|max:100',
            'bonus_skills' => 'nullable|array',
            'bonus_skills.*' => 'string|max:100',
        ];
    }
}
