<?php

namespace App\Http\Middleware;

use App\Models\AuditLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SecurityAuditMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);
        $response = $next($request);
        $endTime = microtime(true);
        $latency = round(($endTime - $startTime) * 1000) . 'ms';

        $routeName = $request->route() ? $request->route()->getName() : null;
        $path = $request->getPathInfo();

        // 1. Skip admin panel standard GET views, only log POST/DELETE admin actions in controllers
        if (str_starts_with($path, '/admin') && !$request->isMethod('POST') && !$request->isMethod('DELETE')) {
            return $response;
        }

        // 2. Log API requests to the 'api' tab
        if (str_starts_with($path, '/api/') || $request->is('api/*') || $request->expectsJson()) {
            AuditLog::create([
                'user_id' => Auth::id(),
                'name' => Auth::check() ? Auth::user()->name : null,
                'tab' => 'api',
                'action' => $path,
                'details' => $request->getMethod() . ' request to API',
                'ip_address' => $request->ip(),
                'status' => $response->getStatusCode() . ' ' . (Response::$statusTexts[$response->getStatusCode()] ?? 'OK'),
                'latency' => $latency,
            ]);
            return $response;
        }

        // 3. Log user tool usages to the 'activity' tab
        // Checking if the request is a POST submission to tool processes
        if ($routeName && $request->isMethod('POST')) {
            // Check if route is a tool process
            $isToolRoute = false;
            $toolsList = ['merge', 'compress', 'jpg-to-pdf', 'pdf-to-word', 'optimize-pdf', 'split', 'crop', 'rotate', 'remove-pages', 'extract-pages', 'organize-pdf', 'page-numbers', 'png-to-pdf', 'pdf-to-jpg', 'word-to-pdf', 'excel-to-pdf', 'pptx-to-pdf', 'pdf-to-excel', 'pdf-to-pptx', 'pdf-to-txt', 'pdf-to-markdown', 'protect-pdf', 'unlock-pdf', 'watermark-pdf', 'html-to-pdf', 'scan-to-pdf', 'pdf-to-pdfa', 'repair-pdf'];
            
            foreach ($toolsList as $tool) {
                if (str_starts_with($routeName, $tool)) {
                    $isToolRoute = true;
                    break;
                }
            }

            if ($isToolRoute) {
                $toolName = ucwords(str_replace('-', ' ', explode('.', $routeName)[0]));
                $filesCount = $request->hasFile('files') ? count($request->file('files')) : ($request->hasFile('file') ? 1 : 0);
                $detailsMsg = $filesCount > 0 ? "Mengunggah dan memproses {$filesCount} file" : "Memproses permintaan fitur";
                
                AuditLog::create([
                    'user_id' => Auth::id(),
                    'name' => Auth::check() ? Auth::user()->name : 'Guest / Pengunjung',
                    'tab' => 'activity',
                    'action' => $toolName,
                    'details' => $detailsMsg,
                    'ip_address' => $request->ip(),
                    'status' => $response->getStatusCode() == 200 || $response->isRedirection() ? 'Selesai' : 'Gagal',
                ]);
            }
        }

        return $response;
    }
}
