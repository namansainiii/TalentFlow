<?php

namespace App\Http\Controllers;

use App\Http\Resources\RecruiterResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RecruiterController extends Controller
{
    /**
     * List recruiters and hiring managers.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = User::whereHas('role', function ($q) {
            $q->whereIn('name', ['recruiter', 'admin']);
        })
            ->with(['role', 'postedJobs' => fn ($q) => $q->withCount('applications')])
            ->withCount(['postedJobs', 'conductedInterviews', 'assignedTasks']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $recruiters = $query->latest()->get();

        return RecruiterResource::collection($recruiters);
    }

    /**
     * View recruiter details with their posted jobs and activity.
     */
    public function show(User $recruiter): JsonResponse
    {
        if (! in_array($recruiter->role?->name, ['recruiter', 'admin'])) {
            return response()->json(['message' => 'User is not a recruiter'], 404);
        }

        $recruiter->load([
            'role',
            'postedJobs' => fn ($q) => $q->withCount('applications')->latest(),
            'conductedInterviews' => fn ($q) => $q->with(['application.candidate', 'application.job'])->latest('scheduled_at'),
        ])->loadCount(['postedJobs', 'conductedInterviews', 'assignedTasks']);

        return response()->json([
            'recruiter' => new RecruiterResource($recruiter),
        ]);
    }
}
