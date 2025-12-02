<?php
/**
 * Notification Model
 * Handles notifications and messages
 */

require_once __DIR__ . '/../config/db_connection.php';

class Notification {
    private $db;

    public function __construct() {
        $this->db = getDB();
    }

    /**
     * Create notification
     */
    public function create($data) {
        $sql = "INSERT INTO notifications (
            user_id, title, message, type, related_entity_type, related_entity_id
        ) VALUES (
            :user_id, :title, :message, :type, :related_entity_type, :related_entity_id
        )";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':user_id' => $data['user_id'],
            ':title' => $data['title'],
            ':message' => $data['message'],
            ':type' => $data['type'] ?? 'info',
            ':related_entity_type' => $data['related_entity_type'] ?? null,
            ':related_entity_id' => $data['related_entity_id'] ?? null
        ]);

        return $this->db->lastInsertId();
    }

    /**
     * Get notifications for user
     */
    public function getByUserId($userId, $unreadOnly = false) {
        $sql = "SELECT * FROM notifications WHERE user_id = :user_id";
        
        if ($unreadOnly) {
            $sql .= " AND is_read = 0";
        }

        $sql .= " ORDER BY created_at DESC LIMIT 50";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll();
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($id, $userId) {
        $sql = "UPDATE notifications SET is_read = 1 
                WHERE id = :id AND user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id, ':user_id' => $userId]);
    }

    /**
     * Get unread count
     */
    public function getUnreadCount($userId) {
        $sql = "SELECT COUNT(*) as count FROM notifications 
                WHERE user_id = :user_id AND is_read = 0";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        $result = $stmt->fetch();
        return $result['count'] ?? 0;
    }

    /**
     * Create message
     */
    public function createMessage($data) {
        $sql = "INSERT INTO messages (
            sender_id, recipient_id, subject, message, related_entity_type, related_entity_id
        ) VALUES (
            :sender_id, :recipient_id, :subject, :message, :related_entity_type, :related_entity_id
        )";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':sender_id' => $data['sender_id'],
            ':recipient_id' => $data['recipient_id'],
            ':subject' => $data['subject'],
            ':message' => $data['message'],
            ':related_entity_type' => $data['related_entity_type'] ?? null,
            ':related_entity_id' => $data['related_entity_id'] ?? null
        ]);

        return $this->db->lastInsertId();
    }

    /**
     * Get messages for user
     */
    public function getMessagesByUserId($userId, $unreadOnly = false) {
        $sql = "SELECT m.*, u.full_name as sender_name, u.email as sender_email
                FROM messages m
                LEFT JOIN users u ON m.sender_id = u.id
                WHERE m.recipient_id = :user_id";
        
        if ($unreadOnly) {
            $sql .= " AND m.is_read = 0";
        }

        $sql .= " ORDER BY m.created_at DESC LIMIT 50";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll();
    }

    /**
     * Mark message as read
     */
    public function markMessageAsRead($id, $userId) {
        $sql = "UPDATE messages SET is_read = 1 
                WHERE id = :id AND recipient_id = :user_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id, ':user_id' => $userId]);
    }

    /**
     * Get unread message count
     */
    public function getUnreadMessageCount($userId) {
        $sql = "SELECT COUNT(*) as count FROM messages 
                WHERE recipient_id = :user_id AND is_read = 0";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        $result = $stmt->fetch();
        return $result['count'] ?? 0;
    }
}

