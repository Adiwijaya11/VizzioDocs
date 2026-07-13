<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Exception;

/**
 * PdfProcessingService
 * 
 * Base class for all PDF processing operations.
 * Provides common functionality, error handling, and progress tracking.
 * All new PDF tool services should extend this class.
 */
class PdfProcessingService
{
    protected $fileValidation;
    protected $fileStorage;
    protected $progress;

    protected $sessionId;
    protected $operationType;
    protected $config = [];

    public function __construct()
    {
        $this->fileValidation = new FileValidationService();
        $this->fileStorage = new FileStorageService();
        $this->progress = new ProcessingProgressService();
    }

    /**
     * Initialize a new processing session
     * 
     * @param string $operationType Type of operation (e.g., 'compress', 'merge')
     * @param array $config Configuration for the operation
     * @return string Session ID
     */
    public function initializeSession($operationType, $config = [])
    {
        $this->sessionId = $this->fileStorage->createSession();
        $this->operationType = $operationType;
        $this->config = array_merge($this->getDefaultConfig(), $config);

        // Initialize progress tracking
        $this->progress->initialize(
            $this->sessionId,
            $operationType,
            $this->config['total_steps'] ?? 100
        );

        return $this->sessionId;
    }

    /**
     * Get default configuration
     * Override this in subclasses
     * 
     * @return array
     */
    protected function getDefaultConfig()
    {
        return [
            'total_steps' => 100,
            'cleanup_on_error' => true,
            'verify_output' => true
        ];
    }

    /**
     * Get current session ID
     * 
     * @return string|null
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }

    /**
     * Get operation type
     * 
     * @return string|null
     */
    public function getOperationType()
    {
        return $this->operationType;
    }

    /**
     * Report progress
     * 
     * @param int $step Current step
     * @param string $message Progress message
     * @return array Updated progress
     */
    protected function reportProgress($step, $message = '')
    {
        if (!$this->sessionId) {
            return [];
        }

        return $this->progress->setStep($this->sessionId, $step, $message);
    }

    /**
     * Report error
     * 
     * @param string $error Error message
     * @return array Updated progress
     */
    protected function reportError($error)
    {
        if (!$this->sessionId) {
            return [];
        }

        return $this->progress->addError($this->sessionId, $error);
    }

    /**
     * Report warning
     * 
     * @param string $warning Warning message
     * @return array Updated progress
     */
    protected function reportWarning($warning)
    {
        if (!$this->sessionId) {
            return [];
        }

        return $this->progress->addWarning($this->sessionId, $warning);
    }

    /**
     * Mark processing as complete
     * 
     * @param string $message Completion message
     * @param array $metadata Additional metadata
     * @return array Final progress
     */
    protected function markComplete($message = 'Completed successfully', $metadata = [])
    {
        if (!$this->sessionId) {
            return [];
        }

        return $this->progress->complete($this->sessionId, $message, $metadata);
    }

    /**
     * Mark processing as failed
     * 
     * @param string $errorMessage Error message
     * @param array $errors Array of error details
     * @return array Final progress
     */
    protected function markFailed($errorMessage, $errors = [])
    {
        if (!$this->sessionId) {
            return [];
        }

        if ($this->config['cleanup_on_error']) {
            $this->cleanup();
        }

        return $this->progress->fail($this->sessionId, $errorMessage, $errors);
    }

    /**
     * Get input file path
     * 
     * @param string $filename Filename
     * @return string|null
     */
    protected function getInputFile($filename)
    {
        if (!$this->sessionId) {
            throw new Exception('Session not initialized');
        }

        return $this->fileStorage->getFile($this->sessionId, $filename, 'input');
    }

    /**
     * Get output file path
     * 
     * @param string $filename Filename
     * @return string|null
     */
    protected function getOutputFile($filename)
    {
        if (!$this->sessionId) {
            throw new Exception('Session not initialized');
        }

        return $this->fileStorage->getFile($this->sessionId, $filename, 'output');
    }

    /**
     * Get temporary file path
     * 
     * @param string $filename Filename
     * @return string Full path
     */
    protected function getTempFile($filename = '')
    {
        if (!$this->sessionId) {
            throw new Exception('Session not initialized');
        }

        $tempDir = $this->fileStorage->getTempPath($this->sessionId);
        
        if ($filename) {
            return $tempDir . '/' . $filename;
        }

        return $tempDir;
    }

