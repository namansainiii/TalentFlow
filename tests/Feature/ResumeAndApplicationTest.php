<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Candidate;
use App\Models\Job;
use App\Models\Resume;
use App\Models\Role;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ResumeAndApplicationTest extends TestCase
{
    use RefreshDatabase;

    protected User $recruiter;

    protected User $candidateUser;

    protected Candidate $candidate;

    protected Job $job;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');

        $recruiterRole = Role::create(['name' => 'recruiter', 'label' => 'Recruiter']);
        $candidateRole = Role::create(['name' => 'candidate', 'label' => 'Candidate']);

        $this->recruiter = User::create([
            'role_id' => $recruiterRole->id,
            'name' => 'Recruiter Dave',
            'email' => 'dave@recruiter.com',
            'password' => 'secret123',
        ]);

        $this->candidateUser = User::create([
            'role_id' => $candidateRole->id,
            'name' => 'Jane Candidate',
            'email' => 'jane@candidate.com',
            'password' => 'secret123',
        ]);

        $this->candidate = Candidate::create([
            'user_id' => $this->candidateUser->id,
            'name' => 'Jane Candidate',
            'email' => 'jane@candidate.com',
            'phone' => '+123456789',
            'experience_years' => 4.0,
            'education' => 'Bachelor of Computer Science',
            'skills_summary' => 'PHP, Laravel, MySQL, REST API',
        ]);

        $this->job = Job::create([
            'recruiter_id' => $this->recruiter->id,
            'title' => 'Laravel Backend Engineer',
            'department' => 'Backend',
            'description' => 'Build high-volume APIs',
            'experience' => '3 years',
            'application_deadline' => now()->addDays(20),
            'status' => 'open',
        ]);

        $skill1 = Skill::create(['name' => 'PHP']);
        $skill2 = Skill::create(['name' => 'Laravel']);
        $skill3 = Skill::create(['name' => 'MySQL']);

        $this->job->skills()->attach([
            $skill1->id => ['is_mandatory' => true],
            $skill2->id => ['is_mandatory' => true],
            $skill3->id => ['is_mandatory' => true],
        ]);
    }

    public function test_candidate_can_upload_pdf_resume(): void
    {
        $file = UploadedFile::fake()->create('resume.pdf', 500, 'application/pdf');

        $response = $this->actingAs($this->candidateUser, 'sanctum')
            ->postJson('/api/resumes/upload', [
                'resume' => $file,
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('message', 'Resume uploaded successfully and queued for processing');

        $this->assertDatabaseHas('resumes', [
            'candidate_id' => $this->candidate->id,
            'file_name' => 'resume.pdf',
        ]);
    }

    public function test_candidate_can_apply_for_job_and_system_calculates_score(): void
    {
        $file = UploadedFile::fake()->create('my_resume.pdf', 300, 'application/pdf');

        $response = $this->actingAs($this->candidateUser, 'sanctum')
            ->postJson("/api/jobs/{$this->job->id}/apply", [
                'resume' => $file,
                'notes' => 'Looking forward to hearing from you.',
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('application.status', 'Applied');

        $this->assertDatabaseHas('applications', [
            'job_id' => $this->job->id,
            'candidate_id' => $this->candidate->id,
            'status' => 'Applied',
        ]);

        // Assert score was computed (> 0)
        $application = Application::where('job_id', $this->job->id)->first();
        $this->assertNotNull($application);
        $this->assertGreaterThan(0, (float) $application->skill_score);

        // Assert status history was logged
        $this->assertDatabaseHas('application_status_histories', [
            'application_id' => $application->id,
            'to_status' => 'Applied',
        ]);
    }

    public function test_candidate_cannot_apply_twice_for_the_same_job(): void
    {
        Application::create([
            'job_id' => $this->job->id,
            'candidate_id' => $this->candidate->id,
            'status' => 'Applied',
            'skill_score' => 80.0,
        ]);

        $response = $this->actingAs($this->candidateUser, 'sanctum')
            ->postJson("/api/jobs/{$this->job->id}/apply", [
                'notes' => 'Duplicate application',
            ]);

        $response->assertStatus(422);
    }

    public function test_recruiter_can_advance_hiring_pipeline_and_records_history(): void
    {
        $application = Application::create([
            'job_id' => $this->job->id,
            'candidate_id' => $this->candidate->id,
            'status' => 'Applied',
            'skill_score' => 85.0,
        ]);

        // Advance to Screening
        $response = $this->actingAs($this->recruiter, 'sanctum')
            ->patchJson("/api/applications/{$application->id}/status", [
                'status' => 'Screening',
                'comment' => 'Passed initial resume scan',
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('application.status', 'Screening');

        $this->assertDatabaseHas('application_status_histories', [
            'application_id' => $application->id,
            'from_status' => 'Applied',
            'to_status' => 'Screening',
            'comment' => 'Passed initial resume scan',
        ]);

        // Advance to Shortlisted
        $this->actingAs($this->recruiter, 'sanctum')
            ->patchJson("/api/applications/{$application->id}/status", [
                'status' => 'Shortlisted',
                'comment' => 'Shortlisted for interview',
            ]);

        // Check history endpoint
        $historyResponse = $this->actingAs($this->recruiter, 'sanctum')
            ->getJson("/api/applications/{$application->id}/history");

        $historyResponse->assertStatus(200)
            ->assertJsonCount(2, 'histories');
    }

    public function test_candidate_cannot_view_another_candidates_application_or_resume(): void
    {
        $otherCandidateRole = Role::where('name', 'candidate')->first();
        $otherUser = User::create([
            'role_id' => $otherCandidateRole->id,
            'name' => 'Other Person',
            'email' => 'other@candidate.com',
            'password' => 'secret123',
        ]);
        $otherCandidate = Candidate::create([
            'user_id' => $otherUser->id,
            'name' => 'Other Person',
            'email' => 'other@candidate.com',
        ]);
        $otherResume = Resume::create([
            'candidate_id' => $otherCandidate->id,
            'file_path' => 'resumes/other.pdf',
            'file_name' => 'other.pdf',
            'file_size' => 100,
            'status' => 'uploaded',
        ]);
        $otherApplication = Application::create([
            'job_id' => $this->job->id,
            'candidate_id' => $otherCandidate->id,
            'resume_id' => $otherResume->id,
            'status' => 'Applied',
            'skill_score' => 70.0,
        ]);

        // Attempt viewing other candidate's application
        $appResponse = $this->actingAs($this->candidateUser, 'sanctum')
            ->getJson("/api/applications/{$otherApplication->id}");
        $appResponse->assertStatus(403);

        // Attempt viewing other candidate's resume
        $resumeResponse = $this->actingAs($this->candidateUser, 'sanctum')
            ->getJson("/api/resumes/{$otherResume->id}");
        $resumeResponse->assertStatus(403);

        // Recruiter can view both without restriction
        $recruiterAppResponse = $this->actingAs($this->recruiter, 'sanctum')
            ->getJson("/api/applications/{$otherApplication->id}");
        $recruiterAppResponse->assertStatus(200);

        $recruiterResumeResponse = $this->actingAs($this->recruiter, 'sanctum')
            ->getJson("/api/resumes/{$otherResume->id}");
        $recruiterResumeResponse->assertStatus(200);
    }
}
