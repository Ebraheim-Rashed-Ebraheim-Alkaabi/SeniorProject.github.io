-- University of Sharjah Faculty Recruitment System Database
-- Compatible with MySQL/MariaDB (XAMPP)
-- Created: 2025

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- Create database
CREATE DATABASE IF NOT EXISTS `uos_recruitment` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `uos_recruitment`;

-- --------------------------------------------------------
-- Table structure for users (applicants and admin staff)
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `role` enum('applicant','hr','hod','dean','committee') NOT NULL DEFAULT 'applicant',
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `last_login` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `idx_email` (`email`),
  KEY `idx_role` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for colleges/departments
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `colleges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `code` varchar(50) NOT NULL UNIQUE,
  `description` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for talent requests (from HoD)
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `talent_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `requested_by` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `college_id` int(11) NOT NULL,
  `rank` enum('professor','associate','assistant','lecturer') NOT NULL,
  `track` enum('regular','emirati') NOT NULL DEFAULT 'regular',
  `type` enum('teaching','clinical','research') NOT NULL DEFAULT 'teaching',
  `required_qualification` varchar(255) NOT NULL,
  `emirati_preference` tinyint(1) NOT NULL DEFAULT 0,
  `proposed_start_date` date NOT NULL,
  `advertisement_file` varchar(255) DEFAULT NULL,
  `notes` text,
  `status` enum('pending','approved','rejected','closed') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_requested_by` (`requested_by`),
  KEY `idx_college` (`college_id`),
  KEY `idx_status` (`status`),
  FOREIGN KEY (`requested_by`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`college_id`) REFERENCES `colleges`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for job vacancies (approved talent requests)
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `vacancies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `talent_request_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `college_id` int(11) NOT NULL,
  `rank` enum('professor','associate','assistant','lecturer') NOT NULL,
  `track` enum('regular','emirati') NOT NULL DEFAULT 'regular',
  `type` enum('teaching','clinical','research') NOT NULL DEFAULT 'teaching',
  `posted_date` date NOT NULL,
  `closing_date` date DEFAULT NULL,
  `status` enum('open','closed','filled') NOT NULL DEFAULT 'open',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_college` (`college_id`),
  KEY `idx_status` (`status`),
  KEY `idx_talent_request` (`talent_request_id`),
  FOREIGN KEY (`college_id`) REFERENCES `colleges`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`talent_request_id`) REFERENCES `talent_requests`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for applications
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `applications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `application_id` varchar(50) NOT NULL UNIQUE,
  `applicant_id` int(11) NOT NULL,
  `vacancy_id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `nationality` varchar(100) NOT NULL,
  `date_of_birth` date NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text,
  `highest_degree` varchar(100) NOT NULL,
  `degree_field` varchar(255) NOT NULL,
  `university` varchar(255) NOT NULL,
  `university_country` varchar(100) NOT NULL,
  `graduation_year` int(4) NOT NULL,
  `gpa` varchar(50) DEFAULT NULL,
  `additional_degrees` text,
  `scopus_publications` int(11) DEFAULT 0,
  `research_interests` text,
  `teaching_experience` int(11) DEFAULT 0,
  `residency_status` varchar(50) NOT NULL,
  `visa_number` varchar(100) DEFAULT NULL,
  `visa_expiry` date DEFAULT NULL,
  `passport_number` varchar(100) NOT NULL,
  `passport_issue_date` date NOT NULL,
  `passport_expiry_date` date NOT NULL,
  `cv_file` varchar(255) DEFAULT NULL,
  `cover_letter_file` varchar(255) DEFAULT NULL,
  `passport_copy_file` varchar(255) DEFAULT NULL,
  `degree_certificates_files` text,
  `transcripts_files` text,
  `additional_documents_files` text,
  `declaration1` tinyint(1) NOT NULL DEFAULT 0,
  `declaration2` tinyint(1) NOT NULL DEFAULT 0,
  `consent_background` tinyint(1) NOT NULL DEFAULT 0,
  `consent_data` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('received','under_review','shortlisted','interview','offer','rejected','withdrawn') NOT NULL DEFAULT 'received',
  `current_stage` enum('hr_review','dept_review','college_review','committee_review','final') NOT NULL DEFAULT 'hr_review',
  `submitted_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_applicant` (`applicant_id`),
  KEY `idx_vacancy` (`vacancy_id`),
  KEY `idx_status` (`status`),
  KEY `idx_application_id` (`application_id`),
  FOREIGN KEY (`applicant_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`vacancy_id`) REFERENCES `vacancies`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for publications
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `publications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `application_id` int(11) NOT NULL,
  `title` varchar(500) NOT NULL,
  `journal_conference` varchar(255) DEFAULT NULL,
  `year` int(4) DEFAULT NULL,
  `doi_link` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_application` (`application_id`),
  FOREIGN KEY (`application_id`) REFERENCES `applications`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for application reviews
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `application_reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `application_id` int(11) NOT NULL,
  `reviewer_id` int(11) NOT NULL,
  `stage` enum('hr_review','dept_review','college_review','committee_review') NOT NULL,
  `technical_competence` text,
  `behavioral_traits` text,
  `values_alignment` text,
  `interview_rating` int(1) DEFAULT NULL,
  `justification` text,
  `recommendation` enum('recommend','maybe','reject') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_application` (`application_id`),
  KEY `idx_reviewer` (`reviewer_id`),
  FOREIGN KEY (`application_id`) REFERENCES `applications`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`reviewer_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for interviews
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `interviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `application_id` int(11) NOT NULL,
  `scheduled_date` datetime NOT NULL,
  `duration` int(11) DEFAULT 60,
  `location` varchar(255) DEFAULT NULL,
  `interview_type` enum('phone','video','in_person') NOT NULL DEFAULT 'in_person',
  `status` enum('scheduled','completed','cancelled','rescheduled') NOT NULL DEFAULT 'scheduled',
  `notes` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_application` (`application_id`),
  KEY `idx_scheduled_date` (`scheduled_date`),
  FOREIGN KEY (`application_id`) REFERENCES `applications`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for interview panel members
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `interview_panel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `interview_id` int(11) NOT NULL,
  `panel_member_id` int(11) NOT NULL,
  `role` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_interview` (`interview_id`),
  KEY `idx_panel_member` (`panel_member_id`),
  FOREIGN KEY (`interview_id`) REFERENCES `interviews`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`panel_member_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for interview feedback
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `interview_feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `interview_id` int(11) NOT NULL,
  `panel_member_id` int(11) NOT NULL,
  `technical_performance` text,
  `behavioral_traits` text,
  `values_alignment` text,
  `rating` int(1) DEFAULT NULL,
  `recommendation` enum('recommend','maybe','reject') NOT NULL,
  `justification` text,
  `submitted_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_interview` (`interview_id`),
  KEY `idx_panel_member` (`panel_member_id`),
  FOREIGN KEY (`interview_id`) REFERENCES `interviews`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`panel_member_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for application timeline/status history
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `application_timeline` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `application_id` int(11) NOT NULL,
  `status` varchar(100) NOT NULL,
  `stage` varchar(100) NOT NULL,
  `description` text,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_application` (`application_id`),
  FOREIGN KEY (`application_id`) REFERENCES `applications`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`updated_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for notifications
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `type` enum('info','success','warning','error') NOT NULL DEFAULT 'info',
  `related_entity_type` enum('application','interview','onboarding') DEFAULT NULL,
  `related_entity_id` int(11) DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user` (`user_id`),
  KEY `idx_read` (`is_read`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for messages
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender_id` int(11) NOT NULL,
  `recipient_id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `related_entity_type` enum('application','interview','onboarding') DEFAULT NULL,
  `related_entity_id` int(11) DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_sender` (`sender_id`),
  KEY `idx_recipient` (`recipient_id`),
  KEY `idx_read` (`is_read`),
  FOREIGN KEY (`sender_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`recipient_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for onboarding (new faculty)
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `onboarding` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `application_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `status` enum('pending','in_progress','completed','overdue') NOT NULL DEFAULT 'pending',
  `progress_percentage` int(3) NOT NULL DEFAULT 0,
  `assigned_hr_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_application` (`application_id`),
  KEY `idx_user` (`user_id`),
  KEY `idx_status` (`status`),
  FOREIGN KEY (`application_id`) REFERENCES `applications`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`assigned_hr_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for onboarding tasks
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `onboarding_tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `onboarding_id` int(11) NOT NULL,
  `task_name` varchar(255) NOT NULL,
  `task_description` text,
  `category` enum('documents','health','security','administrative') NOT NULL,
  `deadline` date DEFAULT NULL,
  `status` enum('pending','in_progress','completed','overdue') NOT NULL DEFAULT 'pending',
  `uploaded_files` text,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_onboarding` (`onboarding_id`),
  KEY `idx_status` (`status`),
  FOREIGN KEY (`onboarding_id`) REFERENCES `onboarding`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for onboarding task data (bank details, emergency contact, etc.)
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `onboarding_task_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `task_id` int(11) NOT NULL,
  `data_key` varchar(100) NOT NULL,
  `data_value` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_task` (`task_id`),
  FOREIGN KEY (`task_id`) REFERENCES `onboarding_tasks`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for HR tasks
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `hr_tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `onboarding_id` int(11) NOT NULL,
  `task_name` varchar(255) NOT NULL,
  `task_description` text,
  `assigned_to` int(11) DEFAULT NULL,
  `priority` enum('normal','high','urgent') NOT NULL DEFAULT 'normal',
  `status` enum('pending','in_progress','completed') NOT NULL DEFAULT 'pending',
  `notes` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `completed_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_onboarding` (`onboarding_id`),
  KEY `idx_assigned` (`assigned_to`),
  FOREIGN KEY (`onboarding_id`) REFERENCES `onboarding`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`assigned_to`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Insert seed data
-- --------------------------------------------------------

-- Insert default admin user (password: admin123)
INSERT INTO `users` (`email`, `password`, `full_name`, `role`, `phone`) VALUES
('admin@sharjah.ac.ae', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System Administrator', 'hr', '+971501234567'),
('hr@sharjah.ac.ae', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'HR Manager', 'hr', '+971501234568'),
('hod@sharjah.ac.ae', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Head of Department', 'hod', '+971501234569'),
('dean@sharjah.ac.ae', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'College Dean', 'dean', '+971501234570'),
('applicant@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Test Applicant', 'applicant', '+971501234571');

-- Insert colleges
INSERT INTO `colleges` (`name`, `code`, `description`) VALUES
('College of Engineering', 'ENG', 'Engineering programs and research'),
('College of Sciences', 'SCI', 'Natural sciences and mathematics'),
('College of Computing and Informatics', 'CCI', 'Computer science and IT programs'),
('College of Medicine', 'MED', 'Medical education and clinical training'),
('College of Business Administration', 'BUS', 'Business and management programs'),
('College of Law', 'LAW', 'Legal studies and jurisprudence'),
('College of Arts, Humanities and Social Sciences', 'AHSS', 'Arts, humanities, and social sciences');

-- Insert sample vacancies
INSERT INTO `vacancies` (`title`, `description`, `college_id`, `rank`, `track`, `type`, `posted_date`, `status`) VALUES
('Assistant Professor – Computer Science (Emirati Track)', 'Seeking a UAE national with a PhD in Computer Science, Scopus publications, and strong teaching potential.', 3, 'assistant', 'emirati', 'teaching', '2025-06-10', 'open'),
('Associate Professor – Mechanical Engineering', 'Requires 5+ years post-PhD experience, strong research record, and teaching excellence in sustainable energy systems.', 1, 'associate', 'regular', 'teaching', '2025-06-05', 'open'),
('Lecturer – Mathematics', 'Master\'s or PhD required. Focus on undergraduate teaching in calculus and linear algebra.', 2, 'lecturer', 'regular', 'teaching', '2025-06-12', 'open'),
('Assistant Professor – Clinical Medicine', 'MD or equivalent with clinical experience. Teaching responsibilities include clinical rotations and patient care supervision.', 4, 'assistant', 'regular', 'clinical', '2025-06-08', 'open'),
('Assistant Professor – Data Science (Research Focus)', 'PhD in Data Science or related field. Strong publication record required. Focus on AI/ML research with industry collaboration opportunities.', 3, 'assistant', 'regular', 'research', '2025-06-15', 'open'),
('Associate Professor – Business Administration (Emirati Track)', 'UAE national preferred. PhD in Business Administration or related field. Reduced teaching load with mentorship support in Year 1.', 5, 'associate', 'emirati', 'teaching', '2025-06-11', 'open');

-- Note: Password hash above is for 'password' - users should change on first login
-- For production, use: password_hash('your_password', PASSWORD_DEFAULT)

