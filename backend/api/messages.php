<?php
/**
 * Messages API Endpoint
 * GET /api/messages.php - Get user messages
 * PUT /api/messages.php?id=X - Mark message as read
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
        $messages = $notificationModel->getMessagesByUserId($user['id'], $unreadOnly);
        $unreadCount = $notificationModel->getUnreadMessageCount($user['id']);
        
        echo json_encode([
            'success' => true,
            'messages' => $messages,
            'unread_count' => $unreadCount
        ]);
    } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Message ID required']);
            exit;
        }
        
        $result = $notificationModel->markMessageAsRead($_GET['id'], $user['id']);
        echo json_encode([
            'success' => $result,
            'message' => $result ? 'Message marked as read' : 'Failed to update message'
        ]);
    } else {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

