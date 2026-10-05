<?php

namespace App\Http\Controllers;

use App\Http\Resources\CandidateResource;
use App\Http\Resources\UserResource;
use App\Models\Candidate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CandidateController extends Controller
{
    /**
     * List candidates with filtering.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Candidate::with(['latestResume', 'applications.job']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('skills_summary', 'like', "%{$search}%");
            });
        }

        if ($request->filled('min_experience')) {
            $query->where('experience_years', '>=', (float) $request->min_experience);
        }

        $candidates = $query->latest()->paginate($request->input('per_page', 15));

        return CandidateResource::collection($candidates);
    }

    /**
     * View candidate profile.
     */
    public function show(Candidate $candidate): JsonResponse
    {
        $candidate->load(['latestResume', 'resumes', 'applications.job']);

        return response()->json([
            'candidate' => new CandidateResource($candidate),
        ]);
    }

    /**
     * Update current user's candidate profile.
     */
    public function updateProfile(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'experience_years' => 'nullable|numeric|min:0|max:50',
            'education' => 'nullable|string|max:255',
            'skills_summary' => 'nullable|string|max:2000',
        ]);

        if (! empty($validated['name']) && $user->name !== $validated['name']) {
            $user->update(['name' => $validated['name']]);
        }
        if (array_key_exists('phone', $validated) && $user->phone !== $validated['phone']) {
            $user->update(['phone' => $validated['phone']]);
        }

        $candidate = $user->candidate;
        if (! $candidate) {
            $candidate = Candidate::create([
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $validated['phone'] ?? $user->phone,
                'experience_years' => $validated['experience_years'] ?? 0,
                'education' => $validated['education'] ?? null,
                'skills_summary' => $validated['skills_summary'] ?? null,
            ]);
        } else {
            $candidate->update([
                'name' => $validated['name'] ?? $candidate->name,
                'phone' => array_key_exists('phone', $validated) ? $validated['phone'] : $candidate->phone,
                'experience_years' => array_key_exists('experience_years', $validated) ? $validated['experience_years'] : $candidate->experience_years,
                'education' => array_key_exists('education', $validated) ? $validated['education'] : $candidate->education,
                'skills_summary' => array_key_exists('skills_summary', $validated) ? $validated['skills_summary'] : $candidate->skills_summary,
            ]);
        }

        $candidate->refresh()->load(['latestResume', 'resumes']);

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => new UserResource($user->fresh()),
            'candidate' => new CandidateResource($candidate),
        ]);
    }
}
