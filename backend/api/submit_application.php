<?php
/**
 * Submit Application API Endpoint
 * POST /api/submit_application.php
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/../controllers/ApplicationController.php';

$controller = new ApplicationController();
$result = $controller->submit();

http_response_code($result['success'] ? 200 : 400);
echo json_encode($result);

