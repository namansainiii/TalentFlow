<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplyJobRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'experience_years' => 'nullable|numeric|min:0|max:50',
            'education' => 'nullable|string|max:255',
            'skills_summary' => 'nullable|string|max:1000',
            'resume_id' => 'nullable|exists:resumes,id',
            'resume' => 'nullable|file|mimes:pdf|max:10240',
            'notes' => 'nullable|string|max:1000',
        ];
    }
}
