# TalentFlow – Complete API Documentation

Base URL: `http://127.0.0.1:8000/api`

All protected requests require an `Authorization` header:
```
Authorization: Bearer <sanctum_token>
Accept: application/json
```

---

## 1. Authentication Endpoints

### 1.1 Register User
- **Method:** `POST`
- **Path:** `/auth/register`
- **Access:** Public
- **Body:**
```json
{
  "name": "Jane Doe",
  "email": "jane@example.com",
  "password": "password123",
  "phone": "+1-555-0199",
  "role": "candidate" // "candidate" or "recruiter"
}
```
- **Response (201 Created):**
```json
{
  "message": "User registered successfully",
  "token": "1|ABC123xyz...",
  "user": {
    "id": 7,
    "name": "Jane Doe",
    "email": "jane@example.com",
    "role": { "id": 3, "name": "candidate" }
  }
}
```

### 1.2 Login
- **Method:** `POST`
- **Path:** `/auth/login`
- **Access:** Public
- **Body:**
```json
{
  "email": "recruiter@talentflow.test",
  "password": "password"
}
```
- **Response (200 OK):**
```json
{
  "message": "Login successful",
  "token": "2|XYZ789...",
  "user": {
    "id": 2,
    "name": "Chandan Kumar",
    "email": "recruiter@talentflow.test",
    "role": { "id": 2, "name": "recruiter" }
  }
}
```

### 1.3 Get Current User Profile (Me)
- **Method:** `GET`
- **Path:** `/auth/me`
- **Access:** Authenticated

### 1.4 Logout
- **Method:** `POST`
- **Path:** `/auth/logout`
- **Access:** Authenticated

### 1.5 Update Current Candidate Profile
- **Method:** `PUT`
- **Path:** `/profile`
- **Access:** Authenticated (Candidate)
- **Body:**
```json
{
  "name": "Jane Doe",
  "phone": "+1-555-0199",
  "experience_years": 4.5,
  "education": "Master of Science in Software Engineering",
  "skills_summary": "PHP, Laravel, MySQL, REST API, Docker"
}
```

---

## 2. Job Openings Endpoints

### 2.1 List Jobs
- **Method:** `GET`
- **Path:** `/jobs`
- **Access:** Public
- **Query Params:** `status`, `department`, `search`, `page`, `per_page`

### 2.2 View Job Details
- **Method:** `GET`
- **Path:** `/jobs/{id}`
- **Access:** Public

### 2.3 Create Job
- **Method:** `POST`
- **Path:** `/jobs`
- **Access:** Recruiter / Admin
- **Body:**
```json
{
  "title": "Senior Laravel Developer",
  "department": "Engineering",
  "description": "Architect scalable recruitment APIs.",
  "experience": "4-6 years",
  "salary_range": "$90,000 - $120,000",
  "application_deadline": "2026-11-30",
  "mandatory_skills": ["PHP", "Laravel", "MySQL", "REST API"],
  "bonus_skills": ["Docker", "Redis", "Vue.js"]
}
```

### 2.4 Update Job
- **Method:** `PUT`
- **Path:** `/jobs/{id}`
- **Access:** Recruiter / Admin

### 2.5 Delete Job
- **Method:** `DELETE`
- **Path:** `/jobs/{id}`
- **Access:** Recruiter / Admin

---

## 3. Skills Endpoints

### 3.1 List Skills
- **Method:** `GET`
- **Path:** `/skills`
- **Access:** Public

### 3.2 Create Skill
- **Method:** `POST`
- **Path:** `/skills`
- **Access:** Recruiter / Admin
- **Body:** `{"name": "GraphQL"}`

---

## 4. Resume Management Endpoints

### 4.1 Upload PDF Resume
- **Method:** `POST`
- **Path:** `/resumes/upload`
- **Access:** Authenticated
- **Content-Type:** `multipart/form-data`
- **Fields:**
  - `resume`: PDF file (max 10MB)
  - `candidate_id`: (optional)
  - `name`: (optional)
  - `email`: (optional)
- **Response (201 Created):**
```json
{
  "message": "Resume uploaded successfully and queued for processing",
  "resume": {
    "id": 1,
    "file_name": "resume.pdf",
    "status": "uploaded"
  }
}
```

### 4.2 View Resume Details & Parsed Data
- **Method:** `GET`
- **Path:** `/resumes/{id}`
- **Access:** Authenticated

### 4.3 Trigger Processing & Scoring
- **Method:** `POST`
- **Path:** `/resumes/{id}/process`
- **Access:** Authenticated

