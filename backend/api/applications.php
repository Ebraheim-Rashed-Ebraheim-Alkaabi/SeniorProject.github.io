<?php
/**
 * Applications API Endpoint
 * GET /api/applications.php - Get applications (requires auth)
 * GET /api/applications.php?id=X - Get specific application
 * PUT /api/applications.php?id=X - Update application status
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, PUT, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/../controllers/ApplicationController.php';

$controller = new ApplicationController();

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (isset($_GET['id'])) {
            // Get specific application
            $result = $controller->getApplication($_GET['id']);
            http_response_code($result['success'] ? 200 : ($result['message'] === 'Access denied' ? 403 : 404));
        } else {
            // Get all applications (admin) or user's applications
            $result = $controller->getAllApplications();
            http_response_code($result['success'] ? 200 : 500);
        }
        echo json_encode($result);
    } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
        // Update application status
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Application ID required']);
            exit;
        }
        $result = $controller->updateStatus($_GET['id']);
        http_response_code($result['success'] ? 200 : 400);
        echo json_encode($result);
    } else {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

