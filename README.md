# TalentFlow – Recruitment & Resume Management System

A recruitment and hiring management platform built with **Laravel 11+**, **Laravel Sanctum**, **Eloquent ORM**, **Queues & Scheduler**, **Events & Notifications**, and **PDF Resume Parsing Engine**.

---

## 🎯 Overview

TalentFlow allows recruiters to create and manage job openings, screen candidates, automatically extract resume data from PDF uploads, compute deterministic candidate match scores, manage candidates across the hiring pipeline stages, schedule interviews with conflict validation, assign and grade technical tasks, and monitor recruitment analytics via a dedicated dashboard.

---

## 🚀 Key Modules & Features

### 1. Authentication & Role-Based Authorization
- **Laravel Sanctum** token-based authentication.
- Three built-in roles:
  - `Admin`: Full access across jobs, candidates, interviews, and analytics.
  - `Recruiter`: Can create and manage jobs, screen applications, move pipeline stages, schedule interviews, and assign/review technical tasks.
  - `Candidate`: Can register, upload resumes, apply for openings, track application progress, and submit technical tasks.
- Dedicated `RoleMiddleware` and Laravel Policies (`JobPolicy`, `ApplicationPolicy`, `InterviewPolicy`, `TechnicalTaskPolicy`).

### 2. Job Management
- Recruiters can publish and manage job openings.
- Fields: `title`, `department`, `description`, `experience`, `salary_range`, `application_deadline`, `status` (`open`, `closed`, `draft`).
- Many-to-many relationship with skills via `job_skills` distinguishing **mandatory** vs. **bonus** skills.

### 3. Resume Management & Queue Processing
- Candidates can upload PDF resumes (`multipart/form-data`).
- Resumes are stored securely in local/cloud storage.
- Processed via Queue job (`ProcessResumeJob`).
- `ResumeParserService` extracts email, phone, experience years, education, and matches skills against the skills database.

### 4. Candidate Scoring Engine
- Implemented in `CandidateScoringService`:
  - **Mandatory Skills Match (Up to 50 pts)**: Percentage of required mandatory job skills matched.
  - **Bonus Skills Match (Up to 10 pts)**: Matches optional job bonus skills or extra applicant skills.
  - **Experience Match (Up to 25 pts)**: Candidate experience years evaluated against job requirements.
  - **Education Match (Up to 15 pts)**: Weighted scoring for PhD/Master/Bachelor/Diploma.
  - **Total Score (0 – 100)**: Automatically calculated upon application and stored on the application record.

### 5. Hiring Pipeline & Status History
- Full lifecycle stages:
  $$\text{Applied} \longrightarrow \text{Screening} \longrightarrow \text{Shortlisted} \longrightarrow \text{Interview} \longrightarrow \text{Technical Task} \longrightarrow \text{Hired / Rejected}$$
- Every stage change automatically emits `ApplicationStatusChanged` event, creating an immutable audit log entry in `application_status_histories` and sending a notification to the candidate.

### 6. Interview Scheduler with Conflict Validation
- Recruiters can schedule interviews with `scheduled_at`, `interviewer_id`, and `meeting_link`.
- `InterviewValidationService` enforces **Conflict Validation**:
  - Prevents an interviewer from being double-booked within a 45-minute window.
  - Prevents a candidate from having overlapping interviews.
- Status management: `scheduled`, `completed`, `cancelled`, `rescheduled` with interviewer feedback.

### 7. Technical Task Management
- Recruiters assign coding tasks with titles, descriptions, and deadlines.
- Task States: `Pending` $\to$ `In Progress` $\to$ `Submitted` $\to$ `Reviewed` $\to$ `Overdue`.
- Candidate submits repository link, notes, or project files.
- Emits `TaskSubmitted` event that notifies the recruiter.
- Recruiter reviews and grades the submission with a numeric score (0–100) and written feedback.

### 8. Deadline Automation & Scheduler
- Automated command: `php artisan app:check-deadlines` (scheduled hourly).
  - Automatically identifies passed deadlines and marks tasks as `Overdue`.
  - Scans tasks due within the next 24 hours and dispatches `SendTaskDeadlineReminderJob` to queue in-app notifications.

### 9. Dashboard Analytics APIs
- Endpoint `GET /api/dashboard/analytics` returns:
  - `total_jobs` & `active_jobs`
  - `active_candidates` (candidates in active hiring stages)
  - `interviews_this_week`
  - `pipeline_distribution` (count per stage)
  - `average_candidate_score`
  - `pending_tasks` & `total_applications`

---

## 🗄 Database Design & Entities

The system defines independent models and migrations for all 13 entities:
1. `roles`
2. `users`
3. `skills`
4. `jobs`
5. `job_skills` (pivot with `is_mandatory` flag)
6. `candidates`
7. `resumes`
8. `applications`
9. `application_status_histories`
10. `interviews`
11. `technical_tasks`
12. `task_submissions`
13. `notifications`

---

## 🛠 Getting Started

### Prerequisites
- PHP 8.3 or 8.4
- Composer 2.x
- SQLite (default) or MySQL

### Installation

1. **Clone the repository:**
   ```bash
   git clone <repository-url>
   cd Laravel_Hiring_Project
   ```

2. **Install Composer dependencies:**
   ```bash
   composer install
   ```

3. **Configure Environment:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Run Migrations & Seeders:**
   ```bash
   php artisan migrate:fresh --seed
   ```

5. **Start Local Development Server:**
   ```bash
   php artisan serve
   ```
   The API will be accessible at: `http://127.0.0.1:8000/api`

---

## 🔑 Default Seed Credentials

All seed accounts use the password: `password`

| Role | Email | Name |
|---|---|---|
| **Admin** | `admin@talentflow.test` | Sarah Connor |
| **Recruiter** | `recruiter@talentflow.test` | Alex Miller |
| **Recruiter 2** | `jane.recruiter@talentflow.test` | Jane Watson |
| **Candidate 1** | `john.doe@talentflow.test` | John Doe |
| **Candidate 2** | `alice.smith@talentflow.test` | Alice Smith |
| **Candidate 3** | `bob.wilson@talentflow.test` | Bob Wilson |

---

## 🧪 Running Automated Tests

Run the full test suite (25 feature tests, 90+ assertions):

```bash
php artisan test
```

Test coverage includes:
- `AuthTest`: Registration, login, Sanctum token issue, profile retrieval, logout.
- `JobTest`: Public job listings, recruiter CRUD, role-based restriction.
- `ResumeAndApplicationTest`: PDF resume upload, candidate application, scoring calculation, duplicate prevention, status history.
- `InterviewTest`: Scheduling, double-booking conflict validation, completion, cancellation.
- `TechnicalTaskTest`: Assignment, start, submission, recruiter review and grading.
- `DashboardAndDeadlineTest`: Analytics calculation, overdue task updates, 24h reminder queue.

---

## ⏰ Running Deadline Automation Manually

To manually trigger deadline checking and 24-hour reminder dispatch:

```bash
php artisan app:check-deadlines
```

---

## 📬 Postman Collection

Import `TalentFlow_API.postman_collection.json` into Postman.
- Pre-configured environment variables:
  - `base_url`: `http://127.0.0.1:8000/api`
  - `token`: automatically populated when running **Login (Recruiter)** or **Login (Candidate)**.
- Organized folders covering all 38 REST endpoints.

For detailed endpoint documentation and payloads, refer to [API_DOCUMENTATION.md](file:///Applications/MAMP/htdocs/Laravel_Hiring_Project/API_DOCUMENTATION.md).
