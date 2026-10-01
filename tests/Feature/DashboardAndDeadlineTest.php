<?php

namespace Tests\Feature;

use App\Jobs\SendTaskDeadlineReminderJob;
use App\Models\Application;
use App\Models\Candidate;
use App\Models\Interview;
use App\Models\Job;
use App\Models\Role;
use App\Models\TechnicalTask;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class DashboardAndDeadlineTest extends TestCase
{
    use RefreshDatabase;

    protected User $recruiter;

    protected function setUp(): void
    {
        parent::setUp();

        $recruiterRole = Role::create(['name' => 'recruiter', 'label' => 'Recruiter']);
        $candidateRole = Role::create(['name' => 'candidate', 'label' => 'Candidate']);

        $this->recruiter = User::create([
            'role_id' => $recruiterRole->id,
            'name' => 'Recruiter Boss',
            'email' => 'boss@recruiter.com',
            'password' => 'secret123',
        ]);

        $candUser = User::create([
            'role_id' => $candidateRole->id,
            'name' => 'Candidate One',
            'email' => 'cand1@test.com',
            'password' => 'secret123',
        ]);

        $candidate = Candidate::create([
            'user_id' => $candUser->id,
            'name' => 'Candidate One',
            'email' => 'cand1@test.com',
        ]);

        $job = Job::create([
            'recruiter_id' => $this->recruiter->id,
            'title' => 'DevOps Architect',
            'department' => 'Cloud',
            'description' => 'AWS cloud work',
            'experience' => '5 years',
            'application_deadline' => now()->addDays(20),
            'status' => 'open',
        ]);

        $application = Application::create([
            'job_id' => $job->id,
            'candidate_id' => $candidate->id,
            'status' => 'Shortlisted',
            'skill_score' => 85.00,
        ]);

        Interview::create([
            'application_id' => $application->id,
            'interviewer_id' => $this->recruiter->id,
            'scheduled_at' => Carbon::now()->addDays(1),
            'meeting_link' => 'https://meet.google.com/test',
            'status' => 'scheduled',
        ]);
    }

    public function test_dashboard_returns_analytics(): void
    {
        $response = $this->actingAs($this->recruiter, 'sanctum')
            ->getJson('/api/dashboard/analytics');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'analytics' => [
                    'total_jobs',
                    'active_jobs',
                    'active_candidates',
                    'interviews_this_week',
                    'pipeline_distribution',
                    'average_candidate_score',
                ],
            ])
            ->assertJsonPath('analytics.total_jobs', 1)
            ->assertJsonPath('analytics.average_candidate_score', 85);
    }

    public function test_deadline_automation_marks_overdue_tasks_and_queues_reminders(): void
    {
        Queue::fake();

        $candRole = Role::where('name', 'candidate')->first();
        $user = User::create([
            'role_id' => $candRole->id,
            'name' => 'Test',
            'email' => 'tasktest@test.com',
            'password' => 'secret123',
        ]);

        $candidate = Candidate::create([
            'user_id' => $user->id,
            'name' => 'Test',
            'email' => 'tasktest@test.com',
        ]);

        $job = Job::first();
        $app = Application::create([
            'job_id' => $job->id,
            'candidate_id' => $candidate->id,
            'status' => 'Technical Task',
            'skill_score' => 80.0,
        ]);

        // Task 1: Past deadline -> should be marked Overdue
        $pastTask = TechnicalTask::create([
            'application_id' => $app->id,
            'assigned_by_user_id' => $this->recruiter->id,
            'title' => 'Expired Task',
            'description' => 'Due yesterday',
            'deadline' => Carbon::yesterday(),
            'status' => 'Pending',
        ]);

        // Task 2: Due within 12 hours -> should queue reminder
        $upcomingTask = TechnicalTask::create([
            'application_id' => $app->id,
            'assigned_by_user_id' => $this->recruiter->id,
            'title' => 'Urgent Task',
            'description' => 'Due in 12 hours',
            'deadline' => Carbon::now()->addHours(12),
            'status' => 'Pending',
        ]);

        $this->artisan('app:check-deadlines')
            ->expectsOutputToContain('Marked 1 technical tasks as Overdue.')
            ->expectsOutputToContain('Queued 1 24h deadline reminder jobs.')
            ->assertExitCode(0);

        $this->assertEquals('Overdue', $pastTask->fresh()->status);

        Queue::assertPushed(SendTaskDeadlineReminderJob::class, function ($job) use ($upcomingTask) {
            return $job->task->id === $upcomingTask->id;
        });
    }
}
