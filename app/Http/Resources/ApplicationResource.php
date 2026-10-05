<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'job_id' => $this->job_id,
            'candidate_id' => $this->candidate_id,
            'status' => $this->status,
            'skill_score' => (float) $this->skill_score,
            'notes' => $this->notes,
            'job' => new JobResource($this->whenLoaded('job')),
            'candidate' => new CandidateResource($this->whenLoaded('candidate')),
            'resume' => new ResumeResource($this->whenLoaded('resume')),
            'status_histories' => ApplicationStatusHistoryResource::collection($this->whenLoaded('statusHistories')),
            'interviews' => InterviewResource::collection($this->whenLoaded('interviews')),
            'technical_tasks' => TechnicalTaskResource::collection($this->whenLoaded('technicalTasks')),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
