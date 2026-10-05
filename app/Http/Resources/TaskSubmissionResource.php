<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskSubmissionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'technical_task_id' => $this->technical_task_id,
            'repository_url' => $this->repository_url,
            'notes' => $this->notes,
            'file_path' => $this->file_path,
            'submitted_at' => $this->submitted_at?->toISOString(),
            'score' => $this->score,
            'feedback' => $this->feedback,
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
