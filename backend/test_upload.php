<?php
/**
 * Test File Upload
 * Use this to test file upload functionality
 * Access: http://localhost/project/backend/test_upload.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>File Upload Test Page</h2>";
echo "<hr>";

// Test 1: Check PHP upload settings
echo "<h3>1. PHP Upload Settings</h3>";
echo "upload_max_filesize: " . ini_get('upload_max_filesize') . "<br>";
echo "post_max_size: " . ini_get('post_max_size') . "<br>";
echo "file_uploads: " . (ini_get('file_uploads') ? 'Enabled' : 'Disabled') . "<br>";
echo "max_file_uploads: " . ini_get('max_file_uploads') . "<br>";
echo "<br>";

// Test 2: Check upload directory
echo "<h3>2. Upload Directory Check</h3>";
require_once __DIR__ . '/config/config.php';

if (defined('UPLOAD_DOCUMENTS_DIR')) {
    echo "Upload directory path: " . UPLOAD_DOCUMENTS_DIR . "<br>";
    
    if (is_dir(UPLOAD_DOCUMENTS_DIR)) {
        echo "✅ Directory exists<br>";
        
        if (is_writable(UPLOAD_DOCUMENTS_DIR)) {
            echo "✅ Directory is writable<br>";
        } else {
            echo "❌ Directory is NOT writable!<br>";
            echo "💡 Solution: Set permissions to 755 or 777<br>";
        }
    } else {
        echo "❌ Directory does NOT exist!<br>";
        echo "💡 Attempting to create...<br>";
        if (mkdir(UPLOAD_DOCUMENTS_DIR, 0755, true)) {
            echo "✅ Directory created successfully<br>";
        } else {
            echo "❌ Failed to create directory<br>";
        }
    }
} else {
    echo "❌ UPLOAD_DOCUMENTS_DIR constant not defined!<br>";
}
echo "<br>";

// Test 3: Check fileinfo extension
echo "<h3>3. PHP Extensions</h3>";
if (function_exists('finfo_open')) {
    echo "✅ Fileinfo extension is available<br>";
} else {
    echo "❌ Fileinfo extension is NOT available!<br>";
    echo "💡 Solution: Enable fileinfo extension in php.ini<br>";
}
echo "<br>";

// Test 4: Test file upload (if form submitted)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['test_file'])) {
    echo "<h3>4. Test File Upload</h3>";
    
    $file = $_FILES['test_file'];
    echo "File name: " . $file['name'] . "<br>";
    echo "File size: " . $file['size'] . " bytes<br>";
    echo "File type: " . $file['type'] . "<br>";
    echo "Error code: " . $file['error'] . "<br>";
    
    if ($file['error'] === UPLOAD_ERR_OK) {
        require_once __DIR__ . '/controllers/FileUploadController.php';
        $uploadController = new FileUploadController();
        $result = $uploadController->uploadDocument($file, 'test');
        
        if ($result['success']) {
            echo "✅ File uploaded successfully!<br>";
            echo "Saved as: " . $result['file'] . "<br>";
            echo "Path: " . $result['path'] . "<br>";
        } else {
            echo "❌ Upload failed: " . $result['message'] . "<br>";
        }
    } else {
        $errorMessages = [
            UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize',
            UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE',
            UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
            UPLOAD_ERR_NO_FILE => 'No file was uploaded',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
            UPLOAD_ERR_EXTENSION => 'File upload stopped by extension'
        ];
        echo "❌ Upload error: " . ($errorMessages[$file['error']] ?? 'Unknown error') . "<br>";
    }
    echo "<br>";
}

// Test form
echo "<h3>5. Test Upload Form</h3>";
echo '<form method="POST" enctype="multipart/form-data">';
echo '<input type="file" name="test_file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required><br><br>';
echo '<button type="submit">Test Upload</button>';
echo '</form>';

echo "<hr>";
echo "<h3>Summary</h3>";
echo "<p>If all checks pass, file uploads should work. Common issues:</p>";
echo "<ul>";
echo "<li><strong>Directory not writable:</strong> Set permissions to 755 or 777</li>";
echo "<li><strong>File too large:</strong> Increase upload_max_filesize in php.ini</li>";
echo "<li><strong>POST too large:</strong> Increase post_max_size in php.ini</li>";
echo "<li><strong>Fileinfo missing:</strong> Enable fileinfo extension</li>";
echo "</ul>";

