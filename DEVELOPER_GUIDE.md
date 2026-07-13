# VizzioDocs - Phase 1 Foundation Developer Guide

## Quick Reference

This guide helps developers build new PDF tools using the Phase 1 foundation infrastructure.

---

## 📁 File Locations

| Component | Path |
|-----------|------|
| Services | `app/Services/` |
| Components | `resources/views/components/` |
| JavaScript | `resources/js/pdf-processing-utils.js` |
| Controllers | `app/Http/Controllers/` |
| Routes | `routes/web.php` |

---

## 🔧 Building a New Tool (Step-by-Step)

### Step 1: Create the Controller

Create a new controller in `app/Http/Controllers/YourToolController.php`:

```php
<?php

namespace App\Http\Controllers;

use App\Services\PdfProcessingService;
use Illuminate\Http\Request;

class RemovePagesController extends PdfProcessingService
{
    /**
     * Show the tool page
     */
    public function index()
    {
        return view('tools.remove-pages');
    }

    /**
     * Process the PDF
     */
    public function process(Request $request)
    {
        try {
            // Step 1: Initialize session with operation metadata
            $this->initializeSession('remove-pages', [
                'total_steps' => 100,
                'filename' => $request->file('file')->getClientOriginalName()
            ]);

            // Step 2: Validate the uploaded file
            $validation = $this->validateFile($request->file('file'));
            if (!$validation['valid']) {
                return $this->handleException(
                    new \Exception($validation['errors'][0])
                );
            }

            // Step 3: Store the input file
            $inputFile = $this->storeInputFile($request->file('file'));
            $this->reportProgress(25, 'Menganalisis PDF...');

            // Step 4: Extract pages to remove from request
            $pagesToRemove = explode(',', $request->input('pages', ''));
            $pagesToRemove = array_map('intval', array_filter($pagesToRemove));

            // Step 5: Get total pages in PDF
            $totalPages = $this->getPdfPageCount($inputFile);
            if (empty($pagesToRemove) || count($pagesToRemove) >= $totalPages) {
                return $this->handleException(
                    new \Exception('Invalid page selection')
                );
            }

            // Step 6: Process (remove pages)
            $this->reportProgress(50, 'Menghapus halaman...');
            $outputFile = $this->getOutputPath('pdf');
            $this->removePagesFromPdf($inputFile, $outputFile, $pagesToRemove);

            // Step 7: Verify output
            $this->reportProgress(75, 'Memverifikasi...');
            if (!file_exists($outputFile) || filesize($outputFile) === 0) {
                return $this->handleException(
                    new \Exception('Processing failed to generate output')
                );
            }

            // Step 8: Complete successfully
            $this->reportProgress(100, 'Selesai!');
            return $this->markComplete('PDF berhasil diproses!', [
                'output_file' => $outputFile,
                'original_pages' => $totalPages,
                'remaining_pages' => $totalPages - count($pagesToRemove),
                'pages_removed' => count($pagesToRemove)
            ]);

        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Helper: Remove specific pages from PDF
     */
    private function removePagesFromPdf($input, $output, $pagesList)
    {
        // Example using imagick or other PDF library
        // This is pseudocode - adapt to your PDF library
        
        $command = sprintf(
            'pdftk %s cat %s output %s',
            escapeshellarg($input),
            $this->buildPageSpec($pagesList), // "1-5 7-end" (exclude 6)
            escapeshellarg($output)
        );

        shell_exec($command);
    }

    /**
     * Helper: Build page specification
     */
    private function buildPageSpec($pagesToRemove)
    {
        $totalPages = $this->getPdfPageCount($this->getInputPath());
        $keepPages = [];

        for ($i = 1; $i <= $totalPages; $i++) {
            if (!in_array($i, $pagesToRemove)) {
                $keepPages[] = $i;
            }
        }

        // Convert to range format: "1-5 7-10 12"
        return $this->pagesToRanges($keepPages);
    }
}
```

### Step 2: Create the Blade View

Create `resources/views/tools/remove-pages.blade.php`:

