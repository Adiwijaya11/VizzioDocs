<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Exception;

/**
 * ProcessingProgressService
 * 
 * Tracks and manages progress for long-running PDF processing operations.
 * Stores progress data in cache for real-time updates via AJAX polling or WebSockets.
 */
class ProcessingProgressService
{
    private const PROGRESS_CACHE_TTL = 3600; // 1 hour
    private const PROGRESS_KEY_PREFIX = 'pdf_processing_progress_';

    /**
     * Initialize progress tracking for a session
     * 
     * @param string $sessionId Session ID
     * @param string $operationType Type of operation (compress, merge, etc.)
     * @param int $totalSteps Total processing steps
     * @return array Initial progress state
     */
    public function initialize($sessionId, $operationType = 'processing', $totalSteps = 100)
    {
        $progressKey = self::PROGRESS_KEY_PREFIX . $sessionId;

        $progressData = [
            'session_id' => $sessionId,
            'operation_type' => $operationType,
            'status' => 'initializing', // initializing, processing, paused, completed, failed
            'current_step' => 0,
            'total_steps' => $totalSteps,
            'percentage' => 0,
            'message' => 'Initializing...',
            'errors' => [],
            'warnings' => [],
            'start_time' => now()->timestamp,
            'end_time' => null,
            'duration_seconds' => 0,
            'file_size' => 0,
            'processed_size' => 0,
            'speed_mbps' => 0,
            'eta_seconds' => 0,
            'metadata' => []
        ];

        Cache::put($progressKey, $progressData, now()->addSeconds(self::PROGRESS_CACHE_TTL));

        return $progressData;
    }

    /**
     * Update progress for a session
     * 
     * @param string $sessionId Session ID
     * @param array $updates Array of fields to update
     * @return array Updated progress state
     */
    public function update($sessionId, $updates = [])
    {
        $progressKey = self::PROGRESS_KEY_PREFIX . $sessionId;
        $current = Cache::get($progressKey) ?? [];

        // Merge updates
        $updated = array_merge($current, $updates);

        // Recalculate percentage
        if (isset($updates['current_step']) && isset($updated['total_steps'])) {
            $updated['percentage'] = (int) (($updated['current_step'] / $updated['total_steps']) * 100);
        }

        // Calculate speed and ETA
        if (isset($updates['processed_size']) && $updated['start_time']) {
            $elapsed = time() - $updated['start_time'];
            if ($elapsed > 0) {
                $updated['speed_mbps'] = round(($updated['processed_size'] / 1024 / 1024) / $elapsed, 2);
                
                if ($updated['speed_mbps'] > 0 && $updated['file_size'] > 0) {
                    $remaining = $updated['file_size'] - $updated['processed_size'];
                    $updated['eta_seconds'] = (int) (($remaining / 1024 / 1024) / $updated['speed_mbps']);
                }
            }
        }

        // Auto-set to 'processing' if not already set
        if (!isset($updated['status']) || $updated['status'] === 'initializing') {
            $updated['status'] = 'processing';
        }

        Cache::put($progressKey, $updated, now()->addSeconds(self::PROGRESS_CACHE_TTL));

        return $updated;
    }

    /**
     * Set progress to a specific step
     * 
     * @param string $sessionId Session ID
     * @param int $step Current step number
     * @param string $message Progress message
     * @return array Updated progress state
     */
    public function setStep($sessionId, $step, $message = '')
    {
        return $this->update($sessionId, [
            'current_step' => $step,
            'message' => $message
        ]);
    }

    /**
     * Increment progress step
     * 
     * @param string $sessionId Session ID
     * @param int $increment How many steps to increment (default: 1)
     * @param string|null $message Progress message
     * @return array Updated progress state
     */
    public function increment($sessionId, $increment = 1, $message = null)
    {
        $progressKey = self::PROGRESS_KEY_PREFIX . $sessionId;
        $current = Cache::get($progressKey) ?? [];

        $updates = [
            'current_step' => ($current['current_step'] ?? 0) + $increment
        ];

        if ($message !== null) {
            $updates['message'] = $message;
        }

        return $this->update($sessionId, $updates);
    }

    /**
     * Mark processing as completed
     * 
     * @param string $sessionId Session ID
     * @param string $message Completion message
     * @param array $metadata Additional metadata
     * @return array Final progress state
     */
    public function complete($sessionId, $message = 'Processing completed successfully', $metadata = [])
    {
        $progressKey = self::PROGRESS_KEY_PREFIX . $sessionId;
        $current = Cache::get($progressKey) ?? [];

        $updates = [
            'status' => 'completed',
            'percentage' => 100,
            'current_step' => $current['total_steps'] ?? 100,
            'message' => $message,
            'end_time' => now()->timestamp,
            'duration_seconds' => (now()->timestamp) - ($current['start_time'] ?? now()->timestamp)
        ];

        if (!empty($metadata)) {
            $updates['metadata'] = array_merge($current['metadata'] ?? [], $metadata);
        }

        return $this->update($sessionId, $updates);
    }

