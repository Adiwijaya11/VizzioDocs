<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Exception;

/**
 * FileStorageService
 * 
 * Manages secure file storage, cleanup, and organization for all PDF processing operations.
 * Provides centralized session-based storage with automatic cleanup mechanisms.
 */
class FileStorageService
{
    // Storage paths
    private const STORAGE_BASE = 'app/private/vizziodocs';
    private const TEMP_DIR = 'app/private/vizziodocs/temp';
    private const PROCESSING_DIR = 'app/private/vizziodocs/processing';
    private const OUTPUT_DIR = 'app/private/vizziodocs/output';

    // File lifetime (in minutes)
    private const FILE_LIFETIME = 60; // 1 hour

    /**
     * Create a new processing session
     * 
     * @param string|null $sessionId Optional session ID (generates one if not provided)
     * @return string Session ID
     */
    public function createSession($sessionId = null)
    {
        $sessionId = $sessionId ?? Str::uuid()->toString();
        $sessionPath = storage_path(self::STORAGE_BASE . '/' . $sessionId);

        File::makeDirectory($sessionPath, 0755, true);
        File::makeDirectory($sessionPath . '/input', 0755, true);
        File::makeDirectory($sessionPath . '/output', 0755, true);
        File::makeDirectory($sessionPath . '/temp', 0755, true);

        return $sessionId;
    }

    /**
     * Get session directory path
     * 
     * @param string $sessionId
     * @return string
     */
    public function getSessionPath($sessionId)
    {
        return storage_path(self::STORAGE_BASE . '/' . $sessionId);
    }

    /**
     * Get input directory for session
     * 
     * @param string $sessionId
     * @return string
     */
    public function getInputPath($sessionId)
    {
        return storage_path(self::STORAGE_BASE . '/' . $sessionId . '/input');
    }

    /**
     * Get output directory for session
     * 
     * @param string $sessionId
     * @return string
     */
    public function getOutputPath($sessionId)
    {
        return storage_path(self::STORAGE_BASE . '/' . $sessionId . '/output');
    }

    /**
     * Get temporary directory for session
     * 
     * @param string $sessionId
     * @return string
     */
    public function getTempPath($sessionId)
    {
        return storage_path(self::STORAGE_BASE . '/' . $sessionId . '/temp');
    }

    /**
     * Store uploaded file in session
     * 
     * @param object $file Uploaded file object
     * @param string $sessionId Session ID
     * @param string $subdir Subdirectory within session (default: 'input')
     * @return array ['path' => string, 'filename' => string, 'size' => int]
     */
    public function storeFile($file, $sessionId, $subdir = 'input')
    {
        $sessionPath = $this->getSessionPath($sessionId);
        $targetDir = $sessionPath . '/' . $subdir;

        if (!File::exists($targetDir)) {
            File::makeDirectory($targetDir, 0755, true);
        }

        $filename = $file->getClientOriginalName();
        $path = $file->storeAs(
            ltrim(self::STORAGE_BASE . '/' . $sessionId . '/' . $subdir, '/'),
            $filename,
            'local'
        );

        return [
            'path' => storage_path($path),
            'filename' => $filename,
            'size' => $file->getSize(),
            'full_path' => $path
        ];
    }

    /**
     * Store multiple files in session
     * 
     * @param array $files Array of uploaded file objects
     * @param string $sessionId Session ID
     * @return array Array of stored file info
     */
    public function storeMultipleFiles($files, $sessionId)
    {
        $stored = [];
        foreach ($files as $file) {
            $stored[] = $this->storeFile($file, $sessionId);
        }
        return $stored;
    }

    /**
     * Get file from session
     * 
     * @param string $sessionId Session ID
     * @param string $filename Filename
     * @param string $subdir Subdirectory (default: 'output')
     * @return string|null Full path if file exists, null otherwise
     */
    public function getFile($sessionId, $filename, $subdir = 'output')
    {
        $path = storage_path(self::STORAGE_BASE . '/' . $sessionId . '/' . $subdir . '/' . $filename);

        if (File::exists($path)) {
            return $path;
        }

        return null;
    }

