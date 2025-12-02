<?php
/**
 * Application Controller
 * Handles application submission and management
 */

require_once __DIR__ . '/AuthController.php';
require_once __DIR__ . '/../models/Application.php';
require_once __DIR__ . '/../models/Vacancy.php';
require_once __DIR__ . '/../models/Notification.php';
require_once __DIR__ . '/FileUploadController.php';

class ApplicationController {
    private $applicationModel;
    private $vacancyModel;
    private $notificationModel;
    private $auth;
    private $fileUpload;

    public function __construct() {
        $this->applicationModel = new Application();
        $this->vacancyModel = new Vacancy();
        $this->notificationModel = new Notification();
        $this->auth = new AuthController();
        $this->fileUpload = new FileUploadController();
    }

    /**
     * Submit new application
     */
    public function submit() {
        header('Content-Type: application/json');

        try {
            // Get form data - handle both JSON and form-data
            $data = [];
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_POST['fullName'])) {
                    // Form data submission (multipart/form-data)
                    $data = $_POST;
                } else {
                    // JSON submission
                    $jsonInput = file_get_contents('php://input');
                    if (!empty($jsonInput)) {
                        $data = json_decode($jsonInput, true);
                    }
                }
            }

            if (!$data) {
                throw new Exception('No data received');
            }

            // Validate required fields
            $required = ['fullName', 'email', 'phone', 'nationality', 'dateOfBirth', 
                        'highestDegree', 'degreeField', 'university', 'universityCountry',
                        'graduationYear', 'passportNumber', 'passportIssueDate', 
                        'passportExpiryDate', 'residencyStatus', 'vacancy_id'];
            
            foreach ($required as $field) {
                if (empty($data[$field])) {
                    throw new Exception("Field $field is required");
                }
            }

            // Handle file uploads
            $fileData = $this->handleFileUploads();

            // Get or create applicant user
            $userModel = new User();
            $applicant = $userModel->findByEmail($data['email']);
            
            if (!$applicant) {
                // Create new applicant account
                $applicantId = $userModel->create([
                    'email' => $data['email'],
                    'password' => bin2hex(random_bytes(8)), // Temporary password
                    'full_name' => $data['fullName'],
                    'role' => 'applicant',
                    'phone' => $data['phone']
                ]);
            } else {
                $applicantId = $applicant['id'];
            }

            // Prepare application data
            $applicationData = [
                'applicant_id' => $applicantId,
                'vacancy_id' => $data['vacancy_id'],
                'full_name' => $data['fullName'],
                'nationality' => $data['nationality'],
                'date_of_birth' => $data['dateOfBirth'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'address' => $data['address'] ?? null,
                'highest_degree' => $data['highestDegree'],
                'degree_field' => $data['degreeField'],
                'university' => $data['university'],
                'university_country' => $data['universityCountry'],
                'graduation_year' => $data['graduationYear'],
                'gpa' => $data['gpa'] ?? null,
                'additional_degrees' => $data['additionalDegrees'] ?? null,
                'scopus_publications' => $data['scopusCount'] ?? 0,
                'research_interests' => $data['researchInterests'] ?? null,
                'teaching_experience' => $data['teachingExperience'] ?? 0,
                'residency_status' => $data['residencyStatus'],
                'visa_number' => $data['visaNumber'] ?? null,
                'visa_expiry' => $data['visaExpiry'] ?? null,
                'passport_number' => $data['passportNumber'],
                'passport_issue_date' => $data['passportIssueDate'],
                'passport_expiry_date' => $data['passportExpiryDate'],
                'cv_file' => $fileData['cv'] ?? null,
                'cover_letter_file' => $fileData['coverLetter'] ?? null,
                'passport_copy_file' => $fileData['passportCopy'] ?? null,
                'degree_certificates_files' => $fileData['degreeCertificates'] ?? null,
                'transcripts_files' => $fileData['transcripts'] ?? null,
                'additional_documents_files' => $fileData['additionalDocuments'] ?? null,
                'declaration1' => isset($data['declaration1']) && $data['declaration1'] === 'on',
                'declaration2' => isset($data['declaration2']) && $data['declaration2'] === 'on',
                'consent_background' => isset($data['consentBackground']) && $data['consentBackground'] === 'on',
                'consent_data' => isset($data['consentData']) && $data['consentData'] === 'on'
            ];

            // Create application
            $applicationId = $this->applicationModel->create($applicationData);

            // Save publications if provided
            if (isset($data['publications']) && is_array($data['publications'])) {
                $publications = [];
                foreach ($data['publications'] as $key => $value) {
                    if (strpos($key, 'pubTitle') === 0) {
                        $index = str_replace('pubTitle', '', $key);
                        $publications[$index]['title'] = $value;
                    } elseif (strpos($key, 'pubJournal') === 0) {
                        $index = str_replace('pubJournal', '', $key);
                        $publications[$index]['journal'] = $value;
                    } elseif (strpos($key, 'pubYear') === 0) {
                        $index = str_replace('pubYear', '', $key);
                        $publications[$index]['year'] = $value;
                    } elseif (strpos($key, 'pubLink') === 0) {
                        $index = str_replace('pubLink', '', $key);
                        $publications[$index]['link'] = $value;
                    }
                }
                if (!empty($publications)) {
                    $this->applicationModel->savePublications($applicationId, $publications);
                }
            }

            // Get application details
            $application = $this->applicationModel->findById($applicationId);

            // Create notification
            $this->notificationModel->create([
                'user_id' => $applicantId,
                'title' => 'Application Received',
                'message' => 'Thank you for applying to the University of Sharjah. We have received your application for ' . $application['vacancy_title'] . '.',
                'type' => 'success',
                'related_entity_type' => 'application',
                'related_entity_id' => $applicationId
            ]);

            return [
                'success' => true,
                'message' => 'Application submitted successfully',
                'application_id' => $application['application_id'],
                'application' => $application
            ];

        } catch (Exception $e) {
            http_response_code(400);
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Handle file uploads
     */
    private function handleFileUploads() {
        $fileData = [];
        $uploadFields = ['cv', 'coverLetter', 'passportCopy', 'degreeCertificates', 'transcripts', 'additionalDocuments'];

        foreach ($uploadFields as $field) {
            if (isset($_FILES[$field]) && $_FILES[$field]['error'] === UPLOAD_ERR_OK) {
                // Check if multiple files (array)
                if (is_array($_FILES[$field]['name'])) {
                    // Multiple files
                    $result = $this->fileUpload->uploadMultipleDocuments($_FILES[$field], $field);
                    if ($result['success'] && !empty($result['files'])) {
                        $fileData[$field] = json_encode($result['files']);
                    }
                } else {
                    // Single file
                    $result = $this->fileUpload->uploadDocument($_FILES[$field], $field);
                    if ($result['success']) {
                        $fileData[$field] = $result['file'];
                    } else {
                        // Log error but don't fail the entire submission
                        error_log("File upload failed for $field: " . ($result['message'] ?? 'Unknown error'));
                    }
                }
            } elseif (isset($_FILES[$field]) && $_FILES[$field]['error'] !== UPLOAD_ERR_NO_FILE) {
                // File upload error (but file was attempted)
                $errorMessages = [
                    UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize',
                    UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE',
                    UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
                    UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
                    UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
                    UPLOAD_ERR_EXTENSION => 'File upload stopped by extension'
                ];
                $errorMsg = $errorMessages[$_FILES[$field]['error']] ?? 'Unknown upload error';
                error_log("File upload error for $field: " . $errorMsg);
            }
        }

        return $fileData;
    }

    /**
     * Get applications for current user
     */
    public function getMyApplications() {
        $this->auth->requireAuth();
        
        $user = $this->auth->getCurrentUser();
        $applications = $this->applicationModel->getByApplicantId($user['id']);

        return [
            'success' => true,
            'applications' => $applications
        ];
    }

    /**
     * Get application details
     */
    public function getApplication($id) {
        $this->auth->requireAuth();
        
        $application = $this->applicationModel->findById($id);
        $user = $this->auth->getCurrentUser();

        // Check if user owns this application or is admin
        if ($application['applicant_id'] != $user['id'] && !in_array($user['role'], ['hr', 'hod', 'dean', 'committee'])) {
            http_response_code(403);
            return ['success' => false, 'message' => 'Access denied'];
        }

        $timeline = $this->applicationModel->getTimeline($id);
        $publications = $this->applicationModel->getPublications($id);

        return [
            'success' => true,
            'application' => $application,
            'timeline' => $timeline,
            'publications' => $publications
        ];
    }

    /**
     * Get all applications (admin)
     */
    public function getAllApplications() {
        $this->auth->requireRole(['hr', 'hod', 'dean', 'committee']);

        $filters = [];
        if (isset($_GET['status'])) {
            $filters['status'] = $_GET['status'];
        }
        if (isset($_GET['stage'])) {
            $filters['stage'] = $_GET['stage'];
        }
        if (isset($_GET['search'])) {
            $filters['search'] = $_GET['search'];
        }

        $applications = $this->applicationModel->getAll($filters);

        return [
            'success' => true,
            'applications' => $applications
        ];
    }

    /**
     * Update application status
     */
    public function updateStatus($id) {
        $this->auth->requireRole(['hr', 'hod', 'dean', 'committee']);

        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($data['status'])) {
            http_response_code(400);
            return ['success' => false, 'message' => 'Status is required'];
        }

        $user = $this->auth->getCurrentUser();
        $result = $this->applicationModel->updateStatus(
            $id,
            $data['status'],
            $data['stage'] ?? null,
            $data['description'] ?? null,
            $user['id']
        );

        if ($result) {
            // Create notification for applicant
            $application = $this->applicationModel->findById($id);
            $this->notificationModel->create([
                'user_id' => $application['applicant_id'],
                'title' => 'Application Status Update',
                'message' => $data['description'] ?? 'Your application status has been updated.',
                'type' => 'info',
                'related_entity_type' => 'application',
                'related_entity_id' => $id
            ]);
        }

        return [
            'success' => $result,
            'message' => $result ? 'Status updated successfully' : 'Failed to update status'
        ];
    }
}

