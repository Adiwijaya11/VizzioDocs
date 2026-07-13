<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompressPdfController;
use App\Http\Controllers\MergePdfController;
use App\Http\Controllers\SplitPdfController;
use App\Http\Controllers\JpgToPdfController;
use App\Http\Controllers\PdfToJpgController;
use App\Http\Controllers\RotatePdfController;
use App\Http\Controllers\WordToPdfController;
use App\Http\Controllers\PdfToWordController;
use App\Http\Controllers\ExcelToPdfController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\CodeDraftController;
use App\Http\Controllers\PdfCropController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\PdfToTxtController;
use App\Http\Controllers\PdfToMarkdownController;
use App\Http\Controllers\RemovePagesController;
use App\Http\Controllers\ExtractPagesController;
use App\Http\Controllers\OrganizePdfController;
use App\Http\Controllers\WatermarkPdfController;
use App\Http\Controllers\ProtectPdfController;
use App\Http\Controllers\UnlockPdfController;
use App\Http\Controllers\PdfToExcelController;
use App\Http\Controllers\HtmlToPdfController;
use App\Http\Controllers\ScanToPdfController;
use App\Http\Controllers\OptimizePdfController;
use App\Http\Controllers\RepairPdfController;
use App\Http\Controllers\PageNumbersController;
use App\Http\Controllers\PdfToPptxController;
use App\Http\Controllers\PptxToPdfController;
use App\Http\Controllers\PdfToPdfAController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/fitur', function () {
    $toolLocks = \App\Models\ToolLock::all();

    // Build path-to-route mapping for lock overlay JS
    $toolRouteMapping = [];
    $toolLocksByRoute = [];
    foreach ($toolLocks as $lock) {
        // Derive URL path from route name — convention: route.name → /route
        $parts = explode('.', $lock->tool_route);
        $path = '/' . $parts[0];
        $toolRouteMapping[$path] = $lock->tool_route;
        $toolLocksByRoute[$lock->tool_route] = (bool) $lock->is_locked;
    }

    return view('fitur', compact('toolLocks', 'toolRouteMapping', 'toolLocksByRoute'));
})->name('fitur');

Route::get('/about', function () {
    $userCount = \App\Models\User::count();
    $countryCount = \App\Models\User::whereNotNull('country')->distinct('country')->count('country');
    return view('about', compact('userCount', 'countryCount'));
})->name('about');

Route::get('/bantuan', function () {
    return view('bantuan');
})->name('bantuan');

Route::get('/privasi', function () {
    return view('privacy');
})->name('privacy');

Route::get('/syarat-ketentuan', function () {
    return view('terms');
})->name('terms');

Route::get('/hubungi-kami', function () {
    return view('contact');
})->name('contact');

// Tool GET routes (show the tool page) — protected by tool.lock
Route::middleware(['tool.lock'])->group(function () {
Route::get('/compress', [CompressPdfController::class, 'index'])->name('compress.index');
Route::get('/merge', [MergePdfController::class, 'index'])->name('merge.index');
Route::get('/split', [SplitPdfController::class, 'index'])->name('split.index');
Route::get('/jpg-to-pdf', [JpgToPdfController::class, 'index'])->name('jpg-to-pdf.index');
Route::get('/png-to-pdf', [JpgToPdfController::class, 'indexPng'])->name('png-to-pdf.index');
Route::get('/pdf-to-jpg', [PdfToJpgController::class, 'index'])->name('pdf-to-jpg.index');
Route::get('/rotate', [RotatePdfController::class, 'index'])->name('rotate.index');
Route::get('/word-to-pdf', [WordToPdfController::class, 'index'])->name('word-to-pdf.index');
Route::get('/pdf-to-word', [PdfToWordController::class, 'index'])->name('pdf-to-word.index');
Route::get('/excel-to-pdf', [ExcelToPdfController::class, 'index'])->name('excel-to-pdf.index');
Route::get('/crop', [PdfCropController::class, 'index'])->name('crop.index');
Route::get('/pdf-to-txt', [PdfToTxtController::class, 'index'])->name('pdf-to-txt.index');
Route::get('/pdf-to-markdown', [PdfToMarkdownController::class, 'index'])->name('pdf-to-markdown.index');
Route::get('/remove-pages', [RemovePagesController::class, 'index'])->name('remove-pages.index');
Route::get('/extract-pages', [ExtractPagesController::class, 'index'])->name('extract-pages.index');
Route::get('/organize-pdf', [OrganizePdfController::class, 'index'])->name('organize-pdf.index');
Route::get('/watermark-pdf', [WatermarkPdfController::class, 'index'])->name('watermark-pdf.index');
Route::get('/protect-pdf', [ProtectPdfController::class, 'index'])->name('protect-pdf.index');
Route::get('/unlock-pdf', [UnlockPdfController::class, 'index'])->name('unlock-pdf.index');
Route::get('/pdf-to-excel', [PdfToExcelController::class, 'index'])->name('pdf-to-excel.index');
Route::get('/html-to-pdf', [HtmlToPdfController::class, 'index'])->name('html-to-pdf.index');
Route::get('/scan-to-pdf', [ScanToPdfController::class, 'index'])->name('scan-to-pdf.index');
Route::get('/optimize-pdf', [OptimizePdfController::class, 'index'])->name('optimize-pdf.index');
Route::get('/repair-pdf', [RepairPdfController::class, 'index'])->name('repair-pdf.index');
Route::get('/page-numbers', [PageNumbersController::class, 'index'])->name('page-numbers.index');
Route::get('/pdf-to-pptx', [PdfToPptxController::class, 'index'])->name('pdf-to-pptx.index');
Route::get('/pptx-to-pdf', [PptxToPdfController::class, 'index'])->name('pptx-to-pdf.index');
Route::get('/pdf-to-pdfa', [PdfToPdfAController::class, 'index'])->name('pdf-to-pdfa.index');
});