    /**
     * Get all files in session subdirectory
     * 
     * @param string $sessionId Session ID
     * @param string $subdir Subdirectory
     * @return array Array of file paths
     */
    public function getFiles($sessionId, $subdir = 'output')
    {
        $path = storage_path(self::STORAGE_BASE . '/' . $sessionId . '/' . $subdir);

        if (!File::exists($path)) {
            return [];
        }

        return File::files($path);
    }

    /**
     * Delete session and all its files
     * 
     * @param string $sessionId Session ID
     * @return bool
     */
    public function deleteSession($sessionId)
    {
        $sessionPath = storage_path(self::STORAGE_BASE . '/' . $sessionId);

        if (File::exists($sessionPath)) {
            return File::deleteDirectory($sessionPath);
        }

        return true;
    }

    /**
     * Delete specific file in session
     * 
     * @param string $sessionId Session ID
     * @param string $filename Filename
     * @param string $subdir Subdirectory
     * @return bool
     */
    public function deleteFile($sessionId, $filename, $subdir = 'output')
    {
        $path = storage_path(self::STORAGE_BASE . '/' . $sessionId . '/' . $subdir . '/' . $filename);

        if (File::exists($path)) {
            return File::delete($path);
        }

        return true;
    }

    /**
     * Check if session exists
     * 
     * @param string $sessionId Session ID
     * @return bool
     */
    public function sessionExists($sessionId)
    {
        return File::exists(storage_path(self::STORAGE_BASE . '/' . $sessionId));
    }

    /**
     * Get session size
     * 
     * @param string $sessionId Session ID
     * @return int Total size in bytes
     */
    public function getSessionSize($sessionId)
    {
        $sessionPath = storage_path(self::STORAGE_BASE . '/' . $sessionId);

        if (!File::exists($sessionPath)) {
            return 0;
        }

        return $this->getDirectorySize($sessionPath);
    }

    /**
     * Calculate directory size
     * 
     * @param string $path Directory path
     * @return int Size in bytes
     */
    private function getDirectorySize($path)
    {
        $size = 0;
        $files = File::allFiles($path);

        foreach ($files as $file) {
            $size += $file->getSize();
        }

        return $size;
    }

    /**
     * Clean up expired sessions
     * 
     * @param int $lifetimeMinutes File lifetime in minutes
     * @return array ['deleted' => int, 'size_freed' => int]
     */
    public function cleanupExpiredSessions($lifetimeMinutes = self::FILE_LIFETIME)
    {
        $basePath = storage_path(self::STORAGE_BASE);
        $now = time();
        $deleted = 0;
        $sizeFreed = 0;

        if (!File::exists($basePath)) {
            return ['deleted' => 0, 'size_freed' => 0];
        }

        $directories = File::directories($basePath);

        foreach ($directories as $dir) {
            $lastModified = filemtime($dir);
            $ageMinutes = ($now - $lastModified) / 60;

            if ($ageMinutes > $lifetimeMinutes) {
                $sizeFreed += $this->getDirectorySize($dir);
                if (File::deleteDirectory($dir)) {
                    $deleted++;
                }
            }
        }

        return ['deleted' => $deleted, 'size_freed' => $sizeFreed];
    }

    /**
     * Get total storage usage
     * 
     * @return int Total size in bytes
     */
    public function getTotalStorageUsage()
    {
        $basePath = storage_path(self::STORAGE_BASE);

        if (!File::exists($basePath)) {
            return 0;
        }

        return $this->getDirectorySize($basePath);
    }

    /**
     * Format bytes to human-readable format
     * 
     * @param int $bytes
     * @param int $precision
     * @return string
     */
    public function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    /**
     * Verify file integrity after processing
     * 
     * @param string $filePath Full file path
     * @return bool
     */
    public function verifyFileIntegrity($filePath)
    {
        if (!File::exists($filePath)) {
            return false;
        }

        // Check file is readable and has size > 0
        return is_readable($filePath) && filesize($filePath) > 0;
    }
}
