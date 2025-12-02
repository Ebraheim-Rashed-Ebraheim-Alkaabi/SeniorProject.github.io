<?php
/**
 * Talent Request Model
 * Handles talent request operations from HoD
 */

require_once __DIR__ . '/../config/db_connection.php';

class TalentRequest {
    private $db;

    public function __construct() {
        $this->db = getDB();
    }

    /**
     * Create new talent request
     */
    public function create($data) {
        $sql = "INSERT INTO talent_requests (
            requested_by, title, description, college_id, rank, track, type,
            required_qualification, emirati_preference, proposed_start_date,
            advertisement_file, notes, status
        ) VALUES (
            :requested_by, :title, :description, :college_id, :rank, :track, :type,
            :required_qualification, :emirati_preference, :proposed_start_date,
            :advertisement_file, :notes, :status
        )";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':requested_by' => $data['requested_by'],
            ':title' => $data['title'],
            ':description' => $data['description'],
            ':college_id' => $data['college_id'],
            ':rank' => $data['rank'],
            ':track' => $data['track'] ?? 'regular',
            ':type' => $data['type'] ?? 'teaching',
            ':required_qualification' => $data['required_qualification'],
            ':emirati_preference' => $data['emirati_preference'] ? 1 : 0,
            ':proposed_start_date' => $data['proposed_start_date'],
            ':advertisement_file' => $data['advertisement_file'] ?? null,
            ':notes' => $data['notes'] ?? null,
            ':status' => 'pending'
        ]);

        return $this->db->lastInsertId();
    }

    /**
     * Get all talent requests
     */
    public function getAll($filters = []) {
        $sql = "SELECT tr.*, c.name as college_name, u.full_name as requested_by_name
                FROM talent_requests tr
                LEFT JOIN colleges c ON tr.college_id = c.id
                LEFT JOIN users u ON tr.requested_by = u.id
                WHERE 1=1";
        
        $params = [];

        if (isset($filters['status']) && $filters['status'] !== 'all') {
            $sql .= " AND tr.status = :status";
            $params[':status'] = $filters['status'];
        }

        if (isset($filters['requested_by'])) {
            $sql .= " AND tr.requested_by = :requested_by";
            $params[':requested_by'] = $filters['requested_by'];
        }

        $sql .= " ORDER BY tr.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Get talent request by ID
     */
    public function findById($id) {
        $sql = "SELECT tr.*, c.name as college_name, u.full_name as requested_by_name
                FROM talent_requests tr
                LEFT JOIN colleges c ON tr.college_id = c.id
                LEFT JOIN users u ON tr.requested_by = u.id
                WHERE tr.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Update talent request status
     */
    public function updateStatus($id, $status) {
        $sql = "UPDATE talent_requests SET status = :status WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':status' => $status, ':id' => $id]);
    }
}

