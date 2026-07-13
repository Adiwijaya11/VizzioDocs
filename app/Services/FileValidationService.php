<?php

namespace App\Services;

use Exception;

/**
 * FileValidationService
 * 
 * Handles validation of uploaded files across all PDF tools.
 * Provides centralized validation rules for file type, size, and integrity.
 */
class FileValidationService
{
    // File type configurations
    public const ALLOWED_TYPES = [
        'pdf' => 'application/pdf',
        'jpg' => ['image/jpeg', 'image/jpg'],
        'jpeg' => ['image/jpeg', 'image/jpg'],
        'png' => 'image/png',
        'doc' => 'application/msword',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'xls' => 'application/vnd.ms-excel',
        'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    ];

    // Size limits (in bytes)
    public const MAX_FILE_SIZE = 30 * 1024 * 1024; // 30MB default
    public const MAX_FILE_SIZE_PREMIUM = 200 * 1024 * 1024; // 200MB for premium users
    public const MIN_FILE_SIZE = 100; // 100 bytes minimum

    public const SIZE_LIMITS = [
        'pdf' => 30 * 1024 * 1024,      // 30MB
        'image' => 50 * 1024 * 1024,    // 50MB
        'document' => 25 * 1024 * 1024, // 25MB
    ];

    /**
     * Validate file with type checking
     * 
     * @param object $file The uploaded file object
     * @param array $allowedMimes Allowed MIME types
     * @param int|null $maxSize Maximum file size in bytes
     * @param \App\Models\User|null $user The authenticated user (optional)
     * @param int|null $adminConfiguredMaxSize The maximum file size configured by the admin (in MB)
     * @return array ['valid' => bool, 'errors' => array]
     */
    public function validate($file, $allowedMimes = ['pdf'], $maxSize = null, ?\App\Models\User $user = null, ?int $adminConfiguredMaxSize = null)
    {
        $errors = [];

        // Validate file exists
        if (!$file || !$file->isValid()) {
            $errors[] = 'File upload failed or file is invalid.';
            return ['valid' => false, 'errors' => $errors];
        }

        // Determine max file size based on user plan and admin settings
        $effectiveMaxSize = self::MAX_FILE_SIZE; // Default for free users

        if ($user && $user->isPremium()) {
            $effectiveMaxSize = self::MAX_FILE_SIZE_PREMIUM;
        } else {
            if ($adminConfiguredMaxSize !== null) {
                $effectiveMaxSize = $adminConfiguredMaxSize * 1024 * 1024;
            } else {
                // If no admin setting, use the default free user limit
                $effectiveMaxSize = self::MAX_FILE_SIZE;
            }
        }

        // Override with specific tool maxSize if provided
        $maxSize = $maxSize ?? $effectiveMaxSize;

        $errors = [];

        // Validate file exists
        if (!$file || !$file->isValid()) {
            $errors[] = 'File upload failed or file is invalid.';
            return ['valid' => false, 'errors' => $errors];
        }

        // Validate file size
        $maxSize = $maxSize ?? self::MAX_FILE_SIZE;
        if ($file->getSize() < self::MIN_FILE_SIZE) {
            $errors[] = 'File is too small. Minimum size: ' . $this->formatBytes(self::MIN_FILE_SIZE);
        }
        if ($file->getSize() > $maxSize) {
            $errors[] = 'File is too large. Maximum size: ' . $this->formatBytes($maxSize);
        }

        // Validate MIME type
        if (!$this->isAllowedMime($file->getMimeType(), $allowedMimes)) {
            $errors[] = 'File type not allowed. Allowed types: ' . implode(', ', $allowedMimes);
        }

        // Validate file extension
        if (!$this->isAllowedExtension($file->getClientOriginalExtension(), $allowedMimes)) {
            $errors[] = 'File extension not allowed.';
        }

        // Additional PDF validation
        if ($file->getClientOriginalExtension() === 'pdf') {
            if (!$this->isPdfValid($file)) {
                $errors[] = 'PDF file is corrupted or invalid.';
            }
        }

        return [
            'valid' => count($errors) === 0,
            'errors' => $errors,
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'original_name' => $file->getClientOriginalName()
        ];
    }

    /**
     * Check if MIME type is allowed
     * 
     * @param string $mimeType
     * @param array $allowedMimes
     * @return bool
     */
    private function isAllowedMime($mimeType, $allowedMimes)
    {
        foreach ($allowedMimes as $allowed) {
            if ($allowed === '*') {
                return true; // Accept all
            }

            if (is_array(self::ALLOWED_TYPES[$allowed] ?? null)) {
                if (in_array($mimeType, self::ALLOWED_TYPES[$allowed])) {
                    return true;
                }
            } else {
                if ($mimeType === (self::ALLOWED_TYPES[$allowed] ?? $allowed)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Check if file extension is allowed
     * 
     * @param string $extension
     * @param array $allowedMimes
     * @return bool
     */
    private function isAllowedExtension($extension, $allowedMimes)
    {
        return in_array(strtolower($extension), $allowedMimes);
    }

    /**
     * Validate PDF file integrity
     * 
     * @param object $file
     * @return bool
     */
    private function isPdfValid($file)
    {
        try {
            $path = $file->getRealPath();
            $handle = fopen($path, 'r');
            
            if (!$handle) {
                return false;
            }

            // Check PDF signature
            $header = fread($handle, 5);
            fclose($handle);

            return strpos($header, '%PDF') === 0;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Validate multiple files
     * 
     * @param array $files Array of file objects
     * @param array $allowedMimes Allowed MIME types
     * @return array Results for each file
     */
    public function validateMultiple($files, $allowedMimes = ['pdf'])
    {
        $results = [];
        foreach ($files as $file) {
            $results[] = $this->validate($file, $allowedMimes);
        }
        return $results;
    }

    /**
     * Format bytes to human-readable format
     * 
     * @param int $bytes
     * @param int $precision
     * @return string
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    /**
     * Get total size of files
     * 
     * @param array $files
     * @return int
     */
    public function getTotalSize($files)
    {
        $total = 0;
        foreach ($files as $file) {
            $total += $file->getSize();
        }
        return $total;
    }

    /**
     * Get file size in human-readable format
     * 
     * @param int $bytes
     * @return string
     */
    public function formatFileSize($bytes)
    {
        return $this->formatBytes($bytes);
    }

    /**
     * Get the effective maximum file size in bytes based on user plan and admin settings.
     *
     * @param \App\Models\User|null $user The authenticated user (optional)
     * @param int|null $adminConfiguredMaxSizeMB The maximum file size configured by the admin (in MB)
     * @return int The effective maximum file size in bytes.
     */
    public function getEffectiveMaxFileSize(?\App\Models\User $user = null, ?int $adminConfiguredMaxSizeMB = null): int
    {
        $effectiveMaxSize = self::MAX_FILE_SIZE; // Default for free users

        if ($user && $user->isPremium()) {
            $effectiveMaxSize = self::MAX_FILE_SIZE_PREMIUM;
        } else {
            if ($adminConfiguredMaxSizeMB !== null) {
                $effectiveMaxSize = $adminConfiguredMaxSizeMB * 1024 * 1024;
            }
        }

        return $effectiveMaxSize;
    }

    /**
     * Get the admin-configured max file size from settings.json (in MB).
     * Returns the default MAX_FILE_SIZE (in MB) if not configured or on error.
     */
    public static function getAdminMaxFileSizeMB(): int
    {
        $settingsPath = storage_path('app/settings.json');
        if (file_exists($settingsPath)) {
            try {
                $settings = json_decode(file_get_contents($settingsPath), true) ?? [];
                if (isset($settings['max_file_size']) && is_numeric($settings['max_file_size'])) {
                    return (int) $settings['max_file_size'];
                }
            } catch (\Exception $e) {
                // Fall through to default
            }
        }
        // Default fallback: 30MB
        return self::MAX_FILE_SIZE / (1024 * 1024);
    }
}
