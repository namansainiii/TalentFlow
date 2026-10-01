<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScheduleInterviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'interviewer_id' => 'required|exists:users,id',
            'scheduled_at' => 'required|date|after:now',
            'meeting_link' => 'required|string|url|max:255',
            'status' => 'nullable|string|in:scheduled,rescheduled,completed,cancelled',
            'feedback' => 'nullable|string',
        ];
    }
}
