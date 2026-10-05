<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecruiterResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'role' => $this->role ? [
                'id' => $this->role->id,
                'name' => $this->role->name,
                'label' => $this->role->label,
            ] : null,
            'posted_jobs_count' => $this->posted_jobs_count ?? $this->postedJobs()->count(),
            'conducted_interviews_count' => $this->conducted_interviews_count ?? $this->conductedInterviews()->count(),
            'assigned_tasks_count' => $this->assigned_tasks_count ?? $this->assignedTasks()->count(),
            'posted_jobs' => JobResource::collection($this->whenLoaded('postedJobs')),
            'conducted_interviews' => InterviewResource::collection($this->whenLoaded('conductedInterviews')),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
