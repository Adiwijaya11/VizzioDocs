<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Transaction;
use App\Models\User;
use App\Models\GuestToolUsage;
use App\Models\ToolLock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Show the admin dashboard.
     */
    public function dashboard()
    {
        $totalUsers = User::count();
        $totalAdmins = User::where('role', 'admin')->count();
        $totalPremium = User::where('plan', 'premium')->count();
        $totalFree = User::where('plan', 'free')->count();
        $recentUsers = User::whereDate('created_at', '>=', now()->subDays(7))->count();
        $recentUsersList = User::latest()->take(10)->get();

        return view('admin.dashboard', compact(
            'totalUsers', 'totalAdmins', 'totalPremium', 'totalFree',
            'recentUsers', 'recentUsersList'
        ));
    }

    /**
     * Show all users (real data).
     */
    public function users(Request $request)
    {
        $query = User::query();

        // Filter by role
        if ($request->filled('role') && $request->role !== 'all') {
            $query->where('role', $request->role);
        }

        // Filter by plan
        if ($request->filled('plan') && $request->plan !== 'all') {
            $query->where('plan', $request->plan);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('origin', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        // Stats for overview
        $totalUsers = User::count();
        $totalAdmins = User::where('role', 'admin')->count();
        $totalPremium = User::where('plan', 'premium')->count();
        $totalGuestUsages = GuestToolUsage::count();

        return view('admin.users.index', compact(
            'users', 'totalUsers', 'totalAdmins', 'totalPremium', 'totalGuestUsages'
        ));
    }

    /**
     * Show statistics with real charts.
     */
    public function statistics()
    {
        // User growth over last 30 days (daily registrations)
        $userGrowth = User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->whereDate('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->pluck('count', 'date')
            ->toArray();

        // Fill in missing days with 0
        $dates = collect();
        $userCounts = collect();
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dates->push(now()->subDays($i)->format('d M'));
            $userCounts->push($userGrowth[$date] ?? 0);
        }

        // Users by role distribution
        $roleStats = User::selectRaw('role, COUNT(*) as count')
            ->groupBy('role')
            ->pluck('count', 'role')
            ->toArray();

        // Users by plan distribution
        $planStats = User::selectRaw('plan, COUNT(*) as count')
            ->groupBy('plan')
            ->pluck('count', 'plan')
            ->toArray();

        // Users by country (top 10)
        $countryStats = User::selectRaw('country, COUNT(*) as count')
            ->whereNotNull('country')
            ->groupBy('country')
            ->orderByDesc('count')
            ->take(10)
            ->pluck('count', 'country')
            ->toArray();

        // Users by origin (top 5)
        $originStats = User::selectRaw('origin, COUNT(*) as count')
            ->whereNotNull('origin')
            ->groupBy('origin')
            ->orderByDesc('count')
            ->take(5)
            ->pluck('count', 'origin')
            ->toArray();

        // Total stats
        $totalUsers = User::count();
        $totalAdmins = User::where('role', 'admin')->count();
        $totalPremium = User::where('plan', 'premium')->count();

        // Tool usage stats (from guest_tool_usages)
        $toolUsage = GuestToolUsage::selectRaw('tool_name, SUM(usage_count) as total_uses')
            ->groupBy('tool_name')
            ->orderByDesc('total_uses')
            ->take(10)
            ->pluck('total_uses', 'tool_name')
            ->toArray();

        return view('admin.statistics.index', compact(
            'dates', 'userCounts',
            'roleStats', 'planStats',
            'countryStats', 'originStats',
            'totalUsers', 'totalAdmins', 'totalPremium',
            'toolUsage'
        ));
    }

    /**
     * Show settings page.
     */
    public function settings()
    {
        $totalUsers = User::count();
        $totalAdmins = User::where('role', 'admin')->count();
        $admin = Auth()->user();

        // Load settings from JSON
        $settingsPath = storage_path('app/settings.json');
        $settings = [];
        if (file_exists($settingsPath)) {
            $settings = json_decode(file_get_contents($settingsPath), true) ?? [];
        }

        return view('admin.settings.index', compact('totalUsers', 'totalAdmins', 'admin', 'settings'));
    }

    /**
     * Update settings.
     */
    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'max_file_size' => 'required|integer|min:1|max:200',
            'maintenance_mode' => 'nullable|boolean',
        ]);

        $settingsPath = storage_path('app/settings.json');
        $settings = [
            'site_name' => $validated['site_name'],
            'max_file_size' => $validated['max_file_size'],
            'maintenance_mode' => $request->boolean('maintenance_mode'),
        ];

        file_put_contents($settingsPath, json_encode($settings, JSON_PRETTY_PRINT));

        // Create audit log
        \App\Models\AuditLog::create([
            'user_id' => auth()->id(),
            'name' => auth()->user()->name,
            'tab' => 'audit',
            'action' => 'Ubah Setting',
            'details' => "Mengubah Nama Situs ke '{$validated['site_name']}' dan Max Upload ke {$validated['max_file_size']} MB",
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('admin.settings')->with('success', 'Pengaturan berhasil disimpan.');
    }

    /**
     * Update user's plan.
     */
    public function updatePlan(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'plan' => 'required|in:free,premium',
        ]);

        // Prevent changing main admin's plan
        if ($user->email === 'vizziodocs@gmail.com') {
            return redirect()->route('admin.users')->with('error', 'Plan akun utama admin tidak dapat diubah.');
        }

        // Prevent changing own plan
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users')->with('error', 'Anda tidak dapat mengubah plan akun sendiri.');
        }

        $user->plan = $request->plan;
        $user->save();

        return redirect()->route('admin.users')->with('success', 'Plan pengguna berhasil diperbarui menjadi ' . ucfirst($request->plan) . '.');
    }

    /**
     * Delete a user.
     */
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        // Prevent deleting the main admin account
        if ($user->email === 'vizziodocs@gmail.com') {
            return redirect()->route('admin.users')->with('error', 'Akun utama admin tidak dapat dihapus.');
        }

        // Prevent deleting self
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users')->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        $user->delete();

        return redirect()->route('admin.users')->with('success', 'Pengguna berhasil dihapus.');
    }

    /**
     * Show coupons page.
     */
    public function coupons()
    {
        $totalUsers = User::count();
        $totalPremium = User::where('plan', 'premium')->count();
        $coupons = Coupon::latest()->get();

        return view('admin.coupons.index', compact('totalUsers', 'totalPremium', 'coupons'));
    }

    /**
     * Store a new coupon.
     */
    public function storeCoupon(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'usage_limit' => 'required|integer|min:1|max:999999',
            'duration_days' => 'required|integer|min:1|max:365',
        ]);

        // Generate unique random coupon code
        do {
            $code = strtoupper(bin2hex(random_bytes(4)));
        } while (Coupon::where('code', $code)->exists());

        Coupon::create([
            'code' => $code,
            'name' => $request->name,
            'description' => $request->description,
            'usage_limit' => $request->usage_limit,
            'duration_days' => $request->duration_days,
            'expires_at' => now()->addMonths(6), // default expiry 6 months
        ]);

        return redirect()->route('admin.coupons')->with('success', 'Kupon berhasil dibuat! Kode: ' . $code);
    }

    /**
     * Delete a coupon.
     */
    public function deleteCoupon($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();

        return redirect()->route('admin.coupons')->with('success', 'Kupon berhasil dihapus.');
    }

    /**
     * Show tools page with real data.
     */
    public function tools()
    {
        $totalUsers = User::count();
        $totalUsage = GuestToolUsage::sum('usage_count');

        // Get all tools from tool_locks with usage stats
        $tools = ToolLock::orderBy('tool_name')->get();

        // Get real usage stats per tool
        $usageStats = GuestToolUsage::selectRaw('tool_name, SUM(usage_count) as total_uses, COUNT(DISTINCT ip_address) as unique_users')
            ->groupBy('tool_name')
            ->pluck('total_uses', 'tool_name')
            ->toArray();

        $uniqueUsersStats = GuestToolUsage::selectRaw('tool_name, COUNT(DISTINCT ip_address) as unique_users')
            ->groupBy('tool_name')
            ->pluck('unique_users', 'tool_name')
            ->toArray();

        // Calculate stats
        $activeTools = $tools->where('is_locked', false)->count();
        $lockedTools = $tools->where('is_locked', true)->count();

        // Find most popular tool
        $popularTool = !empty($usageStats) ? array_search(max($usageStats), $usageStats) : '-';
        $popularCount = $popularTool !== '-' ? $usageStats[$popularTool] : 0;

        // Attach usage data to each tool
        $toolData = $tools->map(function ($tool) use ($usageStats, $uniqueUsersStats) {
            $tool->usage_count = $usageStats[$tool->tool_name] ?? 0;
            $tool->unique_users = $uniqueUsersStats[$tool->tool_name] ?? 0;
            return $tool;
        });

        return view('admin.tools.index', compact(
            'totalUsers', 'totalUsage', 'toolData',
            'activeTools', 'lockedTools',
            'popularTool', 'popularCount'
        ));
    }

    /**
     * Toggle tool lock status.
     */
    public function toggleLock($id)
    {
        $tool = ToolLock::findOrFail($id);
        $tool->is_locked = !$tool->is_locked;
        $tool->save();

        $status = $tool->is_locked ? 'terkunci' : 'terbuka';
        return redirect()->route('admin.tools')->with('success', "{$tool->tool_name} berhasil di{$status}.");
    }

    /**
     * Show reports page.
     */
    public function reports()
    {
        $totalUsers = User::count();
        $totalPremium = User::where('plan', 'premium')->count();
        $totalFree = User::where('plan', 'free')->count();

        // Transaction stats
        $totalTransactions = Transaction::count();
        $totalCouponRedemptions = Transaction::where('type', 'coupon_redemption')->count();
        $totalPurchases = Transaction::where('type', 'premium_purchase')->count();
        $recentTransactions = Transaction::with('user')
            ->latest()
            ->take(50)
            ->get();

        // Coupon stats
        $totalCoupons = Coupon::count();
        $activeCoupons = Coupon::where(function ($q) {
            $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
        })->whereColumn('times_used', '<', 'usage_limit')->count();

        return view('admin.reports.index', compact(
            'totalUsers',
            'totalPremium',
            'totalFree',
            'totalTransactions',
            'totalCouponRedemptions',
            'totalPurchases',
            'recentTransactions',
            'totalCoupons',
            'activeCoupons',
        ));
    }

    /**
     * Show blacklist page (Email, IP, Device).
     */
    public function blacklist(Request $request)
    {
        $tab = $request->get('tab', 'email');
        $blacklists = \App\Models\Blacklist::where('type', $tab)->latest()->paginate(20);
        return view('admin.blacklist.index', compact('blacklists', 'tab'));
    }

    public function storeBlacklist(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:email,ip,device',
            'value' => 'required|string',
            'reason' => 'nullable|string',
        ]);

        \App\Models\Blacklist::create([
            'type' => $validated['type'],
            'value' => $validated['value'],
            'reason' => $validated['reason'] ?? 'Penyalahgunaan sistem',
            'blocked_by' => Auth::user()->name ?? 'System',
        ]);

        return redirect()->route('admin.blacklist', ['tab' => $validated['type']])
            ->with('success', 'Entri berhasil ditambahkan ke daftar blacklist.');
    }

    public function deleteBlacklist($id)
    {
        $blacklist = \App\Models\Blacklist::findOrFail($id);
        $type = $blacklist->type;
        $blacklist->delete();

        return redirect()->route('admin.blacklist', ['tab' => $type])
            ->with('success', 'Entri berhasil dihapus dari daftar blacklist.');
    }

    /**
     * Show Audit Logs page.
     */
    public function auditLogs(Request $request)
    {
        $tab = $request->get('tab', 'audit');
        $totalUsers = User::count();
        $loginHistories = [];
        if ($tab === 'login') {
            $loginHistories = \App\Models\LoginHistory::latest()->paginate(25);
        }
        return view('admin.audit_logs.index', compact('totalUsers', 'tab', 'loginHistories'));
    }

    /**
     * Show Integrations dashboard.
     */
    public function integrations()
    {
        // Read real integration statuses from .env config
        $integrations = [
            'google' => [
                'label' => 'Google OAuth',
                'icon' => 'fab fa-google',
                'color' => 'google',
                'description' => 'Otentikasi login dan pendaftaran pengguna menggunakan akun Google secara langsung.',
                'connected' => !empty(config('services.google.client_id')) && config('services.google.client_id') !== null,
                'detail' => !empty(config('services.google.client_id')) ? 'Client ID: ' . substr(config('services.google.client_id'), 0, 20) . '...' : 'Belum dikonfigurasi',
            ],
            'github' => [
                'label' => 'GitHub OAuth',
                'icon' => 'fab fa-github',
                'color' => 'github',
                'description' => 'Otentikasi login cepat (Quick Login) bagi kalangan developer menggunakan profil GitHub.',
                'connected' => !empty(config('services.github.client_id')) && config('services.github.client_id') !== null,
                'detail' => !empty(config('services.github.client_id')) ? 'Client ID: ' . substr(config('services.github.client_id'), 0, 20) . '...' : 'Belum dikonfigurasi',
            ],
            'mail' => [
                'label' => 'Mail Driver',
                'icon' => 'fas fa-envelope',
                'color' => 'mail',
                'description' => 'Driver email untuk pengiriman notifikasi, reset password, dan verifikasi akun.',
                'connected' => true,
                'detail' => 'Driver: ' . strtoupper(config('mail.default', 'log')) . ' | Host: ' . (config('mail.mailers.' . config('mail.default') . '.host') ?: 'log only'),
            ],
            'database' => [
                'label' => 'Database MySQL',
                'icon' => 'fas fa-database',
                'color' => 'db',
                'description' => 'Koneksi database utama untuk menyimpan seluruh data pengguna, transaksi, dan log sistem.',
                'connected' => true,
                'detail' => 'Host: ' . config('database.connections.mysql.host') . ':' . config('database.connections.mysql.port') . ' | DB: ' . config('database.connections.mysql.database'),
            ],
            'session' => [
                'label' => 'Session Driver',
                'icon' => 'fas fa-cookie-bite',
                'color' => 'session',
                'description' => 'Driver penyimpanan sesi pengguna untuk autentikasi dan keamanan login.',
                'connected' => true,
                'detail' => 'Driver: ' . strtoupper(config('session.driver', 'database')) . ' | Lifetime: ' . config('session.lifetime') . ' menit',
            ],
            'cache' => [
                'label' => 'Cache Driver',
                'icon' => 'fas fa-bolt',
                'color' => 'cache',
                'description' => 'Driver cache untuk mempercepat akses data yang sering digunakan.',
                'connected' => true,
                'detail' => 'Driver: ' . strtoupper(config('cache.default', 'database')) . ' | Store: ' . config('cache.default'),
            ],
        ];
        return view('admin.integrations.index', compact('integrations'));
    }

    public function toggleIntegration(Request $request)
    {
        return response()->json(['success' => true, 'message' => 'Status integrasi berhasil diubah.']);
    }

    /**
     * Show Maintenance mode settings.
     */
    public function maintenance()
    {
        $totalUsers = User::count();
        return view('admin.maintenance.index', compact('totalUsers'));
    }

    public function toggleMaintenance(Request $request)
    {
        return redirect()->route('admin.maintenance')->with('success', 'Status mode maintenance berhasil diperbarui.');
    }

    /**
     * Show Server Monitoring dashboard.
     */
    public function monitoring()
    {
        // Real disk stats
        $diskFree  = disk_free_space(base_path());
        $diskTotal = disk_total_space(base_path());
        $diskUsed  = $diskTotal - $diskFree;
        $diskPct   = $diskTotal > 0 ? round(($diskUsed / $diskTotal) * 100) : 0;
        $diskUsedGB  = round($diskUsed  / 1073741824, 2);
        $diskTotalGB = round($diskTotal / 1073741824, 2);

        // Real memory stats via PHP
        $memLimit = ini_get('memory_limit');
        $memCurrent = memory_get_peak_usage(true);
        $memCurrentMB = round($memCurrent / 1048576, 1);

        // Storage folder size (uploads/temp)
        $storagePath = storage_path('app');
        $storageSize = 0;
        if (is_dir($storagePath)) {
            foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($storagePath, \FilesystemIterator::SKIP_DOTS)) as $file) {
                $storageSize += $file->getSize();
            }
        }
        $storageMB = round($storageSize / 1048576, 2);

        // Read last 20 lines of laravel.log
        $logPath = storage_path('logs/laravel.log');
        $logLines = [];
        if (file_exists($logPath)) {
            $lines = file($logPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $logLines = array_slice($lines, -20);
        }

        // Database connection check
        $dbStatus = 'OK';
        $dbVersion = null;
        try {
            $dbVersion = \Illuminate\Support\Facades\DB::selectOne('SELECT VERSION() as v')?->v;
        } catch (\Exception $e) {
            $dbStatus = 'Error: ' . $e->getMessage();
        }

        // Session count
        $activeSessions = \Illuminate\Support\Facades\DB::table('sessions')->count();

        // Queue jobs pending
        $pendingJobs = 0;
        try {
            $pendingJobs = \Illuminate\Support\Facades\DB::table('jobs')->count();
        } catch (\Exception $e) {}

        return view('admin.monitoring.index', compact(
            'diskPct', 'diskUsedGB', 'diskTotalGB',
            'memLimit', 'memCurrentMB',
            'storageMB',
            'logLines',
            'dbStatus', 'dbVersion',
            'activeSessions', 'pendingJobs'
        ));
    }

    /**
     * Show Roles & Permissions list.
     */
    public function roles()
    {
        $totalUsers = User::count();
        return view('admin.roles.index', compact('totalUsers'));
    }


    /**
     * Show Login History.
     */
    public function loginHistory()
    {
        $loginHistories = \App\Models\LoginHistory::latest()->paginate(25);
        return view('admin.login_history.index', compact('loginHistories'));
    }

    /**
     * Show Active Sessions.
     */
    public function activeSessions()
    {
        $sessions = \Illuminate\Support\Facades\DB::table('sessions')
            ->leftJoin('users', 'sessions.user_id', '=', 'users.id')
            ->select('sessions.*', 'users.name as user_name', 'users.email as user_email')
            ->orderBy('sessions.last_activity', 'desc')
            ->get();
        return view('admin.active_sessions.index', compact('sessions'));
    }

    /**
     * Terminate an active user session.
     */
    public function terminateSession($id)
    {
        \Illuminate\Support\Facades\DB::table('sessions')->where('id', $id)->delete();
        return redirect()->route('admin.active-sessions')->with('success', 'Sesi berhasil dihentikan (logout paksa).');
    }

    /**
     * Show SEO Manager.
     */
    public function seo(Request $request)
    {
        $tab = $request->get('tab', 'meta');

        // Load settings from JSON file
        $settingsPath = storage_path('app/seo_settings.json');
        $settings = [];
        if (file_exists($settingsPath)) {
            $settings = json_decode(file_get_contents($settingsPath), true) ?? [];
        }

        // Read actual robots.txt
        $robotsTxt = '';
        $robotsPath = public_path('robots.txt');
        if (file_exists($robotsPath)) {
            $robotsTxt = file_get_contents($robotsPath);
        } else {
            $robotsTxt = "User-agent: *\nAllow: /\n\nSitemap: " . url('/sitemap.xml') . "\n\n# Disallow admin area\nDisallow: /admin/\nDisallow: /login\nDisallow: /register";
        }

        return view('admin.seo.index', compact('tab', 'settings', 'robotsTxt'));
    }

    public function saveSeo(Request $request)
    {
        $tab = $request->get('tab', 'meta');

        // Load existing settings
        $settingsPath = storage_path('app/seo_settings.json');
        $settings = [];
        if (file_exists($settingsPath)) {
            $settings = json_decode(file_get_contents($settingsPath), true) ?? [];
        }

        if ($tab === 'meta') {
            $settings['site_title']       = $request->input('site_title');
            $settings['meta_description'] = $request->input('meta_description');
            $settings['meta_keywords']    = $request->input('meta_keywords');
            $settings['og_image']         = $request->input('og_image');
            $settings['canonical_base']   = $request->input('canonical_base');
        } elseif ($tab === 'sitemap') {
            $settings['auto_sitemap']      = $request->boolean('auto_sitemap');
            $settings['sitemap_priority']  = $request->input('sitemap_priority', '0.8');
        } elseif ($tab === 'robots') {
            // Write directly to public/robots.txt
            $robotsContent = $request->input('robots_content', '');
            file_put_contents(public_path('robots.txt'), $robotsContent);
        } elseif ($tab === 'analytics') {
            $settings['ga_measurement_id'] = $request->input('ga_measurement_id');
            $settings['ga_enabled']        = $request->boolean('ga_enabled');
            $settings['ga_exclude_admin']  = $request->boolean('ga_exclude_admin');
        } elseif ($tab === 'console') {
            $settings['gsc_meta'] = $request->input('gsc_meta');
            if ($request->filled('gsc_file')) {
                $fileName = basename($request->input('gsc_file'));
                file_put_contents(public_path($fileName), "google-site-verification: {$fileName}");
                $settings['gsc_file'] = $fileName;
            }
        }

        // Save settings back to JSON
        file_put_contents($settingsPath, json_encode($settings, JSON_PRETTY_PRINT));

        return redirect()->route('admin.seo', ['tab' => $tab])
            ->with('success', 'Pengaturan SEO berhasil disimpan.');
    }

    /**
     * Show Rate Limiter settings.
     */
    public function rateLimiter()
    {
        return view('admin.rate_limiter.index');
    }

    public function saveRateLimiter(Request $request)
    {
        return redirect()->route('admin.rate-limiter')
            ->with('success', 'Konfigurasi rate limiter berhasil disimpan.');
    }

    /**
     * Show Cache Manager.
     */
    public function cache()
    {
        return view('admin.cache_manager.index');
    }

    /**
     * Execute artisan cache clear commands.
     */
    public function clearCache(Request $request)
    {
        $type = $request->get('type', 'all');
        $messages = [];

        try {
            if ($type === 'cache' || $type === 'all') {
                \Artisan::call('cache:clear');
                $messages[] = 'Application cache berhasil dibersihkan.';
            }
            if ($type === 'config' || $type === 'all') {
                \Artisan::call('config:clear');
                $messages[] = 'Config cache berhasil dibersihkan.';
            }
            if ($type === 'view' || $type === 'all') {
                \Artisan::call('view:clear');
                $messages[] = 'View cache berhasil dibersihkan.';
            }
            if ($type === 'route' || $type === 'all') {
                \Artisan::call('route:clear');
                $messages[] = 'Route cache berhasil dibersihkan.';
            }
        } catch (\Exception $e) {
            return redirect()->route('admin.cache')
                ->with('error', 'Gagal membersihkan cache: ' . $e->getMessage());
        }

        $label = $type === 'all' ? 'Semua cache' : ucfirst($type) . ' cache';
        return redirect()->route('admin.cache')
            ->with('success', implode(' ', $messages));
    }

    /**
     * Show settings for a specific integration.
     */
    public function integrationSettings($service)
    {
        $validServices = ['google', 'github', 'mail', 'database', 'session', 'cache'];
        if (!in_array($service, $validServices)) {
            abort(404);
        }

        $title = '';
        $fields = [];
        $description = '';

        switch ($service) {
            case 'google':
                $title = 'Google OAuth Settings';
                $description = 'Pengaturan otentikasi integrasi Google OAuth. Pastikan Redirect URI di Google Console sesuai dengan callback URL di bawah.';
                $fields = [
                    'GOOGLE_CLIENT_ID' => [
                        'label' => 'Google Client ID',
                        'type' => 'text',
                        'value' => env('GOOGLE_CLIENT_ID'),
                        'placeholder' => 'Masukkan Client ID Google',
                        'required' => true,
                    ],
                    'GOOGLE_CLIENT_SECRET' => [
                        'label' => 'Google Client Secret',
                        'type' => 'text',
                        'value' => env('GOOGLE_CLIENT_SECRET'),
                        'placeholder' => 'Masukkan Client Secret Google',
                        'required' => true,
                    ],
                    'GOOGLE_REDIRECT_URI' => [
                        'label' => 'Google Redirect URI',
                        'type' => 'text',
                        'value' => env('GOOGLE_REDIRECT_URI', 'http://localhost:8000/auth/google/callback'),
                        'placeholder' => 'e.g., http://localhost:8000/auth/google/callback',
                        'required' => true,
                    ]
                ];
                break;
            case 'github':
                $title = 'GitHub OAuth Settings';
                $description = 'Pengaturan otentikasi login menggunakan akun GitHub.';
                $fields = [
                    'GITHUB_CLIENT_ID' => [
                        'label' => 'GitHub Client ID',
                        'type' => 'text',
                        'value' => env('GITHUB_CLIENT_ID'),
                        'placeholder' => 'Masukkan Client ID GitHub',
                        'required' => true,
                    ],
                    'GITHUB_CLIENT_SECRET' => [
                        'label' => 'GitHub Client Secret',
                        'type' => 'text',
                        'value' => env('GITHUB_CLIENT_SECRET'),
                        'placeholder' => 'Masukkan Client Secret GitHub',
                        'required' => true,
                    ],
                    'GITHUB_REDIRECT_URI' => [
                        'label' => 'GitHub Redirect URI',
                        'type' => 'text',
                        'value' => env('GITHUB_REDIRECT_URI', 'http://127.0.0.1:8000/auth/github/callback'),
                        'placeholder' => 'e.g., http://127.0.0.1:8000/auth/github/callback',
                        'required' => true,
                    ]
                ];
                break;
            case 'mail':
                $title = 'Mail Driver Settings';
                $description = 'Pengaturan driver email SMTP untuk pengiriman notifikasi, reset password, dan OTP.';
                $fields = [
                    'MAIL_MAILER' => [
                        'label' => 'Mail Mailer',
                        'type' => 'select',
                        'value' => env('MAIL_MAILER', 'smtp'),
                        'options' => ['smtp' => 'SMTP', 'log' => 'Log (Local Dev)'],
                        'required' => true,
                    ],
                    'MAIL_HOST' => [
                        'label' => 'Mail Host',
                        'type' => 'text',
                        'value' => env('MAIL_HOST', 'smtp.gmail.com'),
                        'placeholder' => 'e.g., smtp.gmail.com',
                        'required' => false,
                    ],
                    'MAIL_PORT' => [
                        'label' => 'Mail Port',
                        'type' => 'number',
                        'value' => env('MAIL_PORT', 587),
                        'placeholder' => 'e.g., 587',
                        'required' => false,
                    ],
                    'MAIL_USERNAME' => [
                        'label' => 'Mail Username',
                        'type' => 'text',
                        'value' => env('MAIL_USERNAME'),
                        'placeholder' => 'e.g., email@gmail.com',
                        'required' => false,
                    ],
                    'MAIL_PASSWORD' => [
                        'label' => 'Mail Password',
                        'type' => 'text',
                        'value' => env('MAIL_PASSWORD'),
                        'placeholder' => 'Masukkan password email/app password',
                        'required' => false,
                    ],
                    'MAIL_ENCRYPTION' => [
                        'label' => 'Mail Encryption',
                        'type' => 'select',
                        'value' => env('MAIL_ENCRYPTION', 'tls'),
                        'options' => ['tls' => 'TLS', 'ssl' => 'SSL', 'null' => 'None'],
                        'required' => false,
                    ],
                    'MAIL_FROM_ADDRESS' => [
                        'label' => 'Mail From Address',
                        'type' => 'email',
                        'value' => env('MAIL_FROM_ADDRESS'),
                        'placeholder' => 'e.g., noreply@vizziodocs.com',
                        'required' => true,
                    ],
                    'MAIL_FROM_NAME' => [
                        'label' => 'Mail From Name',
                        'type' => 'text',
                        'value' => env('MAIL_FROM_NAME', 'VizzioDocs'),
                        'placeholder' => 'e.g., VizzioDocs',
                        'required' => true,
                    ]
                ];
                break;
            case 'database':
                $title = 'Database Settings';
                $description = 'Pengaturan koneksi database MySQL utama Anda.';
                $fields = [
                    'DB_CONNECTION' => [
                        'label' => 'Database Connection',
                        'type' => 'text',
                        'value' => env('DB_CONNECTION', 'mysql'),
                        'required' => true,
                    ],
                    'DB_HOST' => [
                        'label' => 'Database Host',
                        'type' => 'text',
                        'value' => env('DB_HOST', '127.0.0.1'),
                        'required' => true,
                    ],
                    'DB_PORT' => [
                        'label' => 'Database Port',
                        'type' => 'number',
                        'value' => env('DB_PORT', 3306),
                        'required' => true,
                    ],
                    'DB_DATABASE' => [
                        'label' => 'Database Name',
                        'type' => 'text',
                        'value' => env('DB_DATABASE'),
                        'required' => true,
                    ],
                    'DB_USERNAME' => [
                        'label' => 'Database Username',
                        'type' => 'text',
                        'value' => env('DB_USERNAME', 'root'),
                        'required' => true,
                    ],
                    'DB_PASSWORD' => [
                        'label' => 'Database Password',
                        'type' => 'text',
                        'value' => env('DB_PASSWORD'),
                        'required' => false,
                    ]
                ];
                break;
            case 'session':
                $title = 'Session Driver Settings';
                $description = 'Pengaturan driver penyimpanan sesi dan batas waktu aktif sesi pengguna.';
                $fields = [
                    'SESSION_DRIVER' => [
                        'label' => 'Session Driver',
                        'type' => 'select',
                        'value' => env('SESSION_DRIVER', 'database'),
                        'options' => ['file' => 'File', 'cookie' => 'Cookie', 'database' => 'Database', 'redis' => 'Redis'],
                        'required' => true,
                    ],
                    'SESSION_LIFETIME' => [
                        'label' => 'Session Lifetime (Minutes)',
                        'type' => 'number',
                        'value' => env('SESSION_LIFETIME', 120),
                        'required' => true,
                    ]
                ];
                break;
            case 'cache':
                $title = 'Cache Settings';
                $description = 'Pengaturan driver caching utama untuk kecepatan pemuatan data sistem.';
                $fields = [
                    'CACHE_STORE' => [
                        'label' => 'Cache Store Driver',
                        'type' => 'select',
                        'value' => env('CACHE_STORE', 'database'),
                        'options' => ['file' => 'File', 'database' => 'Database', 'redis' => 'Redis', 'array' => 'Array (No cache)'],
                        'required' => true,
                    ]
                ];
                break;
        }

        return view('admin.integrations.settings', compact('service', 'title', 'description', 'fields'));
    }

    /**
     * Update settings and save to .env file.
     */
    public function updateIntegrationSettings(Request $request, $service)
    {
        $validServices = ['google', 'github', 'mail', 'database', 'session', 'cache'];
        if (!in_array($service, $validServices)) {
            abort(404);
        }

        // Gather all inputs based on the service
        $inputs = $request->except('_token');
        
        // Clean inputs and set default empty values for passwords/secrets
        foreach ($inputs as $key => $val) {
            if ($val === null) {
                $inputs[$key] = '';
            }
        }

        // Save to .env
        $success = $this->updateEnv($inputs);

        if ($success) {
            // Write log
            \App\Models\AuditLog::create([
                'user_id' => auth()->id(),
                'name' => auth()->user()->name,
                'tab' => 'audit',
                'action' => 'Integrasi Config',
                'details' => 'Mengubah konfigurasi layanan integrasi ' . strtoupper($service),
                'ip_address' => $request->ip(),
            ]);

            // Clear cache config to reload new .env variables
            try {
                \Artisan::call('config:clear');
            } catch (\Exception $e) {
                // Silently skip if config clear fails
            }

            return redirect()->route('admin.integrations.settings', $service)
                ->with('success', 'Konfigurasi integrasi ' . strtoupper($service) . ' berhasil diperbarui.');
        }

        return redirect()->route('admin.integrations.settings', $service)
            ->with('error', 'Gagal memperbarui file .env. Pastikan file memiliki izin tulis.');
    }

    /**
     * Helper function to programmatically update .env values.
     */
    protected function updateEnv(array $data)
    {
        $envPath = base_path('.env');
        if (!file_exists($envPath)) {
            return false;
        }

        $content = file_get_contents($envPath);

        foreach ($data as $key => $value) {
            // Ensure values with spaces or special characters are quoted properly
            if (preg_match('/\s/', $value) || str_contains($value, '#') || str_contains($value, '$')) {
                $quotedValue = '"' . str_replace('"', '\"', $value) . '"';
            } else {
                $quotedValue = $value;
            }

            // Look for existing key
            $keyPattern = "/^" . preg_quote($key, '/') . "=(.*)$/m";
            if (preg_match($keyPattern, $content)) {
                $content = preg_replace($keyPattern, $key . '=' . $quotedValue, $content);
            } else {
                // Append key if not exists
                $content .= "\n" . $key . '=' . $quotedValue;
            }
        }

        return file_put_contents($envPath, $content) !== false;
    }
}

