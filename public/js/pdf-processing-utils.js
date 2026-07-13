/**
 * VizzioDocs - Shared PDF Processing Utilities
 * 
 * This module provides common functions for all PDF processing tools,
 * including progress tracking, file handling, and notifications.
 */

class PdfProcessingManager {
    constructor(sessionId = null) {
        this.sessionId = sessionId;
        this.progressCheckInterval = 500; // 500ms
        this.progressPollerId = null;
        this.isProcessing = false;
    }

    /**
     * Initialize a processing session
     * @param {string} operationType - Type of operation
     * @returns {Promise<string>} Session ID
     */
    async initSession(operationType) {
        try {
            // This would be called from the controller
            // Returns the session ID from the backend
            return this.sessionId;
        } catch (error) {
            console.error('Failed to initialize session:', error);
            throw error;
        }
    }

    /**
     * Poll progress from backend
     * @returns {Promise<object>} Progress data
     */
    async pollProgress() {
        if (!this.sessionId) return null;

        try {
            const response = await fetch(`/api/progress/${this.sessionId}`);
            const data = await response.json();
            return data.data || null;
        } catch (error) {
            console.error('Failed to fetch progress:', error);
            return null;
        }
    }

    /**
     * Start polling for progress updates
     * @param {function} onProgress - Callback for progress updates
     * @param {function} onComplete - Callback when complete
     * @param {function} onError - Callback on error
     */
    startProgressPolling(onProgress, onComplete, onError) {
        if (this.progressPollerId) {
            clearInterval(this.progressPollerId);
        }

        this.isProcessing = true;
        this.progressPollerId = setInterval(async () => {
            const progress = await this.pollProgress();

            if (!progress) {
                onError?.('Lost connection to server');
                this.stopProgressPolling();
                return;
            }

            onProgress?.(progress);

            if (progress.status === 'completed') {
                onComplete?.(progress);
                this.stopProgressPolling();
            } else if (progress.status === 'failed') {
                onError?.(progress.message || 'Processing failed');
                this.stopProgressPolling();
            }
        }, this.progressCheckInterval);
    }

    /**
     * Stop polling for progress
     */
    stopProgressPolling() {
        if (this.progressPollerId) {
            clearInterval(this.progressPollerId);
            this.progressPollerId = null;
        }
        this.isProcessing = false;
    }

