<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Candidate;
use App\Models\Interview;
use App\Models\Job;
use App\Models\Role;
use App\Models\User;
use App\Notifications\InterviewFeedbackNotification;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class InterviewTest extends TestCase
{
    use RefreshDatabase;

    protected User $recruiter;

    protected User $candidateUser;

    protected Application $application;

    protected function setUp(): void
    {
        parent::setUp();

        $recruiterRole = Role::create(['name' => 'recruiter', 'label' => 'Recruiter']);
        $candidateRole = Role::create(['name' => 'candidate', 'label' => 'Candidate']);

        $this->recruiter = User::create([
            'role_id' => $recruiterRole->id,
            'name' => 'Recruiter Bob',
            'email' => 'bob.recruiter@test.com',
            'password' => 'secret123',
        ]);

        $this->candidateUser = User::create([
            'role_id' => $candidateRole->id,
            'name' => 'Candidate Carol',
            'email' => 'carol@test.com',
            'password' => 'secret123',
        ]);

        $candidate = Candidate::create([
            'user_id' => $this->candidateUser->id,
            'name' => 'Candidate Carol',
            'email' => 'carol@test.com',
        ]);

        $job = Job::create([
            'recruiter_id' => $this->recruiter->id,
            'title' => 'Software Engineer',
            'department' => 'Tech',
            'description' => 'Engineer position',
            'experience' => '2 years',
            'application_deadline' => now()->addDays(20),
            'status' => 'open',
        ]);

        $this->application = Application::create([
            'job_id' => $job->id,
            'candidate_id' => $candidate->id,
            'status' => 'Interview',
            'skill_score' => 90.0,
        ]);
    }

    public function test_recruiter_can_schedule_interview(): void
    {
        $time = Carbon::tomorrow()->setTime(10, 0);

        $response = $this->actingAs($this->recruiter, 'sanctum')
            ->postJson("/api/applications/{$this->application->id}/interviews", [
                'interviewer_id' => $this->recruiter->id,
                'scheduled_at' => $time->toDateTimeString(),
                'meeting_link' => 'https://meet.google.com/test-meet',
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('interview.meeting_link', 'https://meet.google.com/test-meet');

        $this->assertDatabaseHas('interviews', [
            'application_id' => $this->application->id,
            'interviewer_id' => $this->recruiter->id,
            'status' => 'scheduled',
        ]);
    }

    public function test_conflict_validation_prevents_double_booking_interviewer(): void
    {
        $time = Carbon::tomorrow()->setTime(14, 0);

        Interview::create([
            'application_id' => $this->application->id,
            'interviewer_id' => $this->recruiter->id,
            'scheduled_at' => $time,
            'meeting_link' => 'https://meet.google.com/first',
            'status' => 'scheduled',
        ]);

        // Attempt booking within 30 minutes window for the same interviewer
        $conflictTime = (clone $time)->addMinutes(15);

        $response = $this->actingAs($this->recruiter, 'sanctum')
            ->postJson("/api/applications/{$this->application->id}/interviews", [
                'interviewer_id' => $this->recruiter->id,
                'scheduled_at' => $conflictTime->toDateTimeString(),
                'meeting_link' => 'https://meet.google.com/second',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['scheduled_at']);
    }

    public function test_recruiter_can_complete_interview_with_feedback(): void
    {
        Notification::fake();

        $interview = Interview::create([
            'application_id' => $this->application->id,
            'interviewer_id' => $this->recruiter->id,
            'scheduled_at' => Carbon::now()->subHour(),
            'meeting_link' => 'https://meet.google.com/done',
            'status' => 'scheduled',
        ]);

        $response = $this->actingAs($this->recruiter, 'sanctum')
            ->patchJson("/api/interviews/{$interview->id}/complete", [
                'feedback' => 'Strong communication and problem solving skills.',
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('interview.status', 'completed');

        $this->assertDatabaseHas('interviews', [
            'id' => $interview->id,
            'status' => 'completed',
            'feedback' => 'Strong communication and problem solving skills.',
        ]);

        Notification::assertSentTo(
            $this->candidateUser,
            InterviewFeedbackNotification::class
        );
    }

    public function test_recruiter_can_cancel_interview(): void
    {
        $interview = Interview::create([
            'application_id' => $this->application->id,
            'interviewer_id' => $this->recruiter->id,
            'scheduled_at' => Carbon::tomorrow()->setTime(16, 0),
            'meeting_link' => 'https://meet.google.com/cancel',
            'status' => 'scheduled',
        ]);

        $response = $this->actingAs($this->recruiter, 'sanctum')
            ->patchJson("/api/interviews/{$interview->id}/cancel", [
                'reason' => 'Candidate requested postponement.',
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('interview.status', 'cancelled');
    }

    public function test_recruiter_can_update_interview(): void
    {
        $interview = Interview::create([
            'application_id' => $this->application->id,
            'interviewer_id' => $this->recruiter->id,
            'scheduled_at' => Carbon::tomorrow()->setTime(14, 0),
            'meeting_link' => 'https://meet.google.com/initial',
            'status' => 'scheduled',
        ]);

        $newTime = Carbon::tomorrow()->setTime(17, 30);

        $response = $this->actingAs($this->recruiter, 'sanctum')
            ->putJson("/api/interviews/{$interview->id}", [
                'scheduled_at' => $newTime->toDateTimeString(),
                'meeting_link' => 'https://meet.google.com/updated-room',
                'status' => 'rescheduled',
                'feedback' => 'Rescheduled per recruiter request',
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('interview.meeting_link', 'https://meet.google.com/updated-room')
            ->assertJsonPath('interview.status', 'rescheduled');

        $this->assertDatabaseHas('interviews', [
            'id' => $interview->id,
            'meeting_link' => 'https://meet.google.com/updated-room',
            'status' => 'rescheduled',
            'feedback' => 'Rescheduled per recruiter request',
        ]);
    }

    public function test_candidate_cannot_view_another_candidates_interview(): void
    {
        $otherRole = Role::where('name', 'candidate')->first();
        $otherUser = User::create([
            'role_id' => $otherRole->id,
            'name' => 'Stranger Candidate',
            'email' => 'stranger@test.com',
            'password' => 'secret123',
        ]);
        $otherCandidate = Candidate::create([
            'user_id' => $otherUser->id,
            'name' => 'Stranger Candidate',
            'email' => 'stranger@test.com',
        ]);
        $job = Job::first();
        $otherApp = Application::create([
            'job_id' => $job->id,
            'candidate_id' => $otherCandidate->id,
            'status' => 'Interview',
            'skill_score' => 80.0,
        ]);
        $otherInterview = Interview::create([
            'application_id' => $otherApp->id,
            'interviewer_id' => $this->recruiter->id,
            'scheduled_at' => Carbon::tomorrow()->setTime(11, 0),
            'meeting_link' => 'https://meet.google.com/private',
            'status' => 'scheduled',
        ]);

        $response = $this->actingAs($this->candidateUser, 'sanctum')
            ->getJson("/api/interviews/{$otherInterview->id}");

        $response->assertStatus(403);
    }
}
