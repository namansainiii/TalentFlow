<?php

namespace Tests\Feature;

use App\Models\Job;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JobTest extends TestCase
{
    use RefreshDatabase;

    protected User $recruiter;

    protected User $candidate;

    protected function setUp(): void
    {
        parent::setUp();

        $adminRole = Role::create(['name' => 'admin', 'label' => 'Admin']);
        $recruiterRole = Role::create(['name' => 'recruiter', 'label' => 'Recruiter']);
        $candidateRole = Role::create(['name' => 'candidate', 'label' => 'Candidate']);

        $this->recruiter = User::create([
            'role_id' => $recruiterRole->id,
            'name' => 'Tech Recruiter',
            'email' => 'recruiter@test.com',
            'password' => 'secret123',
        ]);

        $this->candidate = User::create([
            'role_id' => $candidateRole->id,
            'name' => 'Candidate User',
            'email' => 'candidate@test.com',
            'password' => 'secret123',
        ]);
    }

    public function test_anyone_can_list_and_view_jobs(): void
    {
        $job = Job::create([
            'recruiter_id' => $this->recruiter->id,
            'title' => 'Backend Developer',
            'department' => 'Engineering',
            'description' => 'Develop APIs',
            'experience' => '3 years',
            'application_deadline' => now()->addDays(10),
            'status' => 'open',
        ]);

        $response = $this->getJson('/api/jobs');
        $response->assertStatus(200)
            ->assertJsonPath('data.0.title', 'Backend Developer');

        $detailResponse = $this->getJson("/api/jobs/{$job->id}");
        $detailResponse->assertStatus(200)
            ->assertJsonPath('job.title', 'Backend Developer');
    }

    public function test_recruiter_can_create_job_with_skills(): void
    {
        $response = $this->actingAs($this->recruiter, 'sanctum')
            ->postJson('/api/jobs', [
                'title' => 'Laravel Architect',
                'department' => 'Software',
                'description' => 'Architect systems',
                'experience' => '5 years',
                'salary_range' => '$90k - $120k',
                'application_deadline' => now()->addDays(20)->toDateString(),
                'mandatory_skills' => ['PHP', 'Laravel', 'MySQL'],
                'bonus_skills' => ['Docker', 'Redis'],
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('job.title', 'Laravel Architect')
            ->assertJsonCount(5, 'job.skills');

        $this->assertDatabaseHas('jobs', ['title' => 'Laravel Architect']);
        $this->assertDatabaseHas('skills', ['name' => 'PHP']);
    }

    public function test_candidate_cannot_create_job(): void
    {
        $response = $this->actingAs($this->candidate, 'sanctum')
            ->postJson('/api/jobs', [
                'title' => 'Hacker Job',
                'department' => 'Security',
                'description' => 'Unauthorized job creation',
                'experience' => '1 year',
                'application_deadline' => now()->addDays(5)->toDateString(),
            ]);

        $response->assertStatus(403);
    }

    public function test_recruiter_can_update_job(): void
    {
        $job = Job::create([
            'recruiter_id' => $this->recruiter->id,
            'title' => 'Junior Dev',
            'department' => 'Eng',
            'description' => 'Desc',
            'experience' => '1 year',
            'application_deadline' => now()->addDays(15),
            'status' => 'open',
        ]);

        $response = $this->actingAs($this->recruiter, 'sanctum')
            ->putJson("/api/jobs/{$job->id}", [
                'title' => 'Mid-level Developer',
                'experience' => '2 years',
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('job.title', 'Mid-level Developer');

        $this->assertDatabaseHas('jobs', ['title' => 'Mid-level Developer']);
    }

    public function test_recruiter_can_delete_job(): void
    {
        $job = Job::create([
            'recruiter_id' => $this->recruiter->id,
            'title' => 'Temporary Job',
            'department' => 'Eng',
            'description' => 'Desc',
            'experience' => '1 year',
            'application_deadline' => now()->addDays(15),
            'status' => 'open',
        ]);

        $response = $this->actingAs($this->recruiter, 'sanctum')
            ->deleteJson("/api/jobs/{$job->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('jobs', ['id' => $job->id]);
    }

    public function test_candidate_cannot_update_job(): void
    {
        $job = Job::create([
            'recruiter_id' => $this->recruiter->id,
            'title' => 'Junior Dev',
            'department' => 'Eng',
            'description' => 'Desc',
            'experience' => '1 year',
            'application_deadline' => now()->addDays(15),
            'status' => 'open',
        ]);

        $response = $this->actingAs($this->candidate, 'sanctum')
            ->putJson("/api/jobs/{$job->id}", [
                'title' => 'Hacked Title',
            ]);

        $response->assertStatus(403);
    }

    public function test_candidate_cannot_delete_job(): void
    {
        $job = Job::create([
            'recruiter_id' => $this->recruiter->id,
            'title' => 'Important Job',
            'department' => 'Eng',
            'description' => 'Desc',
            'experience' => '1 year',
            'application_deadline' => now()->addDays(15),
            'status' => 'open',
        ]);

        $response = $this->actingAs($this->candidate, 'sanctum')
            ->deleteJson("/api/jobs/{$job->id}");

        $response->assertStatus(403);
        $this->assertDatabaseHas('jobs', ['id' => $job->id]);
    }

    public function test_recruiter_cannot_update_another_recruiters_job(): void
    {
        $recruiterRole = Role::where('name', 'recruiter')->first();
        $anotherRecruiter = User::create([
            'role_id' => $recruiterRole->id,
            'name' => 'Other Recruiter',
            'email' => 'other@recruiter.com',
            'password' => 'secret123',
        ]);

        $job = Job::create([
            'recruiter_id' => $this->recruiter->id,
            'title' => 'Original Job',
            'department' => 'Eng',
            'description' => 'Desc',
            'experience' => '1 year',
            'application_deadline' => now()->addDays(15),
            'status' => 'open',
        ]);

        $response = $this->actingAs($anotherRecruiter, 'sanctum')
            ->putJson("/api/jobs/{$job->id}", [
                'title' => 'Unauthorized Title Change',
            ]);

        $response->assertStatus(403);
    }

    public function test_recruiter_cannot_delete_another_recruiters_job(): void
    {
        $recruiterRole = Role::where('name', 'recruiter')->first();
        $anotherRecruiter = User::create([
            'role_id' => $recruiterRole->id,
            'name' => 'Other Recruiter',
            'email' => 'other2@recruiter.com',
            'password' => 'secret123',
        ]);

        $job = Job::create([
            'recruiter_id' => $this->recruiter->id,
            'title' => 'Protected Job',
            'department' => 'Eng',
            'description' => 'Desc',
            'experience' => '1 year',
            'application_deadline' => now()->addDays(15),
            'status' => 'open',
        ]);

        $response = $this->actingAs($anotherRecruiter, 'sanctum')
            ->deleteJson("/api/jobs/{$job->id}");

        $response->assertStatus(403);
        $this->assertDatabaseHas('jobs', ['id' => $job->id]);
    }
}
