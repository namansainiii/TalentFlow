<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InterviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'application_id' => $this->application_id,
            'interviewer' => new UserResource($this->whenLoaded('interviewer')),
            'candidate' => $this->relationLoaded('application') && $this->application->relationLoaded('candidate')
                ? new CandidateResource($this->application->candidate)
                : null,
            'job' => $this->relationLoaded('application') && $this->application->relationLoaded('job')
                ? new JobResource($this->application->job)
                : null,
            'scheduled_at' => $this->scheduled_at?->toISOString(),
            'meeting_link' => $this->meeting_link,
            'status' => $this->status,
            'feedback' => $this->feedback,
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
