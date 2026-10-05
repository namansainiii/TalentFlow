<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Candidate;
use App\Models\Job;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkspaceTest extends TestCase
{
    use RefreshDatabase;

    protected User $recruiter;

    protected User $candidateUser;

    protected Candidate $candidate;

    protected function setUp(): void
    {
        parent::setUp();

        $recruiterRole = Role::create(['name' => 'recruiter', 'label' => 'Recruiter']);
        $candidateRole = Role::create(['name' => 'candidate', 'label' => 'Candidate']);

        $this->recruiter = User::create([
            'role_id' => $recruiterRole->id,
            'name' => 'Recruiter Admin',
            'email' => 'recruiter@workspace.test',
            'password' => 'secret123',
        ]);

        $this->candidateUser = User::create([
            'role_id' => $candidateRole->id,
            'name' => 'Candidate User',
            'email' => 'candidate@workspace.test',
            'password' => 'secret123',
        ]);

        $this->candidate = Candidate::create([
            'user_id' => $this->candidateUser->id,
            'name' => 'Candidate User',
            'email' => 'candidate@workspace.test',
        ]);

        $job = Job::create([
            'recruiter_id' => $this->recruiter->id,
            'title' => 'Laravel Developer',
            'department' => 'Tech',
            'description' => 'Test job description',
            'experience' => '2 years',
            'application_deadline' => now()->addDays(15),
            'status' => 'open',
        ]);

        Application::create([
            'job_id' => $job->id,
            'candidate_id' => $this->candidate->id,
            'status' => 'Applied',
            'skill_score' => 85.0,
        ]);
    }

    public function test_unauthenticated_user_cannot_access_workspace(): void
    {
        $response = $this->getJson('/api/workspace/bootstrap');
        $response->assertStatus(401);
    }

    public function test_recruiter_can_access_workspace_bootstrap(): void
    {
        $response = $this->actingAs($this->recruiter, 'sanctum')
            ->getJson('/api/workspace/bootstrap');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'user' => ['id', 'name', 'email', 'role'],
                'analytics' => [
                    'total_jobs',
                    'active_jobs',
                    'active_candidates',
                    'interviews_this_week',
                    'pipeline_distribution',
                    'average_candidate_score',
                    'total_applications',
                    'pending_tasks',
                ],
                'jobs',
                'applications',
                'interviews',
                'tasks',
                'candidates',
            ]);
    }

    public function test_candidate_can_access_workspace_bootstrap(): void
    {
        $response = $this->actingAs($this->candidateUser, 'sanctum')
            ->getJson('/api/workspace/bootstrap');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'user',
                'analytics',
                'jobs',
                'applications',
                'interviews',
                'tasks',
                'candidates',
            ]);

        // Candidate should only see their own candidate record
        $this->assertCount(1, $response->json('candidates'));
        $this->assertEquals($this->candidate->id, $response->json('candidates.0.id'));
    }
}
