<?php
/**
 * File Upload Controller
 * Handles secure file uploads with validation
 */

require_once __DIR__ . '/../config/config.php';

class FileUploadController {
    
    /**
     * Upload document
     */
    public function uploadDocument($file, $fieldName = 'document') {
        try {
            // Check if file was uploaded
            if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
                throw new Exception('File upload error');
            }

            // Validate file size
            if ($file['size'] > UPLOAD_MAX_SIZE) {
                throw new Exception('File size exceeds maximum allowed size of 5MB');
            }

            // Validate file type
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);

            if (!in_array($mimeType, ALLOWED_DOCUMENT_TYPES)) {
                throw new Exception('Invalid file type. Allowed types: PDF, DOC, DOCX, JPG, PNG');
            }

            // Generate unique filename
            $extension = $this->getFileExtension($file['name']);
            $filename = $fieldName . '_' . uniqid() . '_' . time() . '.' . $extension;
            $uploadPath = UPLOAD_DOCUMENTS_DIR . $filename;

            // Create upload directory if it doesn't exist
            if (!is_dir(UPLOAD_DOCUMENTS_DIR)) {
                mkdir(UPLOAD_DOCUMENTS_DIR, 0755, true);
            }

            // Move uploaded file
            if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
                throw new Exception('Failed to save file');
            }

            return [
                'success' => true,
                'file' => $filename,
                'path' => $uploadPath,
                'size' => $file['size'],
                'type' => $mimeType
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Upload multiple documents
     */
    public function uploadMultipleDocuments($files, $fieldName = 'documents') {
        $results = [];
        $uploadedFiles = [];

        if (!is_array($files['name'])) {
            // Single file
            $result = $this->uploadDocument($files, $fieldName);
            if ($result['success']) {
                $uploadedFiles[] = $result['file'];
            }
            $results[] = $result;
        } else {
            // Multiple files
            $count = count($files['name']);
            for ($i = 0; $i < $count; $i++) {
                $file = [
                    'name' => $files['name'][$i],
                    'type' => $files['type'][$i],
                    'tmp_name' => $files['tmp_name'][$i],
                    'error' => $files['error'][$i],
                    'size' => $files['size'][$i]
                ];

                $result = $this->uploadDocument($file, $fieldName . '_' . $i);
                if ($result['success']) {
                    $uploadedFiles[] = $result['file'];
                }
                $results[] = $result;
            }
        }

        return [
            'success' => count($uploadedFiles) > 0,
            'files' => $uploadedFiles,
            'results' => $results
        ];
    }

    /**
     * Upload advertisement file
     */
    public function uploadAdvertisement($file) {
        try {
            if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
                throw new Exception('File upload error');
            }

            if ($file['size'] > UPLOAD_MAX_SIZE) {
                throw new Exception('File size exceeds maximum allowed size of 5MB');
            }

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);

            $allowedTypes = ['application/pdf', 'application/msword', 
                           'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
            
            if (!in_array($mimeType, $allowedTypes)) {
                throw new Exception('Invalid file type. Allowed types: PDF, DOC, DOCX');
            }

            $extension = $this->getFileExtension($file['name']);
            $filename = 'advert_' . uniqid() . '_' . time() . '.' . $extension;
            $uploadPath = UPLOAD_ADVERTISEMENTS_DIR . $filename;

            if (!is_dir(UPLOAD_ADVERTISEMENTS_DIR)) {
                mkdir(UPLOAD_ADVERTISEMENTS_DIR, 0755, true);
            }

            if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
                throw new Exception('Failed to save file');
            }

            return [
                'success' => true,
                'file' => $filename,
                'path' => $uploadPath
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Get file extension
     */
    private function getFileExtension($filename) {
        return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    }

    /**
     * Delete file
     */
    public function deleteFile($filename, $type = 'document') {
        $path = ($type === 'advertisement') 
            ? UPLOAD_ADVERTISEMENTS_DIR . $filename
            : UPLOAD_DOCUMENTS_DIR . $filename;

        if (file_exists($path)) {
            return unlink($path);
        }

        return false;
    }
}

