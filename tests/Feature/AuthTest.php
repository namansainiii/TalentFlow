<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::create(['name' => 'admin', 'label' => 'Admin']);
        Role::create(['name' => 'recruiter', 'label' => 'Recruiter']);
        Role::create(['name' => 'candidate', 'label' => 'Candidate']);
    }

    public function test_candidate_can_register_successfully(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'John Candidate',
            'email' => 'john@test.com',
            'password' => 'Secret123!',
            'phone' => '+1234567890',
            'role' => 'candidate',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'token',
                'user' => ['id', 'name', 'email', 'role'],
            ]);

        $this->assertDatabaseHas('users', ['email' => 'john@test.com']);
        $this->assertDatabaseHas('candidates', ['email' => 'john@test.com']);
    }

    public function test_registration_fails_when_password_does_not_meet_complexity(): void
    {
        // Missing symbol, missing uppercase, under 8 chars
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Weak Pass',
            'email' => 'weak@test.com',
            'password' => 'weak12',
            'role' => 'candidate',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);

        // Missing special character
        $response2 = $this->postJson('/api/auth/register', [
            'name' => 'No Special',
            'email' => 'nospecial@test.com',
            'password' => 'Password123',
            'role' => 'candidate',
        ]);

        $response2->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $role = Role::where('name', 'recruiter')->first();
        $user = User::create([
            'role_id' => $role->id,
            'name' => 'Recruiter User',
            'email' => 'recruiter@test.com',
            'password' => 'password123',
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'recruiter@test.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'token',
                'user',
            ]);
    }

    public function test_user_can_login_with_username_instead_of_email(): void
    {
        $role = Role::where('name', 'admin')->first();
        User::create([
            'role_id' => $role->id,
            'name' => 'Namanpreet Kaur',
            'email' => 'admin@test.com',
            'password' => 'Namanpreet!7',
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'Namanpreet Kaur',
            'password' => 'Namanpreet!7',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('user.name', 'Namanpreet Kaur');
    }

    public function test_login_fails_with_invalid_credentials(): void
    {
        $response = $this->postJson('/api/auth/login', [
            'email' => 'wrong@test.com',
            'password' => 'invalid-password',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email'])
            ->assertJsonFragment([
                'email' => ['Username/Password Does not match. Please try again!'],
            ]);
    }

    public function test_authenticated_user_can_access_me_endpoint(): void
    {
        $role = Role::where('name', 'admin')->first();
        $user = User::create([
            'role_id' => $role->id,
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => 'password123',
        ]);

        $token = $user->createToken('test_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/auth/me');

        $response->assertStatus(200)
            ->assertJsonPath('user.email', 'admin@test.com');
    }

    public function test_authenticated_user_can_logout(): void
    {
        $role = Role::where('name', 'candidate')->first();
        $user = User::create([
            'role_id' => $role->id,
            'name' => 'Test Candidate',
            'email' => 'candidate@test.com',
            'password' => 'password123',
        ]);

        $token = $user->createToken('test_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/auth/logout');

        $response->assertStatus(200)
            ->assertJson(['message' => 'Logged out successfully']);

        $this->assertCount(0, $user->tokens);
    }
}
