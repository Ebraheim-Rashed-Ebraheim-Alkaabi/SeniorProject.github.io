<?php
/**
 * Test API Endpoint
 * Use this to test if the API and database are working
 * Access: http://localhost/project/backend/test_api.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>API Test Page</h2>";
echo "<hr>";

// Test 1: Check if database config exists
echo "<h3>1. Checking Database Configuration...</h3>";
if (file_exists(__DIR__ . '/config/database.php')) {
    echo "✅ Database config file exists<br>";
    $dbConfig = require __DIR__ . '/config/database.php';
    echo "Database: " . $dbConfig['dbname'] . "<br>";
    echo "Host: " . $dbConfig['host'] . "<br>";
    echo "Username: " . $dbConfig['username'] . "<br>";
} else {
    echo "❌ Database config file NOT found!<br>";
    exit;
}

// Test 2: Check database connection
echo "<h3>2. Testing Database Connection...</h3>";
try {
    require_once __DIR__ . '/config/db_connection.php';
    $db = getDB();
    echo "✅ Database connection successful!<br>";
} catch (Exception $e) {
    echo "❌ Database connection FAILED: " . $e->getMessage() . "<br>";
    exit;
}

// Test 3: Check if tables exist
echo "<h3>3. Checking Database Tables...</h3>";
try {
    $tables = ['users', 'colleges', 'vacancies', 'applications'];
    foreach ($tables as $table) {
        $stmt = $db->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            echo "✅ Table '$table' exists<br>";
        } else {
            echo "❌ Table '$table' NOT found!<br>";
        }
    }
} catch (Exception $e) {
    echo "❌ Error checking tables: " . $e->getMessage() . "<br>";
}

// Test 4: Check vacancies data
echo "<h3>4. Checking Vacancies Data...</h3>";
try {
    $stmt = $db->query("SELECT COUNT(*) as total FROM vacancies WHERE status = 'open'");
    $result = $stmt->fetch();
    $count = $result['total'];
    
    if ($count > 0) {
        echo "✅ Found $count open vacancies in database<br>";
        
        // Show sample vacancies
        $stmt = $db->query("SELECT id, title, status FROM vacancies LIMIT 5");
        $vacancies = $stmt->fetchAll();
        echo "<ul>";
        foreach ($vacancies as $vacancy) {
            echo "<li>ID: {$vacancy['id']} - {$vacancy['title']} (Status: {$vacancy['status']})</li>";
        }
        echo "</ul>";
    } else {
        echo "❌ No open vacancies found! Database might be empty.<br>";
        echo "💡 Solution: Import database.sql file in phpMyAdmin<br>";
    }
} catch (Exception $e) {
    echo "❌ Error checking vacancies: " . $e->getMessage() . "<br>";
}

// Test 5: Test Vacancy Model
echo "<h3>5. Testing Vacancy Model...</h3>";
try {
    require_once __DIR__ . '/models/Vacancy.php';
    $vacancyModel = new Vacancy();
    $vacancies = $vacancyModel->getAll(['status' => 'open']);
    
    if (count($vacancies) > 0) {
        echo "✅ Vacancy model working! Found " . count($vacancies) . " vacancies<br>";
    } else {
        echo "⚠️ Vacancy model working but no vacancies returned<br>";
    }
} catch (Exception $e) {
    echo "❌ Vacancy model error: " . $e->getMessage() . "<br>";
}

// Test 6: Test API Endpoint
echo "<h3>6. Testing API Endpoint...</h3>";
echo "Try accessing: <a href='api/vacancies.php' target='_blank'>api/vacancies.php</a><br>";

echo "<hr>";
echo "<h3>Summary</h3>";
echo "<p>If all tests pass, the API should work. If vacancies page still doesn't show listings:</p>";
echo "<ul>";
echo "<li>Check browser console (F12) for JavaScript errors</li>";
echo "<li>Check Network tab to see if API request is being made</li>";
echo "<li>Verify the API path in vacancies.html matches your server setup</li>";
echo "</ul>";

