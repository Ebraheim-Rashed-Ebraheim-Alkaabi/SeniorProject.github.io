<?php
/**
 * Vacancies API Endpoint
 * GET /api/vacancies.php - Get all vacancies
 * GET /api/vacancies.php?id=X - Get specific vacancy
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../models/Vacancy.php';
require_once __DIR__ . '/../models/College.php';

$vacancyModel = new Vacancy();
$collegeModel = new College();

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (isset($_GET['id'])) {
            // Get specific vacancy
            $vacancy = $vacancyModel->findById($_GET['id']);
            if ($vacancy) {
                echo json_encode(['success' => true, 'vacancy' => $vacancy]);
            } else {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Vacancy not found']);
            }
        } else {
            // Get all vacancies with filters
            $filters = [];
            if (isset($_GET['college']) && $_GET['college'] !== 'all') {
                $filters['college'] = $_GET['college'];
            }
            if (isset($_GET['rank']) && $_GET['rank'] !== 'all') {
                $filters['rank'] = $_GET['rank'];
            }
            if (isset($_GET['track']) && $_GET['track'] !== 'all') {
                $filters['track'] = $_GET['track'];
            }
            if (isset($_GET['type']) && $_GET['type'] !== 'all') {
                $filters['type'] = $_GET['type'];
            }
            if (isset($_GET['status'])) {
                $filters['status'] = $_GET['status'];
            } else {
                $filters['status'] = 'open'; // Default to open vacancies
            }

            $vacancies = $vacancyModel->getAll($filters);
            echo json_encode(['success' => true, 'vacancies' => $vacancies]);
        }
    } else {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