```blade
@extends('layouts.app')

@section('title', 'Hapus Halaman PDF')

@section('content')
<div x-data="pdfRemovePages()" @load="init()" class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900">
    <!-- Hero Section -->
    <section class="relative pt-20 pb-10 px-4">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-4xl font-bold text-white mb-3">
                Hapus Halaman PDF
            </h1>
            <p class="text-white/70 text-lg">
                Pilih halaman yang ingin dihapus dari PDF Anda
            </p>
        </div>
    </section>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 pb-20">
        <!-- Processing Status (Hidden until upload) -->
        <div x-show="isProcessing" x-transition class="mb-8 rounded-2xl bg-white/5 p-8 border border-white/10">
            <div class="mb-6">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-white font-medium">Progres Pemrosesan</span>
                    <span class="text-white/70" x-text="`${progress}%`"></span>
                </div>
                @include('components.progress-bar')
            </div>
            <p class="text-white/70 text-center" x-text="statusMessage"></p>
        </div>

        <!-- Upload Zone -->
        <div x-show="!isProcessing" x-transition
            class="mb-8 rounded-2xl border-2 border-dashed border-white/20 p-12 text-center cursor-pointer hover:border-white/40 transition"
            @dragover="isDragging = true"
            @dragleave="isDragging = false"
            @drop="handleDrop">
            
            <input type="file" class="hidden" @change="handleFileSelect" accept=".pdf" id="pdfInput">
            
            <svg class="w-16 h-16 mx-auto mb-4 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            
            <h3 class="text-xl font-semibold text-white mb-2">Unggah PDF Anda</h3>
            <p class="text-white/60">Seret dan lepas file atau klik untuk memilih</p>
            
            <button @click="$refs.fileInput.click()" class="mt-4 px-6 py-2 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-lg hover:shadow-lg transition">
                Pilih File
            </button>
        </div>

        <!-- Notifications -->
        @include('components.notifications')
    </div>
</div>

<script>
function pdfRemovePages() {
    return {
        file: null,
        isDragging: false,
        isProcessing: false,
        progress: 0,
        statusMessage: '',
        sessionId: null,
        pdfManager: null,

        async init() {
            // Initialize
        },

        handleDrop(e) {
            e.preventDefault();
            this.isDragging = false;
            if (e.dataTransfer.files.length > 0) {
                this.file = e.dataTransfer.files[0];
                this.uploadFile();
            }
        },

        handleFileSelect(e) {
            if (e.target.files.length > 0) {
                this.file = e.target.files[0];
                this.uploadFile();
            }
        },

        async uploadFile() {
            // Validate
            if (!this.file || this.file.type !== 'application/pdf') {
                alert('Pilih file PDF yang valid');
                return;
            }

            if (this.file.size > 30 * 1024 * 1024) {
                alert('File terlalu besar (max 30MB)');
                return;
            }

            // Upload
            this.isProcessing = true;
            const formData = new FormData();
            formData.append('file', this.file);

            try {
                const response = await fetch('/hapus-halaman', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const result = await response.json();
                if (result.success) {
                    this.sessionId = result.data.session_id;
                    this.startPolling();
                } else {
                    this.isProcessing = false;
                    alert(result.message);
                }
            } catch (error) {
                this.isProcessing = false;
                alert('Upload gagal');
            }
        },

        startPolling() {
            this.pdfManager = new PdfProcessingManager(this.sessionId);
            this.pdfManager.startProgressPolling(
                (p) => { this.progress = p.percentage; this.statusMessage = p.message; },
                (p) => { this.progress = 100; this.statusMessage = 'Selesai!'; },
                (e) => { this.isProcessing = false; alert(e); }
            );
        }
    };
}
</script>
@endsection
```

### Step 3: Add Route

Add to `routes/web.php`:

```php
Route::get('/hapus-halaman', [RemovePagesController::class, 'index'])->name('remove-pages');
Route::post('/hapus-halaman', [RemovePagesController::class, 'process']);
```

---

## 📚 Available Base Class Methods

### In `PdfProcessingService`

