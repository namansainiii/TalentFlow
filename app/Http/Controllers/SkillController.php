<?php

namespace App\Http\Controllers;

use App\Http\Resources\SkillResource;
use App\Models\Skill;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    /**
     * List all available skills.
     */
    public function index(): JsonResponse
    {
        $skills = Skill::orderBy('name')->get();

        return response()->json([
            'skills' => SkillResource::collection($skills),
        ]);
    }

    /**
     * Add a new skill.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|unique:skills,name|max:100',
        ]);

        $skill = Skill::create([
            'name' => trim($request->name),
        ]);

        return response()->json([
            'message' => 'Skill created successfully',
            'skill' => new SkillResource($skill),
        ], 201);
    }
}
