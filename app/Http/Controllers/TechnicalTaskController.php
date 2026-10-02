<?php

namespace App\Http\Controllers;

use App\Events\TaskSubmitted;
use App\Http\Requests\AssignTechnicalTaskRequest;
use App\Http\Requests\ReviewTechnicalTaskRequest;
use App\Http\Requests\SubmitTechnicalTaskRequest;
use App\Http\Resources\TaskSubmissionResource;
use App\Http\Resources\TechnicalTaskResource;
use App\Models\Application;
use App\Models\TaskSubmission;
use App\Models\TechnicalTask;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TechnicalTaskController extends Controller
{
    /**
     * List technical tasks.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $user = $request->user();
        $query = TechnicalTask::with(['assignedByUser', 'submissions', 'latestSubmission', 'application.candidate', 'application.job']);

        // Candidate can only view their tasks
        if ($user->isCandidate() && ! $user->isRecruiter()) {
            if ($user->candidate) {
                $query->whereHas('application', function ($q) use ($user) {
                    $q->where('candidate_id', $user->candidate->id);
                });
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('application_id')) {
            $query->where('application_id', $request->application_id);
        }

        $tasks = $query->latest()->paginate($request->input('per_page', 15));

        return TechnicalTaskResource::collection($tasks);
    }

    /**
     * Assign a new coding task to an application.
     */
    public function assign(AssignTechnicalTaskRequest $request, Application $application): JsonResponse
    {
        $user = $request->user();

        $task = TechnicalTask::create([
            'application_id' => $application->id,
            'assigned_by_user_id' => $user->id,
            'title' => $request->title,
            'description' => $request->description,
            'deadline' => $request->deadline,
            'status' => TechnicalTask::STATUS_PENDING,
        ]);

        return response()->json([
            'message' => 'Technical task assigned successfully',
            'task' => new TechnicalTaskResource($task->load(['assignedByUser', 'application.candidate'])),
        ], 201);
    }

    /**
     * View task details.
     */
    public function show(Request $request, TechnicalTask $task): JsonResponse
    {
        $user = $request->user();
        if ($user->isCandidate() && ! $user->isRecruiter() && ! $user->isAdmin()) {
            if (! $user->candidate || $user->candidate->id !== $task->application?->candidate_id) {
                return response()->json([
                    'message' => 'Forbidden: You do not have access to this task.',
                ], 403);
            }
        }

        $task->load(['assignedByUser', 'submissions', 'latestSubmission', 'application.candidate', 'application.job']);

        return response()->json([
            'task' => new TechnicalTaskResource($task),
        ]);
    }

    /**
     * Candidate marks task as In Progress.
     */
    public function start(Request $request, TechnicalTask $task): JsonResponse
    {
        $user = $request->user();
        if ($user->isCandidate() && ! $user->isRecruiter() && ! $user->isAdmin()) {
            if (! $user->candidate || $user->candidate->id !== $task->application?->candidate_id) {
                return response()->json([
                    'message' => 'Forbidden: You do not have access to this task.',
                ], 403);
            }
        }

        if ($task->status === TechnicalTask::STATUS_PENDING) {
            $task->update([
                'status' => TechnicalTask::STATUS_IN_PROGRESS,
            ]);
        }

        return response()->json([
            'message' => 'Task marked as In Progress',
            'task' => new TechnicalTaskResource($task),
        ]);
    }

    /**
     * Candidate submits task solution.
     */
    public function submit(SubmitTechnicalTaskRequest $request, TechnicalTask $task): JsonResponse
    {
        $user = $request->user();
        if ($user->isCandidate() && ! $user->isRecruiter() && ! $user->isAdmin()) {
            if (! $user->candidate || $user->candidate->id !== $task->application?->candidate_id) {
                return response()->json([
                    'message' => 'Forbidden: You do not have access to this task.',
                ], 403);
            }
        }

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('task_submissions', 'local');
        }

        $submission = TaskSubmission::create([
            'technical_task_id' => $task->id,
            'repository_url' => $request->repository_url,
            'notes' => $request->notes,
            'file_path' => $filePath,
            'submitted_at' => now(),
        ]);

        $task->update([
            'status' => TechnicalTask::STATUS_SUBMITTED,
        ]);

        // Dispatches event: notifies recruiter of new submission!
        event(new TaskSubmitted($task, $submission));

        return response()->json([
            'message' => 'Technical task submitted successfully',
            'submission' => new TaskSubmissionResource($submission),
            'task' => new TechnicalTaskResource($task->fresh(['submissions', 'latestSubmission'])),
        ], 201);
    }

    /**
     * Recruiter reviews submitted task, adds score and feedback.
     */
    public function review(ReviewTechnicalTaskRequest $request, TechnicalTask $task): JsonResponse
    {
        $submission = $task->latestSubmission;

        if (! $submission) {
            return response()->json([
                'message' => 'No submission found for this task yet.',
            ], 422);
        }

        $submission->update([
            'score' => $request->score,
            'feedback' => $request->feedback,
        ]);

        $task->update([
            'status' => $request->status ?? TechnicalTask::STATUS_REVIEWED,
        ]);

        return response()->json([
            'message' => 'Technical task reviewed successfully',
            'task' => new TechnicalTaskResource($task->fresh(['submissions', 'latestSubmission'])),
        ]);
    }
}