    /**
     * Store input file
     * 
     * @param object $file Uploaded file
     * @return array Stored file info
     */
    protected function storeInputFile($file)
    {
        if (!$this->sessionId) {
            throw new Exception('Session not initialized');
        }

        return $this->fileStorage->storeFile($file, $this->sessionId, 'input');
    }

    /**
     * Store output file
     * 
     * @param string $sourcePath Source file path
     * @param string $filename Output filename
     * @return array Stored file info
     */
    protected function storeOutputFile($sourcePath, $filename)
    {
        if (!$this->sessionId) {
            throw new Exception('Session not initialized');
        }

        $outputDir = $this->fileStorage->getOutputPath($this->sessionId);
        $destinationPath = $outputDir . '/' . $filename;

        if (!File::copy($sourcePath, $destinationPath)) {
            throw new Exception('Failed to copy output file');
        }

        return [
            'path' => $destinationPath,
            'filename' => $filename,
            'size' => filesize($destinationPath)
        ];
    }

    /**
     * Generate unique filename
     * 
     * @param string $prefix Filename prefix
     * @param string $extension File extension
     * @return string
     */
    protected function generateFilename($prefix = 'output', $extension = 'pdf')
    {
        return $prefix . '_' . Str::random(8) . '.' . $extension;
    }

    /**
     * Cleanup session files
     * 
     * @return bool
     */
    public function cleanup()
    {
        if (!$this->sessionId) {
            return true;
        }

        return $this->fileStorage->deleteSession($this->sessionId);
    }

    /**
     * Verify output file
     * 
     * @param string $outputPath Path to output file
     * @return bool
     */
    protected function verifyOutput($outputPath)
    {
        return $this->fileStorage->verifyFileIntegrity($outputPath);
    }

    /**
     * Get operation summary
     * 
     * @return array
     */
    public function getSummary()
    {
        if (!$this->sessionId) {
            return [];
        }

        $progress = $this->progress->get($this->sessionId);
        $sessionSize = $this->fileStorage->getSessionSize($this->sessionId);

        return [
            'session_id' => $this->sessionId,
            'operation_type' => $this->operationType,
            'status' => $progress['status'] ?? 'unknown',
            'duration_seconds' => $progress['duration_seconds'] ?? 0,
            'session_size_mb' => round($sessionSize / 1024 / 1024, 2),
            'errors' => $progress['errors'] ?? [],
            'warnings' => $progress['warnings'] ?? []
        ];
    }

    /**
     * Format bytes to human-readable size
     * 
     * @param int $bytes
     * @return string
     */
    protected function formatBytes($bytes)
    {
        return $this->fileStorage->formatBytes($bytes);
    }

    /**
     * Validate file before processing
     * 
     * @param object $file Uploaded file
     * @param array $allowedMimes Allowed MIME types
     * @param int|null $maxSize Maximum file size
     * @return array Validation result
     */
    protected function validateFile($file, $allowedMimes = ['pdf'], $maxSize = null)
    {
        return $this->fileValidation->validate($file, $allowedMimes, $maxSize);
    }

    /**
     * Validate multiple files
     * 
     * @param array $files Array of uploaded files
     * @param array $allowedMimes Allowed MIME types
     * @return array Validation results
     */
    protected function validateFiles($files, $allowedMimes = ['pdf'])
    {
        return $this->fileValidation->validateMultiple($files, $allowedMimes);
    }

    /**
     * Handle exception with logging
     * 
     * @param Exception $exception
     * @param bool $cleanup Whether to cleanup on error
     * @return array Error response
     */
    protected function handleException($exception, $cleanup = true)
    {
        $errorMessage = $exception->getMessage();
        $this->reportError($errorMessage);

        if ($cleanup) {
            $this->cleanup();
        }

        return [
            'success' => false,
            'message' => $errorMessage,
            'session_id' => $this->sessionId
        ];
    }

    /**
     * Execute system command with error handling
     * 
     * @param string $command Command to execute
     * @return array ['success' => bool, 'output' => string, 'return_code' => int]
     */
    protected function executeCommand($command)
    {
        $output = [];
        $returnCode = 0;

        try {
            exec($command, $output, $returnCode);

            return [
                'success' => $returnCode === 0,
                'output' => implode("\n", $output),
                'return_code' => $returnCode
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'output' => $e->getMessage(),
                'return_code' => -1
            ];
        }
    }
}
