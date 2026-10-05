<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInterviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'interviewer_id' => 'sometimes|required|exists:users,id',
            'scheduled_at' => 'sometimes|required|date',
            'meeting_link' => 'sometimes|required|string|url|max:255',
            'status' => 'nullable|string|in:scheduled,rescheduled,completed,cancelled',
            'feedback' => 'nullable|string',
        ];
    }
}
