<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TechnicalTaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'application_id' => $this->application_id,
            'title' => $this->title,
            'description' => $this->description,
            'attachments' => $this->attachments ?? [],
            'deadline' => $this->deadline?->toISOString(),
            'status' => $this->status,
            'assigned_by' => new UserResource($this->whenLoaded('assignedByUser')),
            'submissions' => TaskSubmissionResource::collection($this->whenLoaded('submissions')),
            'latest_submission' => new TaskSubmissionResource($this->whenLoaded('latestSubmission')),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
