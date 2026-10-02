<?php

namespace App\Jobs;

use App\Models\Resume;
use App\Services\CandidateScoringService;
use App\Services\ResumeParserService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessResumeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Resume $resume;

    /**
     * Create a new job instance.
     */
    public function __construct(Resume $resume)
    {
        $this->resume = $resume;
    }

    /**
     * Execute the job.
     */
    public function handle(ResumeParserService $parserService, CandidateScoringService $scoringService): void
    {
        try {
            $this->resume->update(['status' => 'processing']);

            // 1. Extract candidate information & skills from PDF
            $parserService->parseResume($this->resume);

            // 2. Automatically recalculate score for any existing applications
            $candidate = $this->resume->candidate;
            if ($candidate) {
                $applications = $candidate->applications()->get();
                foreach ($applications as $application) {
                    $scoringService->calculateScore($application);
                }
            }
        } catch (\Throwable $e) {
            Log::error('Resume parsing failed: '.$e->getMessage(), ['resume_id' => $this->resume->id]);
            $this->resume->update(['status' => 'failed']);
        }
    }
}