    /**
     * Mark processing as failed
     * 
     * @param string $sessionId Session ID
     * @param string $errorMessage Error message
     * @param array $errors Array of error details
     * @return array Final progress state
     */
    public function fail($sessionId, $errorMessage = 'Processing failed', $errors = [])
    {
        $progressKey = self::PROGRESS_KEY_PREFIX . $sessionId;
        $current = Cache::get($progressKey) ?? [];

        $updates = [
            'status' => 'failed',
            'message' => $errorMessage,
            'errors' => $errors,
            'end_time' => now()->timestamp,
            'duration_seconds' => (now()->timestamp) - ($current['start_time'] ?? now()->timestamp)
        ];

        return $this->update($sessionId, $updates);
    }

    /**
     * Add warning message
     * 
     * @param string $sessionId Session ID
     * @param string $warning Warning message
     * @return array Updated progress state
     */
    public function addWarning($sessionId, $warning)
    {
        $progressKey = self::PROGRESS_KEY_PREFIX . $sessionId;
        $current = Cache::get($progressKey) ?? [];

        $warnings = $current['warnings'] ?? [];
        $warnings[] = [
            'message' => $warning,
            'timestamp' => now()->timestamp
        ];

        return $this->update($sessionId, [
            'warnings' => $warnings
        ]);
    }

    /**
     * Add error message
     * 
     * @param string $sessionId Session ID
     * @param string $error Error message
     * @return array Updated progress state
     */
    public function addError($sessionId, $error)
    {
        $progressKey = self::PROGRESS_KEY_PREFIX . $sessionId;
        $current = Cache::get($progressKey) ?? [];

        $errors = $current['errors'] ?? [];
        $errors[] = [
            'message' => $error,
            'timestamp' => now()->timestamp
        ];

        return $this->update($sessionId, [
            'errors' => $errors
        ]);
    }

    /**
     * Get current progress
     * 
     * @param string $sessionId Session ID
     * @return array|null Progress data or null if not found
     */
    public function get($sessionId)
    {
        $progressKey = self::PROGRESS_KEY_PREFIX . $sessionId;
        return Cache::get($progressKey);
    }

    /**
     * Get progress as JSON
     * 
     * @param string $sessionId Session ID
     * @return string JSON progress data
     */
    public function getJson($sessionId)
    {
        return json_encode($this->get($sessionId));
    }

    /**
     * Delete progress tracking
     * 
     * @param string $sessionId Session ID
     * @return bool
     */
    public function delete($sessionId)
    {
        $progressKey = self::PROGRESS_KEY_PREFIX . $sessionId;
        Cache::forget($progressKey);
        return true;
    }

    /**
     * Check if processing is complete
     * 
     * @param string $sessionId Session ID
     * @return bool
     */
    public function isComplete($sessionId)
    {
        $progress = $this->get($sessionId);
        return $progress && in_array($progress['status'], ['completed', 'failed']);
    }

    /**
     * Check if processing is in progress
     * 
     * @param string $sessionId Session ID
     * @return bool
     */
    public function isProcessing($sessionId)
    {
        $progress = $this->get($sessionId);
        return $progress && $progress['status'] === 'processing';
    }

    /**
     * Format progress for API response
     * 
     * @param string $sessionId Session ID
     * @return array Formatted progress response
     */
    public function formatResponse($sessionId)
    {
        $progress = $this->get($sessionId);

        if (!$progress) {
            return [
                'success' => false,
                'message' => 'Progress data not found',
                'data' => null
            ];
        }

        return [
            'success' => true,
            'data' => [
                'status' => $progress['status'],
                'percentage' => $progress['percentage'],
                'message' => $progress['message'],
                'current_step' => $progress['current_step'],
                'total_steps' => $progress['total_steps'],
                'speed_mbps' => $progress['speed_mbps'],
                'eta_seconds' => $progress['eta_seconds'],
                'errors' => $progress['errors'],
                'warnings' => $progress['warnings']
            ]
        ];
    }

    /**
     * Simulate progress for testing (development only)
     * 
     * @param string $sessionId Session ID
     * @param int $steps Number of steps to simulate
     * @return void
     */
    public function simulateProgress($sessionId, $steps = 10)
    {
        $this->initialize($sessionId, 'test', $steps);

        for ($i = 1; $i <= $steps; $i++) {
            $this->increment($sessionId, 1, "Step $i of $steps");
            usleep(100000); // 100ms
        }

        $this->complete($sessionId);
    }
}
