<?php

namespace Database\Seeders;

use App\Models\Candidate;
use App\Models\Role;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with roles and the 3 quick-fill login accounts.
     */
    public function run(): void
    {
        // 1. Ensure storage directory exists
        Storage::disk('local')->makeDirectory('resumes');
        Storage::disk('local')->makeDirectory('task_submissions');

        // 2. Roles
        $adminRole = Role::firstOrCreate(['name' => 'admin'], ['label' => 'Administrator']);
        $recruiterRole = Role::firstOrCreate(['name' => 'recruiter'], ['label' => 'Recruiter']);
        $candidateRole = Role::firstOrCreate(['name' => 'candidate'], ['label' => 'Candidate']);

        // 3. The 3 Quick-Fill Login Accounts
        $password = Hash::make('Namanpreet!7');

        // Admin Account
        User::updateOrCreate(
            ['email' => 'admin@talentflow.test'],
            [
                'role_id' => $adminRole->id,
                'name' => 'Namanpreet Kaur',
                'phone' => '+1-555-0100',
                'password' => $password,
            ]
        );

        // Recruiter Account
        User::updateOrCreate(
            ['email' => 'recruiter@talentflow.test'],
            [
                'role_id' => $recruiterRole->id,
                'name' => 'Chandan Kumar',
                'phone' => '+1-555-0101',
                'password' => $password,
            ]
        );

        // Candidate Account
        $candidateUser = User::updateOrCreate(
            ['email' => 'candidate@talentflow.test'],
            [
                'role_id' => $candidateRole->id,
                'name' => 'Parampreet Singh',
                'phone' => '+1-555-0201',
                'password' => $password,
            ]
        );

        // Candidate Profile for Parampreet Singh
        Candidate::updateOrCreate(
            ['user_id' => $candidateUser->id],
            [
                'name' => 'Parampreet Singh',
                'email' => $candidateUser->email,
                'phone' => '+1-555-0201',
                'experience_years' => 5.0,
                'education' => 'Bachelor of Computer Science',
                'skills_summary' => 'PHP, Laravel, MySQL, REST API, Docker, Vue.js',
            ]
        );

        // 4. Standard Skill Dictionary
        $skillNames = [
            'PHP', 'Laravel', 'MySQL', 'Vue.js', 'React',
            'Docker', 'AWS', 'Git', 'REST API', 'TailwindCSS',
            'TypeScript', 'Redis', 'Python',
        ];

        foreach ($skillNames as $name) {
            Skill::firstOrCreate(['name' => $name]);
        }
    }
}
