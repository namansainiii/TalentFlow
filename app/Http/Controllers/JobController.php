<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJobRequest;
use App\Http\Requests\UpdateJobRequest;
use App\Http\Resources\JobResource;
use App\Models\Job;
use App\Models\Skill;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class JobController extends Controller
{
    /**
     * List all job openings with optional search and filters.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        // 1. Start querying jobs with recruiter and skill tags
        $query = Job::with(['recruiter', 'skills'])->withCount('applications');

        // Scope to recruiter's own jobs if a non-admin recruiter requests
        $user = $request->user('sanctum') ?? $request->user();
        if ($user && $user->isRecruiter() && ! $user->isAdmin()) {
            $query->where('recruiter_id', $user->id);
        }

        // 2. Filter by department if provided
        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        // 3. Filter by status (open, closed, draft) if provided
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 4. Search keyword in job title, description, or department
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('department', 'like', "%{$search}%");
            });
        }

        // 5. Get latest jobs (15 per page)
        $jobs = $query->latest()->paginate(15);

        return JobResource::collection($jobs);
    }

    /**
     * Create and save a new job opening.
     */
    public function store(StoreJobRequest $request): JsonResponse
    {
        // 1. Get the current logged-in recruiter
        $user = $request->user();

        // 2. Create the job in database
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

        // 3. Attach mandatory and bonus skills to the job
        $mandatorySkills = $request->input('mandatory_skills', []);
        $bonusSkills = $request->input('bonus_skills', []);
        $this->syncSkills($job, $mandatorySkills, $bonusSkills);

        // 4. Return success response with the new job data
        return response()->json([
            'message' => 'Job created successfully',
            'job' => new JobResource($job->load(['recruiter', 'skills'])),
        ], 201);
    }

    /**
     * View full details of a specific job.
     */
    public function show(Job $job): JsonResponse
    {
        // Load recruiter info, skills, and total application count
        $job->load(['recruiter', 'skills'])->loadCount('applications');

        // Return job details
        return response()->json([
            'job' => new JobResource($job),
        ]);
    }

    /**
     * Update an existing job opening.
     */
    public function update(UpdateJobRequest $request, Job $job): JsonResponse
    {
        // Update job fields in database
        $job->update([
            'title' => $request->input('title', $job->title),
            'department' => $request->input('department', $job->department),
            'description' => $request->input('description', $job->description),
            'experience' => $request->input('experience', $job->experience),
            'salary_range' => $request->input('salary_range', $job->salary_range),
            'application_deadline' => $request->input('application_deadline', $job->application_deadline),
            'status' => $request->input('status', $job->status),
        ]);

        // Update skills if provided
        if ($request->has('mandatory_skills') || $request->has('bonus_skills')) {
            $mandatorySkills = $request->input('mandatory_skills', []);
            $bonusSkills = $request->input('bonus_skills', []);
            $this->syncSkills($job, $mandatorySkills, $bonusSkills);
        }

        // Return updated job data
        return response()->json([
            'message' => 'Job updated successfully',
            'job' => new JobResource($job->load(['recruiter', 'skills'])),
        ]);
    }

    /**
     * Delete a job opening.
     */
    public function destroy(Request $request, Job $job): JsonResponse
    {
        $user = $request->user();
        if (! $user || (! $user->isAdmin() && (! $user->isRecruiter() || (int) $job->recruiter_id !== (int) $user->id))) {
            return response()->json(['message' => 'Unauthorized to delete this job opening.'], 403);
        }

        // Delete job from database (cascades to related records)
        $job->delete();

        // Return success message
        return response()->json([
            'message' => 'Job deleted successfully',
        ]);
    }

    /**
     * Attach mandatory and bonus skills to a job opening.
     */
    private function syncSkills(Job $job, array $mandatorySkills = [], array $bonusSkills = []): void
    {
        $skillsToAttach = [];

        // Process mandatory skills
        foreach ($mandatorySkills as $skillName) {
            $name = trim($skillName);
            if ($name !== '') {
                $skill = Skill::firstOrCreate(['name' => $name]);
                $skillsToAttach[$skill->id] = ['is_mandatory' => true];
            }
        }

        // Process bonus skills
        foreach ($bonusSkills as $skillName) {
            $name = trim($skillName);
            if ($name !== '') {
                $skill = Skill::firstOrCreate(['name' => $name]);
                // If not already added as mandatory, mark as bonus (is_mandatory = false)
                if (! isset($skillsToAttach[$skill->id])) {
                    $skillsToAttach[$skill->id] = ['is_mandatory' => false];
                }
            }
        }

        // Save relations into job_skills table
        $job->skills()->sync($skillsToAttach);
    }
}
