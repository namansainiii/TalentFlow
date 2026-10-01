<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\ApplicationStatusHistory;
use App\Models\Candidate;
use App\Models\Interview;
use App\Models\Job;
use App\Models\Resume;
use App\Models\Role;
use App\Models\Skill;
use App\Models\TaskSubmission;
use App\Models\TechnicalTask;
use App\Models\User;
use App\Notifications\ApplicationStatusNotification;
use App\Notifications\TaskSubmittedNotification;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with complete TalentFlow test data.
     */
    public function run(): void
    {
        // Ensure storage directory exists
        Storage::disk('local')->makeDirectory('resumes');
        Storage::disk('local')->makeDirectory('task_submissions');

        // 1. Roles
        $adminRole = Role::firstOrCreate(['name' => 'admin'], ['label' => 'Administrator']);
        $recruiterRole = Role::firstOrCreate(['name' => 'recruiter'], ['label' => 'Recruiter']);
        $candidateRole = Role::firstOrCreate(['name' => 'candidate'], ['label' => 'Candidate']);

        // 2. Users
        $password = Hash::make('password');

        $admin = User::firstOrCreate(
            ['email' => 'admin@talentflow.test'],
            [
                'role_id' => $adminRole->id,
                'name' => 'Sarah Connor (Admin)',
                'phone' => '+1-555-0100',
                'password' => $password,
            ]
        );

        $recruiter = User::firstOrCreate(
            ['email' => 'recruiter@talentflow.test'],
            [
                'role_id' => $recruiterRole->id,
                'name' => 'Alex Miller (Lead Recruiter)',
                'phone' => '+1-555-0101',
                'password' => $password,
            ]
        );

        $recruiter2 = User::firstOrCreate(
            ['email' => 'jane.recruiter@talentflow.test'],
            [
                'role_id' => $recruiterRole->id,
                'name' => 'Jane Watson (Tech Recruiter)',
                'phone' => '+1-555-0102',
                'password' => $password,
            ]
        );

        $candidateUser1 = User::firstOrCreate(
            ['email' => 'john.doe@talentflow.test'],
            [
                'role_id' => $candidateRole->id,
                'name' => 'John Doe',
                'phone' => '+1-555-0201',
                'password' => $password,
            ]
        );

        $candidateUser2 = User::firstOrCreate(
            ['email' => 'alice.smith@talentflow.test'],
            [
                'role_id' => $candidateRole->id,
                'name' => 'Alice Smith',
                'phone' => '+1-555-0202',
                'password' => $password,
            ]
        );

        $candidateUser3 = User::firstOrCreate(
            ['email' => 'bob.wilson@talentflow.test'],
            [
                'role_id' => $candidateRole->id,
                'name' => 'Bob Wilson',
                'phone' => '+1-555-0203',
                'password' => $password,
            ]
        );

        // 3. Skills
        $skillNames = [
            'PHP', 'Laravel', 'MySQL', 'Vue.js', 'React',
            'Docker', 'AWS', 'Git', 'REST API', 'TailwindCSS',
            'TypeScript', 'Redis', 'Python',
        ];

        $skills = [];
        foreach ($skillNames as $name) {
            $skills[$name] = Skill::firstOrCreate(['name' => $name]);
        }

        // 4. Jobs
        $job1 = Job::create([
            'recruiter_id' => $recruiter->id,
            'title' => 'Senior Full-Stack Laravel Developer',
            'department' => 'Engineering',
            'description' => 'We are seeking an experienced Full-Stack Laravel developer to architect and build our scalable cloud recruitment solutions.',
            'experience' => '4-6 years',
            'salary_range' => '$85,000 - $115,000',
            'application_deadline' => Carbon::now()->addDays(30),
            'status' => 'open',
        ]);
        $job1->skills()->attach([
            $skills['PHP']->id => ['is_mandatory' => true],
            $skills['Laravel']->id => ['is_mandatory' => true],
            $skills['MySQL']->id => ['is_mandatory' => true],
            $skills['REST API']->id => ['is_mandatory' => true],
            $skills['Vue.js']->id => ['is_mandatory' => false],
            $skills['Docker']->id => ['is_mandatory' => false],
            $skills['Redis']->id => ['is_mandatory' => false],
        ]);

        $job2 = Job::create([
            'recruiter_id' => $recruiter->id,
            'title' => 'Frontend Vue.js Specialist',
            'department' => 'Frontend',
            'description' => 'Join our product team to craft fluid, accessible, and fast web experiences using Vue 3 and modern CSS.',
            'experience' => '3+ years',
            'salary_range' => '$75,000 - $95,000',
            'application_deadline' => Carbon::now()->addDays(20),
            'status' => 'open',
        ]);
        $job2->skills()->attach([
            $skills['Vue.js']->id => ['is_mandatory' => true],
            $skills['TypeScript']->id => ['is_mandatory' => true],
            $skills['TailwindCSS']->id => ['is_mandatory' => true],
            $skills['Git']->id => ['is_mandatory' => true],
            $skills['REST API']->id => ['is_mandatory' => false],
        ]);

        $job3 = Job::create([
            'recruiter_id' => $recruiter2->id,
            'title' => 'Cloud & DevOps Engineer',
            'department' => 'Infrastructure',
            'description' => 'Help automate our CI/CD pipelines, container orchestration, and multi-region AWS cloud deployments.',
            'experience' => '5+ years',
            'salary_range' => '$105,000 - $135,000',
            'application_deadline' => Carbon::now()->addDays(45),
            'status' => 'open',
        ]);
        $job3->skills()->attach([
            $skills['Docker']->id => ['is_mandatory' => true],
            $skills['AWS']->id => ['is_mandatory' => true],
            $skills['Git']->id => ['is_mandatory' => true],
            $skills['Python']->id => ['is_mandatory' => false],
            $skills['Redis']->id => ['is_mandatory' => false],
        ]);

        // 5. Candidates & Resumes
        // Candidate 1: John Doe
        $candidate1 = Candidate::create([
            'user_id' => $candidateUser1->id,
            'name' => 'John Doe',
            'email' => 'john.doe@talentflow.test',
            'phone' => '+1-555-0201',
            'experience_years' => 5.0,
            'education' => 'Bachelor of Computer Science',
            'skills_summary' => 'PHP, Laravel, MySQL, REST API, Docker, Vue.js',
        ]);

        $samplePdfPath1 = 'resumes/sample_john_doe.pdf';
        Storage::disk('local')->put($samplePdfPath1, '%PDF-1.4 sample resume content for John Doe');

        $resume1 = Resume::create([
            'candidate_id' => $candidate1->id,
            'file_path' => $samplePdfPath1,
            'file_name' => 'John_Doe_Resume.pdf',
            'file_size' => 102400,
            'parsed_text' => 'John Doe. 5 years experience with PHP, Laravel, MySQL, REST API, Docker, Vue.js. Bachelor degree.',
            'parsed_data' => [
                'email' => 'john.doe@talentflow.test',
                'phone' => '+1-555-0201',
                'experience_years' => 5.0,
                'education' => 'Bachelor Of Computer Science',
                'skills' => ['PHP', 'Laravel', 'MySQL', 'REST API', 'Docker', 'Vue.js'],
            ],
            'status' => 'completed',
        ]);

        // Candidate 2: Alice Smith
        $candidate2 = Candidate::create([
            'user_id' => $candidateUser2->id,
            'name' => 'Alice Smith',
            'email' => 'alice.smith@talentflow.test',
            'phone' => '+1-555-0202',
            'experience_years' => 3.5,
            'education' => 'Master of Information Technology',
            'skills_summary' => 'Vue.js, TypeScript, TailwindCSS, Git, REST API',
        ]);

        $samplePdfPath2 = 'resumes/sample_alice_smith.pdf';
        Storage::disk('local')->put($samplePdfPath2, '%PDF-1.4 sample resume content for Alice Smith');

        $resume2 = Resume::create([
            'candidate_id' => $candidate2->id,
            'file_path' => $samplePdfPath2,
            'file_name' => 'Alice_Smith_Frontend_Resume.pdf',
            'file_size' => 95400,
            'parsed_text' => 'Alice Smith. 3.5 years experience in Vue.js, TypeScript, TailwindCSS, Git, REST API. Master degree.',
            'parsed_data' => [
                'email' => 'alice.smith@talentflow.test',
                'phone' => '+1-555-0202',
                'experience_years' => 3.5,
                'education' => 'Master Of Information Technology',
                'skills' => ['Vue.js', 'TypeScript', 'TailwindCSS', 'Git', 'REST API'],
            ],
            'status' => 'completed',
        ]);

        // Candidate 3: Bob Wilson
        $candidate3 = Candidate::create([
            'user_id' => $candidateUser3->id,
            'name' => 'Bob Wilson',
            'email' => 'bob.wilson@talentflow.test',
            'phone' => '+1-555-0203',
            'experience_years' => 4.0,
            'education' => 'Bachelor of Software Engineering',
            'skills_summary' => 'PHP, Laravel, MySQL, REST API, Git, Redis',
        ]);

        $samplePdfPath3 = 'resumes/sample_bob_wilson.pdf';
        Storage::disk('local')->put($samplePdfPath3, '%PDF-1.4 sample resume content for Bob Wilson');

        $resume3 = Resume::create([
            'candidate_id' => $candidate3->id,
            'file_path' => $samplePdfPath3,
            'file_name' => 'Bob_Wilson_Resume.pdf',
            'file_size' => 112000,
            'parsed_text' => 'Bob Wilson. 4 years experience with PHP, Laravel, MySQL, REST API, Git. Bachelor degree.',
            'parsed_data' => [
                'email' => 'bob.wilson@talentflow.test',
                'phone' => '+1-555-0203',
                'experience_years' => 4.0,
                'education' => 'Bachelor Of Software Engineering',
                'skills' => ['PHP', 'Laravel', 'MySQL', 'REST API', 'Git', 'Redis'],
            ],
            'status' => 'completed',
        ]);

        // Candidate 4 (unregistered applicant)
        $candidate4 = Candidate::create([
            'user_id' => null,
            'name' => 'David Miller',
            'email' => 'david.m@example.com',
            'phone' => '+1-555-0204',
            'experience_years' => 2.0,
            'education' => 'Associate Degree in Web Development',
            'skills_summary' => 'PHP, MySQL, Git',
        ]);

        // Candidate 5 (unregistered applicant)
        $candidate5 = Candidate::create([
            'user_id' => null,
            'name' => 'Emma Watson',
            'email' => 'emma.w@example.com',
            'phone' => '+1-555-0205',
            'experience_years' => 6.0,
            'education' => 'Bachelor of Computer Science',
            'skills_summary' => 'Docker, AWS, Git, Python, Redis',
        ]);

        // 6. Applications across Pipeline Stages
        // Application 1: John Doe -> Job 1 (Shortlisted)
        $app1 = Application::create([
            'job_id' => $job1->id,
            'candidate_id' => $candidate1->id,
            'resume_id' => $resume1->id,
            'status' => Application::STATUS_SHORTLISTED,
            'skill_score' => 94.50,
            'notes' => 'Strong experience with Laravel ecosystem and modern API development.',
        ]);
        ApplicationStatusHistory::create([
            'application_id' => $app1->id,
            'from_status' => null,
            'to_status' => Application::STATUS_APPLIED,
            'changed_by_user_id' => null,
            'comment' => 'Applied online via candidate portal',
            'created_at' => Carbon::now()->subDays(5),
        ]);
        ApplicationStatusHistory::create([
            'application_id' => $app1->id,
            'from_status' => Application::STATUS_APPLIED,
            'to_status' => Application::STATUS_SCREENING,
            'changed_by_user_id' => $recruiter->id,
            'comment' => 'Resume parsed and passed automated screening threshold.',
            'created_at' => Carbon::now()->subDays(3),
        ]);
        ApplicationStatusHistory::create([
            'application_id' => $app1->id,
            'from_status' => Application::STATUS_SCREENING,
            'to_status' => Application::STATUS_SHORTLISTED,
            'changed_by_user_id' => $recruiter->id,
            'comment' => 'Candidate shortlisted for initial interview.',
            'created_at' => Carbon::now()->subDays(1),
        ]);

        // Application 2: Alice Smith -> Job 2 (Interview Stage)
        $app2 = Application::create([
            'job_id' => $job2->id,
            'candidate_id' => $candidate2->id,
            'resume_id' => $resume2->id,
            'status' => Application::STATUS_INTERVIEW,
            'skill_score' => 96.00,
            'notes' => 'Impressive portfolio of Vue 3 components and design systems.',
        ]);
        ApplicationStatusHistory::create([
            'application_id' => $app2->id,
            'from_status' => null,
            'to_status' => Application::STATUS_APPLIED,
            'created_at' => Carbon::now()->subDays(4),
        ]);
        ApplicationStatusHistory::create([
            'application_id' => $app2->id,
            'from_status' => Application::STATUS_APPLIED,
            'to_status' => Application::STATUS_SCREENING,
            'changed_by_user_id' => $recruiter->id,
            'created_at' => Carbon::now()->subDays(3),
        ]);
        ApplicationStatusHistory::create([
            'application_id' => $app2->id,
            'from_status' => Application::STATUS_SCREENING,
            'to_status' => Application::STATUS_SHORTLISTED,
            'changed_by_user_id' => $recruiter->id,
            'created_at' => Carbon::now()->subDays(2),
        ]);
        ApplicationStatusHistory::create([
            'application_id' => $app2->id,
            'from_status' => Application::STATUS_SHORTLISTED,
            'to_status' => Application::STATUS_INTERVIEW,
            'changed_by_user_id' => $recruiter->id,
            'comment' => 'Interview scheduled with hiring manager.',
            'created_at' => Carbon::now()->subDays(1),
        ]);

        // Application 3: Bob Wilson -> Job 1 (Technical Task Stage)
        $app3 = Application::create([
            'job_id' => $job1->id,
            'candidate_id' => $candidate3->id,
            'resume_id' => $resume3->id,
            'status' => Application::STATUS_TECHNICAL_TASK,
            'skill_score' => 88.00,
            'notes' => 'Solid backend knowledge, assigned technical evaluation.',
        ]);
        ApplicationStatusHistory::create([
            'application_id' => $app3->id,
            'from_status' => null,
            'to_status' => Application::STATUS_APPLIED,
            'created_at' => Carbon::now()->subDays(6),
        ]);
        ApplicationStatusHistory::create([
            'application_id' => $app3->id,
            'from_status' => Application::STATUS_APPLIED,
            'to_status' => Application::STATUS_SHORTLISTED,
            'changed_by_user_id' => $recruiter->id,
            'created_at' => Carbon::now()->subDays(4),
        ]);
        ApplicationStatusHistory::create([
            'application_id' => $app3->id,
            'from_status' => Application::STATUS_SHORTLISTED,
            'to_status' => Application::STATUS_INTERVIEW,
            'changed_by_user_id' => $recruiter->id,
            'created_at' => Carbon::now()->subDays(3),
        ]);
        ApplicationStatusHistory::create([
            'application_id' => $app3->id,
            'from_status' => Application::STATUS_INTERVIEW,
            'to_status' => Application::STATUS_TECHNICAL_TASK,
            'changed_by_user_id' => $recruiter->id,
            'comment' => 'Assigned coding assessment.',
            'created_at' => Carbon::now()->subDays(2),
        ]);

        // Application 4: David Miller -> Job 1 (Applied)
        $app4 = Application::create([
            'job_id' => $job1->id,
            'candidate_id' => $candidate4->id,
            'status' => Application::STATUS_APPLIED,
            'skill_score' => 52.00,
            'notes' => 'Junior candidate with basic skills.',
        ]);
        ApplicationStatusHistory::create([
            'application_id' => $app4->id,
            'from_status' => null,
            'to_status' => Application::STATUS_APPLIED,
            'created_at' => Carbon::now()->subHours(10),
        ]);

        // Application 5: Emma Watson -> Job 3 (Hired)
        $app5 = Application::create([
            'job_id' => $job3->id,
            'candidate_id' => $candidate5->id,
            'status' => Application::STATUS_HIRED,
            'skill_score' => 97.50,
            'notes' => 'Exceptional technical task submission and interview performance.',
        ]);
        ApplicationStatusHistory::create([
            'application_id' => $app5->id,
            'from_status' => null,
            'to_status' => Application::STATUS_APPLIED,
            'created_at' => Carbon::now()->subDays(10),
        ]);
        ApplicationStatusHistory::create([
            'application_id' => $app5->id,
            'from_status' => Application::STATUS_APPLIED,
            'to_status' => Application::STATUS_HIRED,
            'changed_by_user_id' => $recruiter2->id,
            'comment' => 'Candidate accepted offer letter.',
            'created_at' => Carbon::now()->subDays(1),
        ]);

        // 7. Scheduled Interviews
        // Interview for Alice Smith
        Interview::create([
            'application_id' => $app2->id,
            'interviewer_id' => $recruiter->id,
            'scheduled_at' => Carbon::tomorrow()->setTime(14, 0),
            'meeting_link' => 'https://meet.google.com/abc-tfxy-def',
            'status' => Interview::STATUS_SCHEDULED,
            'feedback' => null,
        ]);

        // Completed Interview for Bob Wilson
        Interview::create([
            'application_id' => $app3->id,
            'interviewer_id' => $recruiter->id,
            'scheduled_at' => Carbon::now()->subDays(2)->setTime(11, 0),
            'meeting_link' => 'https://meet.google.com/bob-tech-interview',
            'status' => Interview::STATUS_COMPLETED,
            'feedback' => 'Good conceptual knowledge of PHP and REST design principles. Recommended for technical coding task.',
        ]);

        // 8. Technical Tasks & Submissions
        $task = TechnicalTask::create([
            'application_id' => $app3->id,
            'assigned_by_user_id' => $recruiter->id,
            'title' => 'Build a Multi-Tenant REST API Module',
            'description' => 'Develop a clean REST API in Laravel with Sanctum authentication, role scoping, and unit tests.',
            'deadline' => Carbon::now()->addDays(3),
            'status' => TechnicalTask::STATUS_SUBMITTED,
        ]);

        $submission = TaskSubmission::create([
            'technical_task_id' => $task->id,
            'repository_url' => 'https://github.com/bobwilson/laravel-api-task',
            'notes' => 'Implemented multi-tenant scoping and wrote 12 feature tests covering authentication and data isolation.',
            'file_path' => null,
            'submitted_at' => Carbon::now()->subHours(4),
            'score' => 90,
            'feedback' => 'Very clean architecture and good test coverage.',
        ]);

        // 9. In-App Notifications
        $candidateUser2->notify(new ApplicationStatusNotification($app2, Application::STATUS_INTERVIEW));
        $candidateUser1->notify(new ApplicationStatusNotification($app1, Application::STATUS_SHORTLISTED));
        $recruiter->notify(new TaskSubmittedNotification($task, $submission));
    }
}
