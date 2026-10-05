<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateJobRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'sometimes|required|string|max:255',
            'department' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'experience' => 'sometimes|required|string|max:100',
            'salary_range' => 'nullable|string|max:100',
            'application_deadline' => 'sometimes|required|date',
            'status' => 'nullable|string|in:open,closed,draft',
            'mandatory_skills' => 'nullable|array',
            'mandatory_skills.*' => 'string|max:100',
            'bonus_skills' => 'nullable|array',
            'bonus_skills.*' => 'string|max:100',
        ];
    }
}
