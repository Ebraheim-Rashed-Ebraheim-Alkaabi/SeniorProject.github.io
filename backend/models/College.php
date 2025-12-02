<?php
/**
 * College Model
 * Handles college/department operations
 */

require_once __DIR__ . '/../config/db_connection.php';

class College {
    private $db;

    public function __construct() {
        $this->db = getDB();
    }

    /**
     * Get all colleges
     */
    public function getAll() {
        $sql = "SELECT * FROM colleges ORDER BY name ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Get college by ID
     */
    public function findById($id) {
        $sql = "SELECT * FROM colleges WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Get college by code
     */
    public function findByCode($code) {
        $sql = "SELECT * FROM colleges WHERE code = :code";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':code' => $code]);
        return $stmt->fetch();
    }
}

