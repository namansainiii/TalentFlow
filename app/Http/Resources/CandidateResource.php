<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CandidateResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'experience_years' => (float) $this->experience_years,
            'education' => $this->education,
            'skills_summary' => $this->skills_summary,
            'user_id' => $this->user_id,
            'latest_resume' => new ResumeResource($this->whenLoaded('latestResume')),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
