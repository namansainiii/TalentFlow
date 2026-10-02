<?php

namespace App\Http\Controllers;

use App\Http\Resources\CandidateResource;
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
}
