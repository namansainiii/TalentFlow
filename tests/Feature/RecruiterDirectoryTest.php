<?php

namespace Tests\Feature;

use App\Models\Candidate;
use App\Models\Job;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecruiterDirectoryTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $recruiter;

    protected User $candidateUser;

    protected Candidate $candidate;

    protected function setUp(): void
    {
        parent::setUp();

        $adminRole = Role::create(['name' => 'admin', 'label' => 'Administrator']);
        $recruiterRole = Role::create(['name' => 'recruiter', 'label' => 'Recruiter']);
        $candidateRole = Role::create(['name' => 'candidate', 'label' => 'Candidate']);

        $this->admin = User::create([
            'role_id' => $adminRole->id,
            'name' => 'Sarah Connor (Admin)',
            'email' => 'admin@test.com',
            'phone' => '+1-555-0100',
            'password' => 'secret123',
        ]);

        $this->recruiter = User::create([
            'role_id' => $recruiterRole->id,
            'name' => 'Alex Miller (Recruiter)',
            'email' => 'alex@test.com',
            'phone' => '+1-555-0101',
            'password' => 'secret123',
        ]);

        $this->candidateUser = User::create([
            'role_id' => $candidateRole->id,
            'name' => 'John Candidate',
            'email' => 'john@test.com',
            'phone' => '+1-555-0102',
            'password' => 'secret123',
        ]);

        $this->candidate = Candidate::create([
            'user_id' => $this->candidateUser->id,
            'name' => 'John Candidate',
            'email' => 'john@test.com',
            'phone' => '+1-555-0102',
            'experience_years' => 4,
            'education' => 'B.S. in Computer Science',
            'skills_summary' => 'PHP, Laravel, MySQL',
        ]);

        Job::create([
            'recruiter_id' => $this->recruiter->id,
            'title' => 'Senior Backend Engineer',
            'department' => 'Engineering',
            'description' => 'Build high-scale systems',
            'experience' => '4 years',
            'application_deadline' => now()->addDays(15),
            'status' => 'open',
        ]);
    }

    public function test_admin_can_view_recruiters_list(): void
    {
        $response = $this->actingAs($this->admin, 'sanctum')
            ->getJson('/api/recruiters');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'email',
                        'phone',
                        'role',
                        'posted_jobs_count',
                        'conducted_interviews_count',
                        'assigned_tasks_count',
                    ],
                ],
            ]);

        $this->assertGreaterThanOrEqual(2, count($response->json('data')));
    }

    public function test_admin_can_view_single_recruiter_details(): void
    {
        $response = $this->actingAs($this->admin, 'sanctum')
            ->getJson("/api/recruiters/{$this->recruiter->id}");

        $response->assertStatus(200)
            ->assertJsonPath('recruiter.name', 'Alex Miller (Recruiter)')
            ->assertJsonPath('recruiter.posted_jobs_count', 1);
    }

    public function test_candidate_cannot_access_recruiter_list(): void
    {
        $response = $this->actingAs($this->candidateUser, 'sanctum')
            ->getJson('/api/recruiters');

        $response->assertStatus(403);
    }

    public function test_admin_can_view_candidate_profile(): void
    {
        $response = $this->actingAs($this->admin, 'sanctum')
            ->getJson("/api/candidates/{$this->candidate->id}");

        $response->assertStatus(200)
            ->assertJsonPath('candidate.name', 'John Candidate')
            ->assertJsonPath('candidate.experience_years', 4);
    }
}
