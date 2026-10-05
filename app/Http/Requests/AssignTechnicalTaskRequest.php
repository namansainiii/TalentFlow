<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignTechnicalTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'deadline' => 'required|date|after:now',
            'files' => 'nullable|array|max:5',
            'files.*' => 'file|mimes:jpg,jpeg,png,webp,svg,pdf,doc,docx,txt,rtf,odt|max:10240',
        ];
    }

    public function messages(): array
    {
        return [
            'files.max' => 'You can upload up to 5 files only.',
            'files.*.mimes' => 'Only image (JPG, PNG, WEBP, SVG), PDF, and document (DOC, DOCX, TXT, RTF, ODT) files are allowed.',
            'files.*.max' => 'Each file cannot exceed 10MB in size.',
        ];
    }
}
