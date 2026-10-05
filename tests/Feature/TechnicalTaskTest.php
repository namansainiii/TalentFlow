<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Candidate;
use App\Models\Job;
use App\Models\Role;
use App\Models\TechnicalTask;
use App\Models\User;
use App\Notifications\TaskReviewedNotification;
use App\Notifications\TaskSubmittedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TechnicalTaskTest extends TestCase
{
    use RefreshDatabase;

    protected User $recruiter;

    protected User $candidateUser;

    protected Application $application;

    protected function setUp(): void
    {
        parent::setUp();
        Notification::fake();

        $recruiterRole = Role::create(['name' => 'recruiter', 'label' => 'Recruiter']);
        $candidateRole = Role::create(['name' => 'candidate', 'label' => 'Candidate']);

        $this->recruiter = User::create([
            'role_id' => $recruiterRole->id,
            'name' => 'Lead Reviewer',
            'email' => 'reviewer@test.com',
            'password' => 'secret123',
        ]);

        $this->candidateUser = User::create([
            'role_id' => $candidateRole->id,
            'name' => 'Developer Dan',
            'email' => 'dan@test.com',
            'password' => 'secret123',
        ]);

        $candidate = Candidate::create([
            'user_id' => $this->candidateUser->id,
            'name' => 'Developer Dan',
            'email' => 'dan@test.com',
        ]);

        $job = Job::create([
            'recruiter_id' => $this->recruiter->id,
            'title' => 'Full-Stack Developer',
            'department' => 'Engineering',
            'description' => 'Job description',
            'experience' => '3 years',
            'application_deadline' => now()->addDays(20),
            'status' => 'open',
        ]);

        $this->application = Application::create([
            'job_id' => $job->id,
            'candidate_id' => $candidate->id,
            'status' => 'Technical Task',
            'skill_score' => 88.0,
        ]);
    }

    public function test_recruiter_can_assign_technical_task(): void
    {
        $response = $this->actingAs($this->recruiter, 'sanctum')
            ->postJson("/api/applications/{$this->application->id}/technical-tasks", [
                'title' => 'Build a REST API with Auth',
                'description' => 'Implement Sanctum authentication and token tests',
                'deadline' => now()->addDays(4)->toDateTimeString(),
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('task.title', 'Build a REST API with Auth')
            ->assertJsonPath('task.status', 'Pending');

        $this->assertDatabaseHas('technical_tasks', [
            'application_id' => $this->application->id,
            'title' => 'Build a REST API with Auth',
            'status' => 'Pending',
        ]);
    }

    public function test_candidate_can_start_and_submit_task(): void
    {
        $task = TechnicalTask::create([
            'application_id' => $this->application->id,
            'assigned_by_user_id' => $this->recruiter->id,
            'title' => 'Coding Challenge',
            'description' => 'Solve the puzzle',
            'deadline' => now()->addDays(2),
            'status' => 'Pending',
        ]);

        // Start task
        $startResponse = $this->actingAs($this->candidateUser, 'sanctum')
            ->patchJson("/api/technical-tasks/{$task->id}/start");

        $startResponse->assertStatus(200)
            ->assertJsonPath('task.status', 'In Progress');

        // Submit task
        $submitResponse = $this->actingAs($this->candidateUser, 'sanctum')
            ->postJson("/api/technical-tasks/{$task->id}/submit", [
                'repository_url' => 'https://github.com/dan/challenge-solution',
                'notes' => 'Implemented using service pattern and 10 feature tests.',
            ]);

        $submitResponse->assertStatus(201)
            ->assertJsonPath('task.status', 'Submitted');

        $this->assertDatabaseHas('task_submissions', [
            'technical_task_id' => $task->id,
            'repository_url' => 'https://github.com/dan/challenge-solution',
        ]);

        // Assert notification sent to recruiter
        Notification::assertSentTo(
            $this->recruiter,
            TaskSubmittedNotification::class
        );
    }

    public function test_recruiter_can_review_technical_task(): void
    {
        $task = TechnicalTask::create([
            'application_id' => $this->application->id,
            'assigned_by_user_id' => $this->recruiter->id,
            'title' => 'Coding Task',
            'description' => 'Details',
            'deadline' => now()->addDays(2),
            'status' => 'Submitted',
        ]);

        $task->submissions()->create([
            'repository_url' => 'https://github.com/dan/solution',
            'submitted_at' => now(),
        ]);

        $response = $this->actingAs($this->recruiter, 'sanctum')
            ->postJson("/api/technical-tasks/{$task->id}/review", [
                'score' => 95,
                'feedback' => 'Exceptional code quality and test coverage.',
                'status' => 'Reviewed',
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('task.status', 'Reviewed');

        $this->assertDatabaseHas('task_submissions', [
            'technical_task_id' => $task->id,
            'score' => 95,
            'feedback' => 'Exceptional code quality and test coverage.',
        ]);

        Notification::assertSentTo($this->candidateUser, TaskReviewedNotification::class);
    }

    public function test_candidate_cannot_view_or_submit_another_candidates_technical_task(): void
    {
        $otherRole = Role::where('name', 'candidate')->first();
        $otherUser = User::create([
            'role_id' => $otherRole->id,
            'name' => 'Stranger Dev',
            'email' => 'strangerdev@test.com',
            'password' => 'secret123',
        ]);
        $otherCandidate = Candidate::create([
            'user_id' => $otherUser->id,
            'name' => 'Stranger Dev',
            'email' => 'strangerdev@test.com',
        ]);
        $job = Job::first();
        $otherApp = Application::create([
            'job_id' => $job->id,
            'candidate_id' => $otherCandidate->id,
            'status' => 'Technical Task',
            'skill_score' => 82.0,
        ]);
        $otherTask = TechnicalTask::create([
            'application_id' => $otherApp->id,
            'assigned_by_user_id' => $this->recruiter->id,
            'title' => 'Secret Coding Challenge',
            'description' => 'Confidential prompt',
            'deadline' => now()->addDays(2),
            'status' => 'Pending',
        ]);

        // Attempt viewing
        $viewResponse = $this->actingAs($this->candidateUser, 'sanctum')
            ->getJson("/api/technical-tasks/{$otherTask->id}");
        $viewResponse->assertStatus(403);

        // Attempt starting
        $startResponse = $this->actingAs($this->candidateUser, 'sanctum')
            ->patchJson("/api/technical-tasks/{$otherTask->id}/start");
        $startResponse->assertStatus(403);

        // Attempt submitting
        $submitResponse = $this->actingAs($this->candidateUser, 'sanctum')
            ->postJson("/api/technical-tasks/{$otherTask->id}/submit", [
                'repository_url' => 'https://github.com/hacker/fake-repo',
            ]);
        $submitResponse->assertStatus(403);
    }

    public function test_recruiter_can_upload_up_to_5_attachments_when_assigning_task(): void
    {
        Storage::fake('local');

        $files = [
            UploadedFile::fake()->image('mockup.png'),
            UploadedFile::fake()->create('brief.pdf', 500, 'application/pdf'),
            UploadedFile::fake()->create('instructions.docx', 300, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'),
        ];

        $response = $this->actingAs($this->recruiter, 'sanctum')
            ->post("/api/applications/{$this->application->id}/technical-tasks", [
                'title' => 'Design System Challenge',
                'description' => 'Build a responsive component matching mockups',
                'deadline' => now()->addDays(3)->toDateTimeString(),
                'files' => $files,
            ], ['Accept' => 'application/json']);

        $response->assertStatus(201)
            ->assertJsonPath('task.title', 'Design System Challenge');

        $task = TechnicalTask::where('title', 'Design System Challenge')->first();
        $this->assertNotNull($task);
        $this->assertIsArray($task->attachments);
        $this->assertCount(3, $task->attachments);
        $this->assertEquals('mockup.png', $task->attachments[0]['name']);

        Storage::disk('local')->assertExists($task->attachments[0]['path']);
        Storage::disk('local')->assertExists($task->attachments[1]['path']);
        Storage::disk('local')->assertExists($task->attachments[2]['path']);
    }

    public function test_assigning_task_fails_if_more_than_5_files_uploaded(): void
    {
        Storage::fake('local');

        $files = [
            UploadedFile::fake()->image('img1.png'),
            UploadedFile::fake()->image('img2.png'),
            UploadedFile::fake()->image('img3.png'),
            UploadedFile::fake()->image('img4.png'),
            UploadedFile::fake()->image('img5.png'),
            UploadedFile::fake()->image('img6.png'),
        ];

        $response = $this->actingAs($this->recruiter, 'sanctum')
            ->post("/api/applications/{$this->application->id}/technical-tasks", [
                'title' => 'Exceeding Limit Challenge',
                'description' => 'Too many files attached',
                'deadline' => now()->addDays(3)->toDateTimeString(),
                'files' => $files,
            ], ['Accept' => 'application/json']);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['files']);
    }

    public function test_assigning_task_fails_for_disallowed_file_types(): void
    {
        Storage::fake('local');

        $files = [
            UploadedFile::fake()->create('malicious.exe', 100, 'application/x-msdownload'),
        ];

        $response = $this->actingAs($this->recruiter, 'sanctum')
            ->post("/api/applications/{$this->application->id}/technical-tasks", [
                'title' => 'Invalid File Challenge',
                'description' => 'Disallowed format',
                'deadline' => now()->addDays(3)->toDateTimeString(),
                'files' => $files,
            ], ['Accept' => 'application/json']);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['files.0']);
    }

    public function test_can_download_task_attachment(): void
    {
        Storage::fake('local');

        $filePath = 'task_attachments/spec.pdf';
        Storage::disk('local')->put($filePath, 'fake pdf content');

        $task = TechnicalTask::create([
            'application_id' => $this->application->id,
            'assigned_by_user_id' => $this->recruiter->id,
            'title' => 'Downloadable Task',
            'description' => 'Check the attached spec',
            'attachments' => [
                [
                    'name' => 'spec.pdf',
                    'path' => $filePath,
                    'size' => 1234,
                    'mime' => 'application/pdf',
                ],
            ],
            'deadline' => now()->addDays(3),
            'status' => 'Pending',
        ]);

        $response = $this->actingAs($this->candidateUser, 'sanctum')
            ->get("/api/technical-tasks/{$task->id}/attachments/0");

        $response->assertStatus(200);
    }
}
