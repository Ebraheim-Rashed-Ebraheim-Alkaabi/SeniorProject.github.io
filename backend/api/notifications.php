<?php
/**
 * Notifications API Endpoint
 * GET /api/notifications.php - Get user notifications
 * PUT /api/notifications.php?id=X - Mark notification as read
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, PUT, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../models/Notification.php';

$auth = new AuthController();
$auth->requireAuth();

$user = $auth->getCurrentUser();
$notificationModel = new Notification();

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $unreadOnly = isset($_GET['unread']) && $_GET['unread'] === 'true';
        $notifications = $notificationModel->getByUserId($user['id'], $unreadOnly);
        $unreadCount = $notificationModel->getUnreadCount($user['id']);
        
        echo json_encode([
            'success' => true,
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
    } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Notification ID required']);
            exit;
        }
        
        $result = $notificationModel->markAsRead($_GET['id'], $user['id']);
        echo json_encode([
            'success' => $result,
            'message' => $result ? 'Notification marked as read' : 'Failed to update notification'
        ]);
    } else {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

