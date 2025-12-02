# Guide - UoS Faculty Recruitment System


## Checklist

### I. Public-Facing / Applicant Portal Module

#### 1. Home Page
- **URL**: http://localhost:8000/public_facing_applicant_portal_module/index.html
- **Cases**:
  - Check navigation links
  - Click "View Open Positions" button
  - Verify all navigation items work

#### 2. Job Vacancies Listing Page
- **URL**: http://localhost:8000/public_facing_applicant_portal_module/vacancies.html
- **Cases**:
  - Test all filters: College, Rank, Track (Regular/Emirati), Type (Teaching/Clinical/Research)
  - Verify jobs filter correctly when filters are applied
  - Click "Apply Now" buttons - should navigate to application form
  - Test "No results" message when filters don't match

#### 3. Online Application Form
- **URL**: http://localhost:8000/public_facing_applicant_portal_module/apply.html
- **Cases**:
  - Navigate through all 6 steps using Next/Previous buttons
  - Test form validation on each step
  - Fill out Step 1: Personal Information
  - Fill out Step 2: Academic Qualifications
  - Add publications in Step 3
  - Test UAE residency status dropdown (should show/hide visa fields)
  - Upload files in Step 5 (test file size validation)
  - Check all declarations in Step 6
  - Submit form and verify redirect to dashboard

#### 4. Applicant Dashboard
- **URL**: http://localhost:8000/public_facing_applicant_portal_module/applicant-dashboard.html
- **Cases**:
  - View application status and timeline
  - Switch between Notifications and Messages tabs
  - Click on notification/message items to mark as read
  - Verify unread badge counts update
  - Test action links (Edit Application, Withdraw Application)

### II. Internal Admin Portal Module

#### 5. Talent Request Submission Form
- **URL**: http://localhost:8000/University_Recruitment_System/talent_request_submission_form.html
- **Cases**:
  - Fill out all required fields
  - Upload advertisement document
  - Test file validation (size, type)
  - Submit form and verify preview

#### 6. Recruitment Dashboard (HR View)
- **URL**: http://localhost:8000/University_Recruitment_System/recruitment_dashboard_hr_view.html
- **Cases**:
  - View quick stats
  - Check pipeline visualization
  - Test search functionality
  - Filter by department
  - View position details

#### 7. Application Review Interface
- **URL**: http://localhost:8000/University_Recruitment_System/application_review_interface.html
- **Cases**:
  - Search for applicants
  - Filter by stage
  - Click on applicant to view profile
  - Fill out assessment form (Technical, Behavioral, Values)
  - Set interview rating
  - Test Recommend/Reject buttons

#### 8. Interview Scheduling & Feedback
- **URL**: http://localhost:8000/University_Recruitment_System/interview_scheduling_feedback.html
- **Cases**:
  - Enter candidate name
  - Click calendar slots to propose interview times
  - Add panel members
  - Submit feedback for panel members
  - View aggregated panel recommendations
  - Test "Send Invites" button

### III. Onboarding Module

#### 9. Pre-Joining Checklist Portal
- **URL**: http://localhost:8000/University_Recruitment_System/pre_joining_checklist.html
- **Cases**:
  - View overall progress
  - Upload documents for each task
  - Test file upload validation
  - Check off completed tasks
  - Verify progress bar updates
  - Fill out bank details form
  - Fill out emergency contact form
  - Test filter buttons (All, Pending, Completed)

#### 10. HR Onboarding Management Page
- **URL**: http://localhost:8000/University_Recruitment_System/hr_onboarding_management.html
- **Cases**:
  - View statistics dashboard
  - Search for new hires
  - Filter by status and department
  - Click "View" to see detailed checklist
  - Click "Assign" to assign HR tasks
  - Test task assignment modal
  - Test export report button

#### 11. New Faculty Welcome / Induction Page
- **URL**: http://localhost:8000/University_Recruitment_System/new_faculty_welcome.html
- **Cases**:
  - View countdown timer (updates every second)
  - Check onboarding journey timeline
  - Click on resource cards
  - View contact information
  - Test quick action buttons
  - Click campus tour video placeholder

## Quick Access Links

### Public Portal
- Home: http://localhost:8000/public_facing_applicant_portal_module/index.html
- Vacancies: http://localhost:8000/public_facing_applicant_portal_module/vacancies.html
- Apply: http://localhost:8000/Public-Facing%20Applicant%20Portal%20module/apply.html
- Dashboard: http://localhost:8000/Public-Facing%20Applicant%20Portal%20module/applicant-dashboard.html

### Admin Portal
- Talent Request: http://localhost:8000/University_Recruitment_System/talent_request_submission_form.html
- HR Dashboard: http://localhost:8000/University_Recruitment_System/recruitment_dashboard_hr_view.html
- Application Review: http://localhost:8000/University_Recruitment_System/application_review_interface.html
- Interview Scheduling: http://localhost:8000/University_Recruitment_System/interview_scheduling_feedback.html

### Onboarding
- Pre-Joining Checklist: http://localhost:8000/University_Recruitment_System/pre_joining_checklist.html
- HR Management: http://localhost:8000/University_Recruitment_System/hr_onboarding_management.html
- Welcome Page: http://localhost:8000/University_Recruitment_System/new_faculty_welcome.html

## Notes
- All forms use client-side validation (no backend)
- File uploads are simulated (files are not actually saved)
- All data is mock data for demonstration purposes

