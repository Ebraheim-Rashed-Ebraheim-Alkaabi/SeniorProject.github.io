<?php
/**
 * Vacancy Model
 * Handles job vacancy operations
 */

require_once __DIR__ . '/../config/db_connection.php';

class Vacancy {
    private $db;

    public function __construct() {
        $this->db = getDB();
    }

    /**
     * Get all vacancies with filters
     */
    public function getAll($filters = []) {
        $sql = "SELECT v.*, c.name as college_name, c.code as college_code
                FROM vacancies v
                LEFT JOIN colleges c ON v.college_id = c.id
                WHERE 1=1";
        
        $params = [];

        if (isset($filters['status']) && $filters['status'] !== 'all') {
            $sql .= " AND v.status = :status";
            $params[':status'] = $filters['status'];
        }

        if (isset($filters['college']) && $filters['college'] !== 'all') {
            $sql .= " AND c.code = :college";
            $params[':college'] = $filters['college'];
        }

        if (isset($filters['rank']) && $filters['rank'] !== 'all') {
            $sql .= " AND v.rank = :rank";
            $params[':rank'] = $filters['rank'];
        }

        if (isset($filters['track']) && $filters['track'] !== 'all') {
            $sql .= " AND v.track = :track";
            $params[':track'] = $filters['track'];
        }

        if (isset($filters['type']) && $filters['type'] !== 'all') {
            $sql .= " AND v.type = :type";
            $params[':type'] = $filters['type'];
        }

        $sql .= " ORDER BY v.posted_date DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Get vacancy by ID
     */
    public function findById($id) {
        $sql = "SELECT v.*, c.name as college_name, c.code as college_code
                FROM vacancies v
                LEFT JOIN colleges c ON v.college_id = c.id
                WHERE v.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Create new vacancy
     */
    public function create($data) {
        $sql = "INSERT INTO vacancies (
            talent_request_id, title, description, college_id, rank, track, type,
            posted_date, closing_date, status
        ) VALUES (
            :talent_request_id, :title, :description, :college_id, :rank, :track, :type,
            :posted_date, :closing_date, :status
        )";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':talent_request_id' => $data['talent_request_id'] ?? null,
            ':title' => $data['title'],
            ':description' => $data['description'],
            ':college_id' => $data['college_id'],
            ':rank' => $data['rank'],
            ':track' => $data['track'],
            ':type' => $data['type'],
            ':posted_date' => $data['posted_date'] ?? date('Y-m-d'),
            ':closing_date' => $data['closing_date'] ?? null,
            ':status' => $data['status'] ?? 'open'
        ]);

        return $this->db->lastInsertId();
    }

    /**
     * Update vacancy
     */
    public function update($id, $data) {
        $fields = [];
        $params = [':id' => $id];

        $allowedFields = ['title', 'description', 'status', 'closing_date'];
        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $fields[] = "$field = :$field";
                $params[":$field"] = $data[$field];
            }
        }

        if (empty($fields)) {
            return false;
        }

        $sql = "UPDATE vacancies SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }
}