    /**
     * Cleanup session on backend
     * @returns {Promise<boolean>}
     */
    async cleanupSession() {
        if (!this.sessionId) return true;

        try {
            const response = await fetch(`/api/session/${this.sessionId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                    'Content-Type': 'application/json'
                }
            });
            return response.ok;
        } catch (error) {
            console.error('Failed to cleanup session:', error);
            return false;
        }
    }

    /**
     * Format bytes to human-readable size
     * @param {number} bytes
     * @returns {string}
     */
    static formatBytes(bytes) {
        if (bytes === 0) return '0 Bytes';

        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));

        return Math.round((bytes / Math.pow(k, i)) * 100) / 100 + ' ' + sizes[i];
    }

    /**
     * Format seconds to human-readable time
     * @param {number} seconds
     * @returns {string}
     */
    static formatTime(seconds) {
        if (seconds < 60) return Math.round(seconds) + 's';
        if (seconds < 3600) return Math.round(seconds / 60) + 'm';
        return Math.round(seconds / 3600) + 'h';
    }
}

/**
 * Global Alpine component for PDF upload
 */
function pdfUpload() {
    return {
        file: null,
        isDragging: false,
        isLoading: false,
        uploaded: false,
        loadingText: 'Memproses PDF Anda...',
        sessionId: null,
        pdfManager: null,

        handleDrop(event) {
            this.isDragging = false;
            const files = event.dataTransfer.files;
            if (files.length > 0) {
                this.file = files[0];
                this.processFile();
            }
        },

        handleFileChange(event) {
            const files = event.target.files;
            if (files.length > 0) {
                this.file = files[0];
                this.processFile();
            }
        },

        processFile() {
            // Validate file
            if (!this.file || this.file.type !== 'application/pdf') {
                alert('Please select a valid PDF file');
                return;
            }

            // Check file size (30MB max)
            const maxSize = 30 * 1024 * 1024;
            if (this.file.size > maxSize) {
                alert('File size exceeds 30MB limit');
                return;
            }

            this.isLoading = true;
            this.loadingText = 'Memproses PDF Anda...';

            // Create form data and submit to server
            const formData = new FormData();
            formData.append('file', this.file);

            // This will be implemented by specific tool
            this.uploadFile(formData);
        },

        uploadFile(formData) {
            // Override this in specific tool implementations
            console.log('Upload method not implemented');
        }
    };
}

/**
 * Global Alpine component for processing status
 */
function processingStatus() {
    return {
        progress: 0,
        status: 'idle', // idle, processing, completed, failed
        message: '',
        errors: [],
        sessionId: null,
        pdfManager: null,

        startPolling(sessionId) {
            this.sessionId = sessionId;
            this.pdfManager = new PdfProcessingManager(sessionId);

            this.pdfManager.startProgressPolling(
                (progress) => this.onProgress(progress),
                (progress) => this.onComplete(progress),
                (error) => this.onError(error)
            );
        },

        onProgress(progress) {
            this.progress = progress.percentage || 0;
            this.message = progress.message || '';
            this.status = 'processing';
        },

        onComplete(progress) {
            this.progress = 100;
            this.status = 'completed';
            this.message = progress.message || 'Processing completed successfully';
        },

        onError(error) {
            this.status = 'failed';
            this.message = error;
            this.errors = [error];
        },

        stopPolling() {
            if (this.pdfManager) {
                this.pdfManager.stopProgressPolling();
            }
        },

        async cleanup() {
            if (this.pdfManager) {
                await this.pdfManager.cleanupSession();
            }
        }
    };
}

/**
 * Utility function to show notifications (requires notifications component)
 * @param {function} notifyFunc - Alpine notify function
 * @param {string} type - 'success', 'error', 'warning', 'info'
 * @param {string} title - Notification title
 * @param {string} message - Notification message
 */
function showNotification(notifyFunc, type, title, message) {
    if (notifyFunc) {
        notifyFunc[type](title, message);
    } else {
        console.log(`[${type.toUpperCase()}] ${title}: ${message}`);
    }
}

/**
 * Fetch with CSRF token automatically included
 * @param {string} url
 * @param {object} options
 * @returns {Promise<Response>}
 */
async function fetchWithCsrf(url, options = {}) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    
    const headers = {
        ...options.headers,
        'X-CSRF-TOKEN': csrfToken
    };

    return fetch(url, {
        ...options,
        headers
    });
}

/**
 * Format file size from bytes
 * @param {number} bytes
 * @returns {string}
 */
function formatFileSize(bytes) {
    return PdfProcessingManager.formatBytes(bytes);
}

/**
 * Format time from seconds
 * @param {number} seconds
 * @returns {string}
 */
function formatTime(seconds) {
    return PdfProcessingManager.formatTime(seconds);
}

/**
 * Calculate compression ratio
 * @param {number} originalSize
 * @param {number} compressedSize
 * @returns {number} Percentage reduction
 */
function calculateCompressionRatio(originalSize, compressedSize) {
    if (originalSize === 0) return 0;
    return Math.round((1 - (compressedSize / originalSize)) * 100);
}

/**
 * Estimate processing time based on file size
 * @param {number} fileSizeMB
 * @returns {number} Estimated time in seconds
 */
function estimateProcessingTime(fileSizeMB) {
    // Rough estimate: 1 second per MB for standard operations
    // Can be overridden per tool
    return Math.max(2, fileSizeMB * 1);
}

// Export for use in other files
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        PdfProcessingManager,
        pdfUpload,
        processingStatus,
        showNotification,
        fetchWithCsrf,
        formatFileSize,
        formatTime,
        calculateCompressionRatio,
        estimateProcessingTime
    };
}