// Tool POST routes (processing) — protected by quota, deduct & tool.lock middleware
Route::middleware(['quota', 'deduct', 'tool.lock'])->group(function () {
    Route::post('/compress', [CompressPdfController::class, 'process'])->name('compress.process');
    Route::post('/merge', [MergePdfController::class, 'process'])->name('merge.process');
    Route::post('/split', [SplitPdfController::class, 'process'])->name('split.process');
    Route::post('/jpg-to-pdf', [JpgToPdfController::class, 'process'])->name('jpg-to-pdf.process');
    Route::post('/png-to-pdf', [JpgToPdfController::class, 'processPng'])->name('png-to-pdf.process');
    Route::post('/pdf-to-jpg', [PdfToJpgController::class, 'process'])->name('pdf-to-jpg.process');
    Route::post('/rotate', [RotatePdfController::class, 'process'])->name('rotate.process');
    Route::post('/word-to-pdf', [WordToPdfController::class, 'process'])->name('word-to-pdf.process');
    Route::post('/pdf-to-word', [PdfToWordController::class, 'process'])->name('pdf-to-word.process');
    Route::post('/excel-to-pdf', [ExcelToPdfController::class, 'process'])->name('excel-to-pdf.process');
    Route::post('/crop/process', [PdfCropController::class, 'crop'])->name('crop.process');
    Route::post('/pdf-crop/upload', [PdfCropController::class, 'upload'])->name('pdf-crop.upload');
    Route::post('/pdf-crop/crop', [PdfCropController::class, 'crop'])->name('pdf-crop.crop');
    Route::post('/pdf-to-txt', [PdfToTxtController::class, 'process'])->name('pdf-to-txt.process');
    Route::post('/pdf-to-markdown', [PdfToMarkdownController::class, 'process'])->name('pdf-to-markdown.process');
    Route::post('/remove-pages', [RemovePagesController::class, 'process'])->name('remove-pages.process');
    Route::post('/extract-pages', [ExtractPagesController::class, 'process'])->name('extract-pages.process');
    Route::post('/organize-pdf', [OrganizePdfController::class, 'process'])->name('organize-pdf.process');
    Route::post('/watermark-pdf', [WatermarkPdfController::class, 'process'])->name('watermark-pdf.process');
    Route::post('/protect-pdf', [ProtectPdfController::class, 'process'])->name('protect-pdf.process');
    Route::post('/unlock-pdf', [UnlockPdfController::class, 'process'])->name('unlock-pdf.process');
    Route::post('/pdf-to-excel', [PdfToExcelController::class, 'process'])->name('pdf-to-excel.process');
    Route::post('/html-to-pdf', [HtmlToPdfController::class, 'process'])->name('html-to-pdf.process');
    Route::post('/scan-to-pdf', [ScanToPdfController::class, 'process'])->name('scan-to-pdf.process');
    Route::post('/optimize-pdf', [OptimizePdfController::class, 'process'])->name('optimize-pdf.process');
    Route::post('/repair-pdf', [RepairPdfController::class, 'process'])->name('repair-pdf.process');
    Route::post('/page-numbers', [PageNumbersController::class, 'process'])->name('page-numbers.process');
    Route::post('/pdf-to-pptx', [PdfToPptxController::class, 'process'])->name('pdf-to-pptx.process');
    Route::post('/pptx-to-pdf', [PptxToPdfController::class, 'process'])->name('pptx-to-pdf.process');
    Route::post('/pdf-to-pdfa', [PdfToPdfAController::class, 'process'])->name('pdf-to-pdfa.process');
});