### 4.4 View or Download Resume PDF
- **Method:** `GET`
- **Path:** `/resumes/{id}/download`
- **Access:** Authenticated (Recruiter/Admin or Owner Candidate)
- **Query Params:**
  - `inline` (optional, boolean: `1` or `0`): Set `1` to stream PDF inline in browser; `0` to download as attachment.
  - `token` (optional, string): Sanctum bearer token supported in query string for direct browser new-tab opening.
- **Response:** PDF binary stream with `Content-Type: application/pdf`.

---

## 5. Applications & Hiring Pipeline Endpoints

### 5.1 Apply for Job Opening
- **Method:** `POST`
- **Path:** `/jobs/{job_id}/apply`
- **Access:** Authenticated / Candidate
- **Content-Type:** `multipart/form-data` or `application/json`
- **Fields / Body:**
  - `resume`: PDF file (optional if resume_id passed)
  - `resume_id`: integer (optional if uploading new file)
  - `notes`: text
- **Behavior:**
  - Enforces single application per candidate per job.
  - Automatically parses resume skills and experience.
  - Runs **Candidate Scoring Engine** (0–100 scale).
  - Logs initial stage `Applied` in `application_status_histories`.

### 5.2 List Applications
- **Method:** `GET`
- **Path:** `/applications`
- **Access:** Authenticated (Candidates see their own; Recruiters see all)
- **Query Params:** `job_id`, `status`, `candidate_id`, `page`

### 5.3 View Application Details
- **Method:** `GET`
- **Path:** `/applications/{id}`
- **Access:** Authenticated

### 5.4 Update Pipeline Stage
- **Method:** `PATCH`
- **Path:** `/applications/{id}/status`
- **Access:** Recruiter / Admin
- **Body:**
```json
{
  "status": "Interview", // Applied, Screening, Shortlisted, Interview, Technical Task, Hired, Rejected
  "comment": "Passed technical assessment. Scheduling final interview."
}
```
- **Behavior:**
  - Updates application status.
  - Logs entry in `application_status_histories`.
  - Dispatches candidate in-app notification.

### 5.5 View Application Status History
- **Method:** `GET`
- **Path:** `/applications/{id}/history`
- **Access:** Authenticated

### 5.6 Recalculate Candidate Score
- **Method:** `POST`
- **Path:** `/applications/{id}/score`
- **Access:** Recruiter / Admin

---

## 6. Interview Scheduler Endpoints

### 6.1 List Scheduled Interviews
- **Method:** `GET`
- **Path:** `/interviews`
- **Access:** Authenticated
- **Query Params:** `status`, `interviewer_id`, `date`

### 6.2 Schedule Interview
- **Method:** `POST`
- **Path:** `/applications/{application_id}/interviews`
- **Access:** Recruiter / Admin
- **Body:**
```json
{
  "interviewer_id": 2,
  "scheduled_at": "2026-10-15 14:00:00",
  "meeting_link": "https://meet.google.com/talentflow-abc"
}
```
- **Conflict Validation:** Rejects with 422 if interviewer or candidate already has an interview scheduled within +/- 45 minutes of the requested time.

### 6.3 View Interview Details
- **Method:** `GET`
- **Path:** `/interviews/{id}`
- **Access:** Authenticated

### 6.4 Update / Reschedule Interview
- **Method:** `PUT`
- **Path:** `/interviews/{id}`
- **Access:** Recruiter / Admin
- **Body:**
```json
{
  "scheduled_at": "2026-10-16 15:30:00",
  "meeting_link": "https://meet.google.com/updated-link",
  "status": "rescheduled",
  "interviewer_id": 2,
  "feedback": "Rescheduled per candidate request."
}
```
- **Response (200 OK):**
```json
{
  "message": "Interview updated successfully",
  "interview": {
    "id": 1,
    "application_id": 1,
    "interviewer": {
      "id": 2,
      "name": "Chandan Kumar"
    },
    "scheduled_at": "2026-10-16T15:30:00.000000Z",
    "meeting_link": "https://meet.google.com/updated-link",
    "status": "rescheduled",
    "feedback": "Rescheduled per candidate request."
  }
}
```

### 6.5 Complete Interview
- **Method:** `PATCH`
- **Path:** `/interviews/{id}/complete`
- **Access:** Recruiter / Admin
- **Body:**
```json
{
  "feedback": "Strong architecture fundamentals and excellent culture fit."
}
```