#### Session Management
```php
// Initialize session with metadata
$this->initializeSession($operationType, $metadata = []);

// Get session ID
$sessionId = $this->getSessionId();

// Get stored metadata
$metadata = $this->getSessionMetadata();
```

#### File Operations
```php
// Validate file
$result = $this->validateFile($file);

// Store input file, returns path
$inputPath = $this->storeInputFile($file);

// Get input file path
$path = $this->getInputPath();

// Get output file path
$path = $this->getOutputPath($extension);
```

#### Progress Reporting
```php
// Report progress (0-100)
$this->reportProgress($percentage, $message);

// Mark complete
$response = $this->markComplete($message, $data = []);

// Handle exceptions
$response = $this->handleException($exception);
```

#### PDF Utilities
```php
// Get page count
$count = $this->getPdfPageCount($filePath);

// Check if PDF is valid
$valid = $this->isPdfValid($filePath);
```

---

## 🎨 Available Frontend Components

### progress-bar
```blade
@include('components.progress-bar')
<!-- Requires Alpine.js variable: progress (0-100) -->
```

### notifications
```blade
@include('components.notifications')
<!-- Provides: notify.success(), notify.error(), notify.warning(), notify.info() -->
```

### pdf-upload
```blade
@include('components.pdf-upload')
<!-- Pre-built upload component with drag & drop -->
```

---

## 🔌 JavaScript API

### PdfProcessingManager

```javascript
// Create instance
const manager = new PdfProcessingManager(sessionId);

// Start polling
manager.startProgressPolling(
    (progress) => {}, // onProgress callback
    (progress) => {}, // onComplete callback
    (error) => {}     // onError callback
);

// Stop polling
manager.stopProgressPolling();

// Cleanup
await manager.cleanupSession();

// Static helpers
PdfProcessingManager.formatBytes(1024)        // "1 KB"
PdfProcessingManager.formatTime(120)          // "2m"
```

### Alpine Components

```javascript
// Upload component
x-data="pdfUpload()"

// Status component
x-data="processingStatus()"
```

---

## 🧪 Testing Checklist

Before submitting a new tool:

- [ ] File upload validation works
- [ ] Progress updates in real-time
- [ ] Output file is generated correctly
- [ ] Session cleanup works
- [ ] Error handling displays properly
- [ ] Mobile responsive
- [ ] Tool name appears in navigation
- [ ] Tool icon displays correctly

---

## 📋 Common Patterns

### Pattern 1: Multi-Step Processing

```php
// Step 1: Parse
$this->reportProgress(25, 'Analyzing...');

// Step 2: Process
$this->reportProgress(50, 'Processing...');

// Step 3: Optimize
$this->reportProgress(75, 'Optimizing...');

// Step 4: Complete
$this->reportProgress(100, 'Complete!');
```

### Pattern 2: Error Recovery

```php
try {
    $this->validateFile($file);
} catch (\Exception $e) {
    return $this->handleException($e);
}
```

### Pattern 3: Output Verification

```php
$output = $this->getOutputPath('pdf');
if (!file_exists($output) || filesize($output) === 0) {
    return $this->handleException(
        new \Exception('Output generation failed')
    );
}
```

---

## 🚀 Performance Tips

1. **Batch Operations:** Process files in batches for large PDFs
2. **Progress Intervals:** Update progress every 500ms-1s
3. **Caching:** Cache intermediate results
4. **Cleanup:** Always cleanup temp files after processing
5. **Timeouts:** Set reasonable timeouts for operations

---

## 🔐 Security Checklist

- [ ] File type validation (MIME + extension)
- [ ] File size limits enforced
- [ ] CSRF token on all POST requests
- [ ] Input sanitization
- [ ] Safe file storage (outside web root)
- [ ] Session isolation
- [ ] Automatic cleanup

---

## 📞 Support Resources

- Base class: `app/Services/PdfProcessingService.php`
- Validation: `app/Services/FileValidationService.php`
- Storage: `app/Services/FileStorageService.php`
- Progress: `app/Services/ProcessingProgressService.php`
- JavaScript: `resources/js/pdf-processing-utils.js`