// PDF Crop viewer — no quota needed
Route::get('/pdf-crop/view/{sessionId}/{filename}', [PdfCropController::class, 'viewPdf'])->name('pdf-crop.view');

// File Download
Route::get('/download/{id}/{filename?}', [DownloadController::class, 'download'])->name('download');

// API Progress Tracking Routes
Route::prefix('api')->group(function () {
    Route::get('/progress/{sessionId}', function ($sessionId) {
        $progressService = new \App\Services\ProcessingProgressService();
        return response()->json($progressService->formatResponse($sessionId));
    })->name('api.progress');
    
    Route::delete('/session/{sessionId}', function ($sessionId) {
        $storageService = new \App\Services\FileStorageService();
        $deleted = $storageService->deleteSession($sessionId);
        return response()->json(['success' => $deleted]);
    })->name('api.session.delete');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Password Reset Routes (OTP-based)
Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');
Route::get('/password/otp', [ForgotPasswordController::class, 'showOtpForm'])->name('password.otp.form');
Route::post('/password/otp/verify', [ForgotPasswordController::class, 'verifyOtp'])->name('password.otp.verify');
Route::post('/password/otp/resend', [ForgotPasswordController::class, 'resendOtp'])->name('password.otp.resend');
Route::get('/password/reset/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [ForgotPasswordController::class, 'reset'])->name('password.update');

// Github OAuth
Route::get('auth/github', [AuthController::class, 'redirectToGithub'])->name('auth.github');
Route::get('auth/github/callback', [AuthController::class, 'handleGithubCallback']);

// Google OAuth
Route::get('auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

// Premium Routes
Route::get('/upgrade', [App\Http\Controllers\PremiumController::class, 'index'])->name('upgrade.index');
Route::post('/upgrade/purchase', [App\Http\Controllers\PremiumController::class, 'purchase'])->name('premium.purchase');
Route::post('/upgrade/apply-coupon', [App\Http\Controllers\CouponController::class, 'applyCoupon'])->name('premium.applyCoupon');

// Admin Routes
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/users', [App\Http\Controllers\Admin\AdminController::class, 'users'])->name('admin.users');
    Route::post('/users/{id}/update-plan', [App\Http\Controllers\Admin\AdminController::class, 'updatePlan'])->name('admin.users.update-plan');
    Route::delete('/users/{id}', [App\Http\Controllers\Admin\AdminController::class, 'deleteUser'])->name('admin.users.delete');
    Route::get('/statistics', [App\Http\Controllers\Admin\AdminController::class, 'statistics'])->name('admin.statistics');
    Route::get('/coupons', [App\Http\Controllers\Admin\AdminController::class, 'coupons'])->name('admin.coupons');
    Route::post('/coupons', [App\Http\Controllers\Admin\AdminController::class, 'storeCoupon'])->name('admin.coupons.store');
    Route::delete('/coupons/{id}', [App\Http\Controllers\Admin\AdminController::class, 'deleteCoupon'])->name('admin.coupons.delete');
    Route::get('/tools', [App\Http\Controllers\Admin\AdminController::class, 'tools'])->name('admin.tools');
    Route::post('/tools/{id}/toggle-lock', [App\Http\Controllers\Admin\AdminController::class, 'toggleLock'])->name('admin.tools.toggle-lock');
    Route::get('/reports', [App\Http\Controllers\Admin\AdminController::class, 'reports'])->name('admin.reports');
    Route::get('/settings', [App\Http\Controllers\Admin\AdminController::class, 'settings'])->name('admin.settings');
    Route::post('/settings', [App\Http\Controllers\Admin\AdminController::class, 'updateSettings'])->name('admin.settings.update');
    
    // Rute Baru untuk Manajemen Lanjutan
    Route::get('/blacklist', [App\Http\Controllers\Admin\AdminController::class, 'blacklist'])->name('admin.blacklist');
    Route::post('/blacklist', [App\Http\Controllers\Admin\AdminController::class, 'storeBlacklist'])->name('admin.blacklist.store');
    Route::delete('/blacklist/{id}', [App\Http\Controllers\Admin\AdminController::class, 'deleteBlacklist'])->name('admin.blacklist.delete');
    
    Route::get('/audit-logs', [App\Http\Controllers\Admin\AdminController::class, 'auditLogs'])->name('admin.audit-logs');
    Route::get('/integrations', [App\Http\Controllers\Admin\AdminController::class, 'integrations'])->name('admin.integrations');
    Route::get('/integrations/{service}', [App\Http\Controllers\Admin\AdminController::class, 'integrationSettings'])->name('admin.integrations.settings');
    Route::post('/integrations/{service}', [App\Http\Controllers\Admin\AdminController::class, 'updateIntegrationSettings'])->name('admin.integrations.update');
    Route::post('/integrations/toggle', [App\Http\Controllers\Admin\AdminController::class, 'toggleIntegration'])->name('admin.integrations.toggle');
    
    Route::get('/maintenance', [App\Http\Controllers\Admin\AdminController::class, 'maintenance'])->name('admin.maintenance');
    Route::post('/maintenance/toggle', [App\Http\Controllers\Admin\AdminController::class, 'toggleMaintenance'])->name('admin.maintenance.toggle');
    
    Route::get('/monitoring', [App\Http\Controllers\Admin\AdminController::class, 'monitoring'])->name('admin.monitoring');
    
    Route::get('/roles', [App\Http\Controllers\Admin\AdminController::class, 'roles'])->name('admin.roles');
    Route::get('/login-history', [App\Http\Controllers\Admin\AdminController::class, 'loginHistory'])->name('admin.login-history');
    Route::get('/active-sessions', [App\Http\Controllers\Admin\AdminController::class, 'activeSessions'])->name('admin.active-sessions');
    Route::post('/active-sessions/{id}/terminate', [App\Http\Controllers\Admin\AdminController::class, 'terminateSession'])->name('admin.active-sessions.terminate');

    // SEO, Rate Limiter, Cache Manager
    Route::get('/seo', [App\Http\Controllers\Admin\AdminController::class, 'seo'])->name('admin.seo');
    Route::post('/seo', [App\Http\Controllers\Admin\AdminController::class, 'saveSeo'])->name('admin.seo.save');
    Route::get('/rate-limiter', [App\Http\Controllers\Admin\AdminController::class, 'rateLimiter'])->name('admin.rate-limiter');
    Route::post('/rate-limiter', [App\Http\Controllers\Admin\AdminController::class, 'saveRateLimiter'])->name('admin.rate-limiter.save');
    Route::get('/cache', [App\Http\Controllers\Admin\AdminController::class, 'cache'])->name('admin.cache');
    Route::post('/cache/clear', [App\Http\Controllers\Admin\AdminController::class, 'clearCache'])->name('admin.cache.clear');
});

// Premium Payment Routes
Route::get('/payment/{invoice}', [App\Http\Controllers\PaymentController::class, 'show'])->name('payment.show');
Route::post('/payment/{invoice}/process', [App\Http\Controllers\PaymentController::class, 'process'])->name('payment.process');
Route::get('/payment/{invoice}/status', [App\Http\Controllers\PaymentController::class, 'status'])->name('payment.status');

// Midtrans Webhook (no auth — Midtrans calls this)
Route::post('/payment/notification', [App\Http\Controllers\MidtransWebhookController::class, 'notification'])->name('payment.notification');

// Debug: test Midtrans QRIS response
Route::get('/debug/midtrans-qris', function () {
    $midtrans = app(App\Services\MidtransService::class);
    $payment = App\Models\Payment::where('user_id', Auth::id())->latest()->first();
    if (!$payment) {
        return 'No payment found';
    }
    try {
        $response = $midtrans->createTransaction($payment, 'qris');
        return response()->json($response);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});
