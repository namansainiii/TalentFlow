<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJobRequest;
use App\Http\Requests\UpdateJobRequest;
use App\Http\Resources\JobResource;
use App\Models\Job;
use App\Models\Skill;
use App\Services\WorkspaceCacheService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class JobController extends Controller
{
    /**
     * List all job openings with filtering.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Job::with(['recruiter', 'skills'])->withCount('applications');

        // Filter by department
        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        // Filter by status (default to open if not specified, or allow filtering)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search in title or description
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('department', 'like', "%{$search}%");
            });
        }

        $jobs = $query->latest()->paginate($request->input('per_page', 15));

        return JobResource::collection($jobs);
    }

    /**
     * Create a new job opening (recruiter/admin).
     */
    public function store(StoreJobRequest $request): JsonResponse
    {
        $user = $request->user();

        $job = Job::create([
            'recruiter_id' => $user->id,
            'title' => $request->title,
            'department' => $request->department,
            'description' => $request->description,
            'experience' => $request->experience,
            'salary_range' => $request->salary_range,
            'application_deadline' => $request->application_deadline,
            'status' => $request->status ?? 'open',
        ]);

        if ($request->filled('mandatory_skills') || $request->filled('bonus_skills')) {
            $this->syncSkills(
                $job,
                $request->input('mandatory_skills', []),
                $request->input('bonus_skills', [])
            );
        }

        WorkspaceCacheService::invalidateAll();

        return response()->json([
            'message' => 'Job created successfully',
            'job' => new JobResource($job->load(['recruiter', 'skills'])),
        ], 201);
    }

    /**
     * View job details.
     */
    public function show(Job $job): JsonResponse
    {
        $job->load(['recruiter', 'skills'])->loadCount('applications');

        return response()->json([
            'job' => new JobResource($job),
        ]);
    }

    /**
     * Update an existing job.
     */
    public function update(UpdateJobRequest $request, Job $job): JsonResponse
    {
        $job->update($request->only([
            'title',
            'department',
            'description',
            'experience',
            'salary_range',
            'application_deadline',
            'status',
        ]));

        if ($request->has('mandatory_skills') || $request->has('bonus_skills')) {
            $this->syncSkills(
                $job,
                $request->input('mandatory_skills', []),
                $request->input('bonus_skills', [])
            );
        }

        WorkspaceCacheService::invalidateAll();

        return response()->json([
            'message' => 'Job updated successfully',
            'job' => new JobResource($job->load(['recruiter', 'skills'])),
        ]);
    }

    /**
     * Sync mandatory and bonus skills to a job.
     */
    private function syncSkills(Job $job, array $mandatorySkills = [], array $bonusSkills = []): void
    {
        $syncData = [];

        foreach ($mandatorySkills as $skillName) {
            $name = trim($skillName);
            if ($name !== '') {
                $skill = Skill::firstOrCreate(['name' => $name]);
                $syncData[$skill->id] = ['is_mandatory' => true];
            }
        }

        foreach ($bonusSkills as $skillName) {
            $name = trim($skillName);
            if ($name !== '') {
                $skill = Skill::firstOrCreate(['name' => $name]);
                if (! isset($syncData[$skill->id])) {
                    $syncData[$skill->id] = ['is_mandatory' => false];
                }
            }
        }

        $job->skills()->sync($syncData);
    }

    /**
     * Delete a job.
     */
    public function destroy(Job $job): JsonResponse
    {
        $job->delete();

        WorkspaceCacheService::invalidateAll();

        return response()->json([
            'message' => 'Job deleted successfully',
        ]);
    }
}
