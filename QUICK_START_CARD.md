# 🔥 Phase 1 Quick Start Card

**Copy this & reference while building tools**

---

## 📍 Key Files to Know

```
BACKEND:
  app/Services/PdfProcessingService.php     ← Extend this
  
FRONTEND:
  resources/views/components/notifications.blade.php
  resources/views/components/progress-bar.blade.php
  
JAVASCRIPT:
  resources/js/pdf-processing-utils.js      ← Use this
  
ROUTES:
  routes/web.php                            ← Add your routes
```

---

## ⚡ Minimal Controller Template

```php
<?php
namespace App\Http\Controllers;

use App\Services\PdfProcessingService;

class YourToolController extends PdfProcessingService
{
    public function process(Request $request)
    {
        $this->initializeSession('your-tool', [
            'total_files' => count($request->file('files', [])),
        ]);

        try {
            // Validate
            $file = $request->file('file');
            $validation = $this->validateFile($file);
            if (!$validation['valid']) {
                return $this->handleException(
                    new \Exception($validation['errors'][0])
                );
            }

            // Process
            $this->reportProgress(25, 'Processing...');
            $inputFile = $this->storeInputFile($file);
            $outputFile = $this->getOutputPath('pdf');
            
            // Your PDF logic here
            // ...
            
            $this->reportProgress(100, 'Complete!');
            return $this->markComplete('Success!', [
                'output_file' => $outputFile,
            ]);

        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }
}
```

---

## ⚡ Minimal View Template

```blade
@extends('layouts.app')

@section('title', 'Your Tool')

@section('content')
<div x-data="toolComponent()" class="container">
    <!-- Upload Form -->
    <div x-show="!isProcessing">
        <input type="file" @change="handleFile" accept=".pdf">
        <button @click="process()">Process</button>
    </div>

    <!-- Progress -->
    <div x-show="isProcessing">
        @include('components.progress-bar')
        <p x-text="statusMessage"></p>
    </div>
</div>

<script>
function toolComponent() {
    return {
        files: [],
        isProcessing: false,
        progress: 0,
        statusMessage: '',
        
        handleFile(e) {
            this.files = e.target.files;
        },
        
        async process() {
            const formData = new FormData();
            Array.from(this.files).forEach(f => {
                formData.append('file', f);
            });
            
            this.isProcessing = true;
            const res = await fetch('{{ route("your-tool") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            const data = await res.json();
            if (data.success) {
                this.startPolling(data.data.session_id);
            }
        },
        
        startPolling(sessionId) {
            const manager = new PdfProcessingManager(sessionId);
            manager.startProgressPolling(
                (p) => {
                    this.progress = p.percentage;
                    this.statusMessage = p.message;
                },
                (p) => window.location.href = `/download/${sessionId}`,
                (e) => alert('Error: ' + e)
            );
        }
    };
}
</script>
@endsection
```

---

## 📍 Available Base Class Methods

```php
$this->initializeSession($toolName, $data)
$this->validateFile($file)
$this->storeInputFile($file)
$this->getOutputPath($extension)
$this->reportProgress($percentage, $message)
$this->markComplete($message, $data)
$this->handleException($exception)
$this->getSessionId()
$this->getInputPath($filename)
```

---

## 📍 Routes You Need

```php
Route::post('/your-tool', [YourToolController::class, 'process'])->name('your-tool');

// These are already in web.php:
// GET /api/progress/{sessionId}
// DELETE /api/session/{sessionId}
```

---

## 📍 Component Includes

```blade
@include('components.notifications')
@include('components.progress-bar')
@include('components.pdf-upload')
@include('components.pdf-viewer')
```

---

## 🎯 Testing Checklist

```
[ ] Controller works (POST request)
[ ] Form submission validates
[ ] Progress shows (check /api/progress/{sessionId})
[ ] Download works
[ ] Mobile responsive
[ ] Error handling works
```

---

## 💡 Pro Tips

1. Always extend `PdfProcessingService`
2. Use `$this->reportProgress()` frequently
3. Call `$this->markComplete()` when done
4. Never throw exceptions directly
5. Always use `$this->handleException($e)`
6. Test progress polling in browser console
7. Use Alpine.js for all interactivity

---

## 🔗 Quick Links

- Example code: `TOOL_EXAMPLE_MERGE.md`
- Full guide: `DEVELOPER_GUIDE.md`
- API ref: `QUICK_REFERENCE.md`
- Fitur integration: `FITUR_INTEGRATION.md`

---

**Time estimate: 1-2 hours per tool**

