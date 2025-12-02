<?php
/**
 * My Applications API Endpoint
 * GET /api/my_applications.php - Get current user's applications
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/../controllers/ApplicationController.php';

$controller = new ApplicationController();
$result = $controller->getMyApplications();

http_response_code($result['success'] ? 200 : 401);
echo json_encode($result);

