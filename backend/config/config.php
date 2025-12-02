<?php
/**
 * Application Configuration
 * University of Sharjah Faculty Recruitment System
 */

// Application settings
define('APP_NAME', 'UoS Faculty Recruitment System');
define('APP_URL', 'http://localhost');
define('APP_TIMEZONE', 'Asia/Dubai');

// Session settings
define('SESSION_LIFETIME', 3600); // 1 hour
ini_set('session.gc_maxlifetime', SESSION_LIFETIME);

// File upload settings
define('UPLOAD_MAX_SIZE', 5 * 1024 * 1024); // 5MB
define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('UPLOAD_DOCUMENTS_DIR', UPLOAD_DIR . 'documents/');
define('UPLOAD_ADVERTISEMENTS_DIR', UPLOAD_DIR . 'advertisements/');

// Allowed file types
define('ALLOWED_DOCUMENT_TYPES', ['application/pdf', 'image/jpeg', 'image/png', 'image/jpg', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']);
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/jpg']);

// Security
define('PASSWORD_MIN_LENGTH', 8);
define('CSRF_TOKEN_NAME', 'csrf_token');

// Application status stages
define('APP_STATUS_RECEIVED', 'received');
define('APP_STATUS_UNDER_REVIEW', 'under_review');
define('APP_STATUS_SHORTLISTED', 'shortlisted');
define('APP_STATUS_INTERVIEW', 'interview');
define('APP_STATUS_OFFER', 'offer');
define('APP_STATUS_REJECTED', 'rejected');
define('APP_STATUS_WITHDRAWN', 'withdrawn');

// Application stages
define('STAGE_HR_REVIEW', 'hr_review');
define('STAGE_DEPT_REVIEW', 'dept_review');
define('STAGE_COLLEGE_REVIEW', 'college_review');
define('STAGE_COMMITTEE_REVIEW', 'committee_review');
define('STAGE_FINAL', 'final');

// User roles
define('ROLE_APPLICANT', 'applicant');
define('ROLE_HR', 'hr');
define('ROLE_HOD', 'hod');
define('ROLE_DEAN', 'dean');
define('ROLE_COMMITTEE', 'committee');

// Error reporting (set to 0 in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Timezone
date_default_timezone_set(APP_TIMEZONE);

