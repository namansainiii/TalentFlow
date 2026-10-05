<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'department' => $this->department,
            'description' => $this->description,
            'experience' => $this->experience,
            'salary_range' => $this->salary_range,
            'application_deadline' => $this->application_deadline?->toDateString(),
            'status' => $this->status,
            'recruiter' => new UserResource($this->whenLoaded('recruiter')),
            'skills' => SkillResource::collection($this->whenLoaded('skills')),
            'applications_count' => $this->whenCounted('applications'),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
