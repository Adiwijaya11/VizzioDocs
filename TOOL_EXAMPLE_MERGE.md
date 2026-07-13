# Contoh Integrasi: Merge PDF Tool

Berikut adalah contoh konkret bagaimana mengintegrasikan Phase 1 foundation ke satu tool (Merge PDF).

## 📋 File yang Perlu Dibuat/Diubah

### 1. Controller: `app/Http/Controllers/MergePdfController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Services\PdfProcessingService;
use Illuminate\Http\Request;

class MergePdfController extends PdfProcessingService
{
    /**
     * Show merge tool page
     */
    public function index()
    {
        return view('tools.merge-pdf');
    }

    /**
     * Process PDF merge
     */
    public function process(Request $request)
    {
        try {
            // Initialize session
            $this->initializeSession('merge-pdf', [
                'total_files' => count($request->file('files', [])),
                'operation' => 'merge'
            ]);

            // Validate all files
            $files = $request->file('files', []);
            if (empty($files)) {
                return $this->handleException(
                    new \Exception('Pilih minimal 2 file PDF')
                );
            }

            // Store all input files
            $storedFiles = [];
            foreach ($files as $index => $file) {
                $validation = $this->validateFile($file);
                if (!$validation['valid']) {
                    return $this->handleException(
                        new \Exception($validation['errors'][0])
                    );
                }
                $storedFiles[] = $this->storeInputFile($file);
                $this->reportProgress(
                    25 + ($index / count($files)) * 20,
                    "Validating: {$file->getClientOriginalName()}"
                );
            }

            // Merge PDFs
            $this->reportProgress(50, 'Merging PDFs...');
            $outputFile = $this->getOutputPath('pdf');
            
            // Use your PDF library to merge
            $this->mergePdfFiles($storedFiles, $outputFile);

            // Verify output
            $this->reportProgress(75, 'Verifying output...');
            if (!file_exists($outputFile) || filesize($outputFile) === 0) {
                return $this->handleException(
                    new \Exception('Failed to merge PDFs')
                );
            }

            // Complete
            $this->reportProgress(100, 'Complete!');
            return $this->markComplete('PDFs berhasil digabungkan!', [
                'output_file' => $outputFile,
                'file_count' => count($files),
                'output_size' => filesize($outputFile)
            ]);

        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Helper to merge PDF files
     */
    private function mergePdfFiles($files, $output)
    {
        // Implementation using your PDF library
        // Example: TCPDF, FPDF, or system command (pdftk, ghostscript)
    }
}
```

### 2. View: `resources/views/tools/merge-pdf.blade.php`

```blade
@extends('layouts.app')

@section('title', 'Gabungkan PDF - VizzioDocs')

@section('content')
<div x-data="mergePdfTool()" @load="init()" class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900">
    {{-- Hero Section --}}
    <section class="relative py-20 px-4 text-center">
        <div class="max-w-3xl mx-auto">
            <h1 class="text-4xl font-bold text-white mb-4">Gabungkan PDF</h1>
            <p class="text-white/70 text-lg">Hubungkan beberapa file PDF menjadi satu dokumen</p>
        </div>
    </section>

    {{-- Main Content --}}
    <div class="max-w-4xl mx-auto px-4 pb-20">
        {{-- Processing Status --}}
        <div x-show="isProcessing" x-transition class="mb-8 rounded-2xl bg-white/5 p-8 border border-white/10">
            <div class="mb-6">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-white font-medium">Progress Pemrosesan</span>
                    <span class="text-white/70" x-text="`${progress}%`"></span>
                </div>
                @include('components.progress-bar')
            </div>
            <p class="text-white/70 text-center" x-text="statusMessage"></p>
        </div>

        {{-- Upload Area --}}
        <div x-show="!isProcessing" x-transition
            class="mb-8 rounded-2xl border-2 border-dashed border-white/20 p-12 text-center cursor-pointer hover:border-white/40 transition"
            @dragover="isDragging = true"
            @dragleave="isDragging = false"
            @drop="handleDrop">

            <input 
                type="file" 
                @change="handleFileSelect" 
                accept=".pdf"
                multiple
                hidden
                ref="fileInput"
            >

            <svg class="w-16 h-16 mx-auto mb-4 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>

            <h3 class="text-xl font-semibold text-white mb-2">Pilih File PDF</h3>
            <p class="text-white/60 mb-4">Seret dan lepas atau klik untuk memilih (minimal 2 file)</p>

            <button @click="$refs.fileInput.click()" 
                class="px-6 py-2 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-lg hover:shadow-lg transition">
                Pilih File
            </button>

            {{-- File List --}}
            <template x-if="files.length > 0">
                <div class="mt-6 text-left">
                    <p class="text-white/70 mb-3">File yang dipilih:</p>
                    <template x-for="(file, index) in files" :key="index">
                        <div class="flex items-center justify-between mb-2 p-3 bg-white/5 rounded-lg border border-white/10">
                            <div class="flex items-center gap-3">
                                <span class="text-white/50">#<span x-text="index + 1"></span></span>
                                <span class="text-white" x-text="file.name"></span>
                            </div>
                            <button @click="removeFile(index)" class="text-red-400 hover:text-red-300">
                                ✕
                            </button>
                        </div>
                    </template>
                    <button @click="mergeFiles()" 
                        class="mt-4 w-full py-3 bg-gradient-to-r from-purple-500 to-indigo-600 text-white font-bold rounded-lg hover:shadow-lg transition">
                        Gabung PDF
                    </button>
                </div>
            </template>
        </div>

        {{-- Notifications --}}
        @include('components.notifications')
    </div>
</div>

<script>
function mergePdfTool() {
    return {
        files: [],
        isProcessing: false,
        progress: 0,
        statusMessage: '',
        sessionId: null,
        pdfManager: null,

        init() {
            // Initialization
        },

        handleDrop(e) {
            e.preventDefault();
            this.isDragging = false;
            if (e.dataTransfer.files.length > 0) {
                this.addFiles(e.dataTransfer.files);
            }
        },

        handleFileSelect(e) {
            if (e.target.files.length > 0) {
                this.addFiles(e.target.files);
            }
        },

        addFiles(fileList) {
            for (let file of fileList) {
                if (file.type === 'application/pdf') {
                    this.files.push(file);
                }
            }
        },

        removeFile(index) {
            this.files.splice(index, 1);
        },

        async mergeFiles() {
            if (this.files.length < 2) {
                alert('Pilih minimal 2 file PDF');
                return;
            }

            this.isProcessing = true;
            const formData = new FormData();
            this.files.forEach(file => {
                formData.append('files[]', file);
            });

            try {
                const response = await fetch('{{ route("merge.process") }}', {
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
                (p) => {
                    this.progress = p.percentage;
                    this.statusMessage = p.message;
                },
                (p) => {
                    this.progress = 100;
                    this.statusMessage = 'Selesai! Mengunduh...';
                    // Trigger download
                    setTimeout(() => window.location.href = `/download/${this.sessionId}`, 500);
                },
                (e) => {
                    this.isProcessing = false;
                    alert('Error: ' + e);
                }
            );
        }
    };
}
</script>
@endsection
```

### 3. Route: Update `routes/web.php`

```php
// Add these routes
Route::get('/merge', [MergePdfController::class, 'index'])->name('merge.index');
Route::post('/merge', [MergePdfController::class, 'process'])->name('merge.process');
```

### 4. Update Feature Card: `resources/views/fitur.blade.php`

Ubah dari:
```blade
<a href="{{ route('merge.index') }}" class="feature-card ...">
```

Menjadi:
```blade
<a href="/merge" class="feature-card ...">
```

---

## 🧪 Testing Checklist

- [ ] Controller dibuat dan routable
- [ ] View menampilkan dengan benar
- [ ] Drag & drop file bekerja
- [ ] Multiple file selection bekerja
- [ ] Progress bar muncul saat processing
- [ ] Notifikasi muncul saat error
- [ ] Download setelah complete
- [ ] Mobile responsive
- [ ] Cleanup terjadi otomatis

---

## 📞 Support

Lihat `DEVELOPER_GUIDE.md` untuk detail lengkap.

