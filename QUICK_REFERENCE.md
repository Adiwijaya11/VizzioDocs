# VizzioDocs Phase 1 - Quick Reference Card

## 🚀 Getting Started (30 seconds)

```bash
# 1. Create controller
php artisan make:controller Tools/YourToolController

# 2. Extend base class
class YourToolController extends PdfProcessingService { }

# 3. Implement methods
public function process(Request $request) {
    $this->initializeSession('your-tool');
    // Your code here
    return $this->markComplete('Done!', []);
}

# 4. Create view
# resources/views/tools/your-tool.blade.php

# 5. Add route
Route::get('/your-tool', [YourToolController::class, 'index']);
Route::post('/your-tool', [YourToolController::class, 'process']);
```

---

## 📦 Available Methods

### Session & Files
```php
$this->initializeSession($type, $metadata);
$this->storeInputFile($file);           // → path
$this->getInputPath();                  // → path
$this->getOutputPath($ext);             // → path
$this->validateFile($file);             // → {valid, errors}
```

### Progress & Status
```php
$this->reportProgress($percent, $msg);
$this->markComplete($msg, $data);       // → response
$this->handleException($e);             // → error response
```

### PDF Utilities
```php
$this->getPdfPageCount($file);          // → int
$this->isPdfValid($file);               // → bool
```

---

## 🎨 Frontend Components

```blade
<!-- Progress Bar -->
@include('components.progress-bar')

<!-- Notifications -->
@include('components.notifications')

<!-- Upload -->
@include('components.pdf-upload')
```

---

## 📡 JavaScript Helpers

```javascript
// Create manager
const mgr = new PdfProcessingManager(sessionId);

// Start polling
mgr.startProgressPolling(onProgress, onComplete, onError);

// Stop polling
mgr.stopProgressPolling();

// Cleanup
await mgr.cleanupSession();

// Helpers
PdfProcessingManager.formatBytes(1024);    // "1 KB"
PdfProcessingManager.formatTime(60);       // "1m"
```

---

## 🔌 API Endpoints

```javascript
// Get progress
GET /api/progress/{sessionId}
→ { success, data: { status, percentage, message, eta_seconds } }

// Cleanup
DELETE /api/session/{sessionId}
→ { success }
```

---

## 🧪 Minimal Working Example

**Controller:**
```php
class HelloPdfController extends PdfProcessingService {
    public function index() {
        return view('tools.hello-pdf');
    }

    public function process(Request $request) {
        $this->initializeSession('hello-pdf');
        
        $input = $this->storeInputFile($request->file('file'));
        $this->reportProgress(50, 'Processing...');
        
        // Your code here
        $output = $this->getOutputPath('pdf');
        
        $this->reportProgress(100, 'Done!');
        return $this->markComplete('Processed!', [
            'output_file' => $output
        ]);
    }
}
```

**View:**
```blade
<div x-data="processingStatus()" @load="startPolling('{{ $sessionId }}')">
    @include('components.progress-bar')
    @include('components.notifications')
</div>
```

**Route:**
```php
Route::get('/hello-pdf', [HelloPdfController::class, 'index']);
Route::post('/hello-pdf', [HelloPdfController::class, 'process']);
```

---

## ✅ Pre-Launch Checklist

- [ ] Controller created
- [ ] View created
- [ ] Route added
- [ ] File validation implemented
- [ ] Progress reporting added
- [ ] Output verification done
- [ ] Error handling tested
- [ ] Mobile responsive
- [ ] Tool in navigation
- [ ] Documentation updated

---

## 🎯 Common Gotchas

| Issue | Solution |
|-------|----------|
| "Method not found" | Extends `PdfProcessingService`? |
| Progress not updating | Check `sessionId` passed to polling |
| Output not generated | Verify PDF library installed |
| File disappeared | Use `getOutputPath()` within session |
| CSRF error | Meta tag included in layout? |

---

## 📚 Documentation Links

| File | Purpose |
|------|---------|
| `DEVELOPER_GUIDE.md` | Full tutorial with examples |
| `PHASE_1_SUMMARY.md` | Architecture overview |
| `walkthrough.md` | Implementation details |
| Source code | Inline comments & JSDoc |

---

## 🔒 Security Reminders

- ✅ Input validation automatic (via `validateFile()`)
- ✅ CSRF token auto-included (via `fetchWithCsrf()`)
- ✅ File size limits enforced (configurable)
- ✅ Session isolation automatic (UUID-based)
- ✅ Cleanup automatic (after 60 minutes)

---

## 📊 Expected Development Time

| Task | Time |
|------|------|
| Create controller | 10 min |
| Create view | 15 min |
| Implement processing | 20-60 min |
| Test & debug | 10 min |
| **Total** | **1-2 hours** |

---

## 🚀 Ready to Build?

1. Read `DEVELOPER_GUIDE.md`
2. Copy the example from this card
3. Replace with your PDF logic
4. Test
5. Deploy

**That's it! 🎉**