### 6.6 Cancel Interview
- **Method:** `PATCH`
- **Path:** `/interviews/{id}/cancel`
- **Access:** Recruiter / Admin
- **Body:**
```json
{
  "reason": "Candidate requested reschedule."
}
```

---

## 7. Technical Task Management Endpoints

### 7.1 List Technical Tasks
- **Method:** `GET`
- **Path:** `/technical-tasks`
- **Access:** Authenticated
- **Query Params:** `status`, `application_id`

### 7.2 Assign Coding Task
- **Method:** `POST`
- **Path:** `/applications/{application_id}/technical-tasks`
- **Access:** Recruiter / Admin
- **Body:**
```json
{
  "title": "Build a Multi-Tenant REST API Module",
  "description": "Develop a REST API with Sanctum authentication, role scoping, and unit tests.",
  "deadline": "2026-10-20 18:00:00"
}
```

### 7.3 View Task Details
- **Method:** `GET`
- **Path:** `/technical-tasks/{id}`
- **Access:** Authenticated

### 7.4 Start Task (In Progress)
- **Method:** `PATCH`
- **Path:** `/technical-tasks/{id}/start`
- **Access:** Candidate

### 7.5 Submit Task
- **Method:** `POST`
- **Path:** `/technical-tasks/{id}/submit`
- **Access:** Candidate
- **Body:**
```json
{
  "repository_url": "https://github.com/candidate/talentflow-solution",
  "notes": "Completed features and feature tests."
}
```
- **Behavior:** Changes task status to `Submitted`, saves submission, dispatches notification to the recruiter.

### 7.6 Review & Grade Task
- **Method:** `POST`
- **Path:** `/technical-tasks/{id}/review`
- **Access:** Recruiter / Admin
- **Body:**
```json
{
  "score": 92,
  "feedback": "Very clean repository, clear code structure, and good test coverage.",
  "status": "Reviewed"
}
```

### 7.7 Download Task Attachment
- **Method:** `GET`
- **Path:** `/technical-tasks/{id}/attachments/{index}`
- **Access:** Authenticated
- **Behavior:** Downloads or streams project specification/reference files attached by the recruiter.

---

## 8. Candidate Directory Endpoints

### 8.1 List Candidates
- **Method:** `GET`
- **Path:** `/candidates`
- **Access:** Recruiter / Admin
- **Query Params:** `search`, `min_experience`, `page`

### 8.2 Get Candidate Profile
- **Method:** `GET`
- **Path:** `/candidates/{id}`
- **Access:** Recruiter / Admin

---

## 9. Dashboard Analytics Endpoint

### 9.1 Get Recruitment Metrics
- **Method:** `GET`
- **Path:** `/dashboard/analytics`
- **Access:** Recruiter / Admin
- **Response (200 OK):**
```json
{
  "analytics": {
    "total_jobs": 3,
    "active_jobs": 3,
    "active_candidates": 4,
    "interviews_this_week": 1,
    "pipeline_distribution": {
      "Applied": 1,
      "Screening": 0,
      "Shortlisted": 1,
      "Interview": 1,
      "Technical Task": 1,
      "Hired": 1,
      "Rejected": 0
    },
    "average_candidate_score": 85.2,
    "total_applications": 5,
    "pending_tasks": 0
  }
}
```

---

## 10. In-App Notifications Endpoints

### 10.1 List Current User Notifications
- **Method:** `GET`
- **Path:** `/notifications`
- **Access:** Authenticated

### 10.2 Mark Notification as Read
- **Method:** `PATCH`
- **Path:** `/notifications/{id}/read`
- **Access:** Authenticated

### 10.3 Mark All Notifications as Read
- **Method:** `POST`
- **Path:** `/notifications/read-all`
- **Access:** Authenticated

---

## 11. Workspace Bootstrap Endpoint

### 11.1 Bootstrap Workspace
- **Method:** `GET`
- **Path:** `/workspace/bootstrap`
- **Access:** Authenticated
- **Behavior:** High-speed unified bootstrap endpoint that pre-loads role-scoped jobs, candidates, applications, interviews, tasks, and notifications in a single request for the SPA workspace.

---

## 12. Recruiter Directory Endpoints

### 12.1 List Recruiters
- **Method:** `GET`
- **Path:** `/recruiters`
- **Access:** Admin
- **Behavior:** Lists all hiring managers, recruiters, and administrators along with their posting and interview statistics.

### 12.2 View Recruiter Profile
- **Method:** `GET`
- **Path:** `/recruiters/{id}`
- **Access:** Admin
- **Behavior:** Returns detailed recruiter activity, including posted jobs and conducted interviews.

