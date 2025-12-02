<?php
/**
 * Application Model
 * Handles application CRUD operations
 */

require_once __DIR__ . '/../config/db_connection.php';

class Application {
    private $db;

    public function __construct() {
        $this->db = getDB();
    }

    /**
     * Create new application
     */
    public function create($data) {
        // Generate unique application ID
        $applicationId = 'UOS-FAC-' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        
        // Ensure unique application ID
        while ($this->findByApplicationId($applicationId)) {
            $applicationId = 'UOS-FAC-' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        }

        $sql = "INSERT INTO applications (
            application_id, applicant_id, vacancy_id, full_name, nationality, date_of_birth,
            email, phone, address, highest_degree, degree_field, university, university_country,
            graduation_year, gpa, additional_degrees, scopus_publications, research_interests,
            teaching_experience, residency_status, visa_number, visa_expiry, passport_number,
            passport_issue_date, passport_expiry_date, cv_file, cover_letter_file, passport_copy_file,
            degree_certificates_files, transcripts_files, additional_documents_files,
            declaration1, declaration2, consent_background, consent_data, status, current_stage
        ) VALUES (
            :application_id, :applicant_id, :vacancy_id, :full_name, :nationality, :date_of_birth,
            :email, :phone, :address, :highest_degree, :degree_field, :university, :university_country,
            :graduation_year, :gpa, :additional_degrees, :scopus_publications, :research_interests,
            :teaching_experience, :residency_status, :visa_number, :visa_expiry, :passport_number,
            :passport_issue_date, :passport_expiry_date, :cv_file, :cover_letter_file, :passport_copy_file,
            :degree_certificates_files, :transcripts_files, :additional_documents_files,
            :declaration1, :declaration2, :consent_background, :consent_data, :status, :current_stage
        )";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':application_id' => $applicationId,
            ':applicant_id' => $data['applicant_id'],
            ':vacancy_id' => $data['vacancy_id'],
            ':full_name' => $data['full_name'],
            ':nationality' => $data['nationality'],
            ':date_of_birth' => $data['date_of_birth'],
            ':email' => $data['email'],
            ':phone' => $data['phone'],
            ':address' => $data['address'] ?? null,
            ':highest_degree' => $data['highest_degree'],
            ':degree_field' => $data['degree_field'],
            ':university' => $data['university'],
            ':university_country' => $data['university_country'],
            ':graduation_year' => $data['graduation_year'],
            ':gpa' => $data['gpa'] ?? null,
            ':additional_degrees' => $data['additional_degrees'] ?? null,
            ':scopus_publications' => $data['scopus_publications'] ?? 0,
            ':research_interests' => $data['research_interests'] ?? null,
            ':teaching_experience' => $data['teaching_experience'] ?? 0,
            ':residency_status' => $data['residency_status'],
            ':visa_number' => $data['visa_number'] ?? null,
            ':visa_expiry' => $data['visa_expiry'] ?? null,
            ':passport_number' => $data['passport_number'],
            ':passport_issue_date' => $data['passport_issue_date'],
            ':passport_expiry_date' => $data['passport_expiry_date'],
            ':cv_file' => $data['cv_file'] ?? null,
            ':cover_letter_file' => $data['cover_letter_file'] ?? null,
            ':passport_copy_file' => $data['passport_copy_file'] ?? null,
            ':degree_certificates_files' => $data['degree_certificates_files'] ?? null,
            ':transcripts_files' => $data['transcripts_files'] ?? null,
            ':additional_documents_files' => $data['additional_documents_files'] ?? null,
            ':declaration1' => $data['declaration1'] ? 1 : 0,
            ':declaration2' => $data['declaration2'] ? 1 : 0,
            ':consent_background' => $data['consent_background'] ? 1 : 0,
            ':consent_data' => $data['consent_data'] ? 1 : 0,
            ':status' => 'received',
            ':current_stage' => 'hr_review'
        ]);

        $applicationId = $this->db->lastInsertId();

        // Add timeline entry
        $this->addTimelineEntry($applicationId, 'received', 'hr_review', 'Application received and acknowledged');

        return $applicationId;
    }

    /**
     * Find application by ID
     */
    public function findById($id) {
        $sql = "SELECT a.*, v.title as vacancy_title, v.college_id, c.name as college_name,
                u.full_name as applicant_name, u.email as applicant_email
                FROM applications a
                LEFT JOIN vacancies v ON a.vacancy_id = v.id
                LEFT JOIN colleges c ON v.college_id = c.id
                LEFT JOIN users u ON a.applicant_id = u.id
                WHERE a.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Find application by application ID
     */
    public function findByApplicationId($applicationId) {
        $sql = "SELECT * FROM applications WHERE application_id = :application_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':application_id' => $applicationId]);
        return $stmt->fetch();
    }

    /**
     * Get applications by applicant ID
     */
    public function getByApplicantId($applicantId) {
        $sql = "SELECT a.*, v.title as vacancy_title, c.name as college_name
                FROM applications a
                LEFT JOIN vacancies v ON a.vacancy_id = v.id
                LEFT JOIN colleges c ON v.college_id = c.id
                WHERE a.applicant_id = :applicant_id
                ORDER BY a.submitted_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':applicant_id' => $applicantId]);
        return $stmt->fetchAll();
    }

    /**
     * Get all applications with filters
     */
    public function getAll($filters = []) {
        $sql = "SELECT a.*, v.title as vacancy_title, c.name as college_name,
                u.full_name as applicant_name
                FROM applications a
                LEFT JOIN vacancies v ON a.vacancy_id = v.id
                LEFT JOIN colleges c ON v.college_id = c.id
                LEFT JOIN users u ON a.applicant_id = u.id
                WHERE 1=1";
        
        $params = [];

        if (isset($filters['status'])) {
            $sql .= " AND a.status = :status";
            $params[':status'] = $filters['status'];
        }

        if (isset($filters['stage'])) {
            $sql .= " AND a.current_stage = :stage";
            $params[':stage'] = $filters['stage'];
        }

        if (isset($filters['college_id'])) {
            $sql .= " AND v.college_id = :college_id";
            $params[':college_id'] = $filters['college_id'];
        }

        if (isset($filters['search'])) {
            $sql .= " AND (a.full_name LIKE :search OR a.email LIKE :search OR a.application_id LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }

        $sql .= " ORDER BY a.submitted_at DESC";

        if (isset($filters['limit'])) {
            $sql .= " LIMIT :limit";
            $params[':limit'] = $filters['limit'];
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Update application status
     */
    public function updateStatus($id, $status, $stage = null, $description = null, $updatedBy = null) {
        $sql = "UPDATE applications SET status = :status";
        $params = [':id' => $id, ':status' => $status];

        if ($stage) {
            $sql .= ", current_stage = :stage";
            $params[':stage'] = $stage;
        }

        $sql .= " WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute($params);

        if ($result && $description) {
            $this->addTimelineEntry($id, $status, $stage ?? 'hr_review', $description, $updatedBy);
        }

        return $result;
    }

    /**
     * Add timeline entry
     */
    public function addTimelineEntry($applicationId, $status, $stage, $description, $updatedBy = null) {
        $sql = "INSERT INTO application_timeline (application_id, status, stage, description, updated_by)
                VALUES (:application_id, :status, :stage, :description, :updated_by)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':application_id' => $applicationId,
            ':status' => $status,
            ':stage' => $stage,
            ':description' => $description,
            ':updated_by' => $updatedBy
        ]);
    }

    /**
     * Get timeline for application
     */
    public function getTimeline($applicationId) {
        $sql = "SELECT * FROM application_timeline 
                WHERE application_id = :application_id 
                ORDER BY created_at ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':application_id' => $applicationId]);
        return $stmt->fetchAll();
    }

    /**
     * Save publications for application
     */
    public function savePublications($applicationId, $publications) {
        $sql = "INSERT INTO publications (application_id, title, journal_conference, year, doi_link)
                VALUES (:application_id, :title, :journal_conference, :year, :doi_link)";
        $stmt = $this->db->prepare($sql);

        foreach ($publications as $pub) {
            if (!empty($pub['title'])) {
                $stmt->execute([
                    ':application_id' => $applicationId,
                    ':title' => $pub['title'],
                    ':journal_conference' => $pub['journal'] ?? null,
                    ':year' => $pub['year'] ?? null,
                    ':doi_link' => $pub['link'] ?? null
                ]);
            }
        }
    }

    /**
     * Get publications for application
     */
    public function getPublications($applicationId) {
        $sql = "SELECT * FROM publications WHERE application_id = :application_id ORDER BY year DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':application_id' => $applicationId]);
        return $stmt->fetchAll();
    }
}

