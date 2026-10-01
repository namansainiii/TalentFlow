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

        // Attach mandatory skills
        if ($request->filled('mandatory_skills')) {
            foreach ($request->mandatory_skills as $skillName) {
                $skill = Skill::firstOrCreate(['name' => trim($skillName)]);
                $job->skills()->attach($skill->id, ['is_mandatory' => true]);
            }
        }

        // Attach bonus skills
        if ($request->filled('bonus_skills')) {
            foreach ($request->bonus_skills as $skillName) {
                $skill = Skill::firstOrCreate(['name' => trim($skillName)]);
                // Only attach if not already mandatory
                if (!$job->skills()->where('skill_id', $skill->id)->exists()) {
                    $job->skills()->attach($skill->id, ['is_mandatory' => false]);
                }
            }
        }

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
            $syncData = [];

            if ($request->filled('mandatory_skills')) {
                foreach ($request->mandatory_skills as $skillName) {
                    $skill = Skill::firstOrCreate(['name' => trim($skillName)]);
                    $syncData[$skill->id] = ['is_mandatory' => true];
                }
            }

            if ($request->filled('bonus_skills')) {
                foreach ($request->bonus_skills as $skillName) {
                    $skill = Skill::firstOrCreate(['name' => trim($skillName)]);
                    if (!isset($syncData[$skill->id])) {
                        $syncData[$skill->id] = ['is_mandatory' => false];
                    }
                }
            }

            $job->skills()->sync($syncData);
        }

        return response()->json([
            'message' => 'Job updated successfully',
            'job' => new JobResource($job->load(['recruiter', 'skills'])),
        ]);
    }

    /**
     * Delete a job.
     */
    public function destroy(Job $job): JsonResponse
    {
        $job->delete();

        return response()->json([
            'message' => 'Job deleted successfully',
        ]);
    }
}
