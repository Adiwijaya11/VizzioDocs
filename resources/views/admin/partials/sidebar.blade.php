<aside class="sidebar" id="sidebar">

    {{-- Brand --}}
    <div class="sidebar-brand">
        <div class="brand-icon">
            <i class="fas fa-crown"></i>
        </div>
        <div class="brand-text">
            <h2>VizzioDocs</h2>
            <span>Admin Panel</span>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="sidebar-nav">

        {{-- MENU UTAMA --}}
        <div class="nav-label">Menu Utama</div>

        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-th-large"></i>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('admin.statistics') }}" class="nav-link {{ request()->routeIs('admin.statistics') ? 'active' : '' }}">
            <i class="fas fa-chart-line"></i>
            <span>Statistik</span>
        </a>

        <a href="{{ route('admin.reports') }}" class="nav-link {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
            <i class="fas fa-file-chart-column"></i>
            <span>Laporan</span>
        </a>

        <div class="nav-dropdown {{ request()->routeIs(['admin.users', 'admin.roles', 'admin.login-history', 'admin.active-sessions']) ? 'open' : '' }}">
            <button type="button" class="nav-dropdown-toggle" onclick="toggleDropdown(this)">
                <i class="fas fa-users-cog"></i>
                <span>Manajemen Pengguna</span>
                <i class="fas fa-chevron-down nav-chevron"></i>
            </button>
            <div class="nav-dropdown-menu">
                <a href="{{ route('admin.users') }}" class="nav-dropdown-item {{ request()->routeIs('admin.users') && !request()->has('filter') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>Semua Pengguna</span>
                </a>
                <a href="{{ route('admin.roles') }}" class="nav-dropdown-item {{ request()->routeIs('admin.roles') ? 'active' : '' }}">
                    <i class="fas fa-shield-alt"></i>
                    <span>Role & Permission</span>
                </a>
                <a href="{{ route('admin.blacklist') }}" class="nav-dropdown-item {{ request()->routeIs('admin.blacklist') && request()->get('tab') !== 'ip' && request()->get('tab') !== 'device' ? 'active' : '' }}">
                    <i class="fas fa-user-slash"></i>
                    <span>Blacklist</span>
                </a>
                <a href="{{ route('admin.login-history') }}" class="nav-dropdown-item {{ request()->routeIs('admin.login-history') ? 'active' : '' }}">
                    <i class="fas fa-history"></i>
                    <span>Riwayat Login</span>
                </a>
                <a href="{{ route('admin.active-sessions') }}" class="nav-dropdown-item {{ request()->routeIs('admin.active-sessions') ? 'active' : '' }}">
                    <i class="fas fa-laptop-code"></i>
                    <span>Session Aktif</span>
                </a>
            </div>
        </div>

        {{-- MANAJEMEN PLATFORM --}}
        <div class="nav-label">Manajemen Platform</div>

        <a href="{{ route('admin.tools') }}" class="nav-link {{ request()->routeIs('admin.tools') ? 'active' : '' }}">
            <i class="fas fa-tools"></i>
            <span>Manajemen Tools</span>
        </a>

        <a href="{{ route('admin.coupons') }}" class="nav-link {{ request()->routeIs('admin.coupons') ? 'active' : '' }}">
            <i class="fas fa-ticket-alt"></i>
            <span>Kupon & Diskon</span>
        </a>

        {{-- KEAMANAN --}}
        <div class="nav-dropdown {{ request()->routeIs(['admin.audit-logs']) || (request()->routeIs('admin.blacklist') && (request()->get('tab') === 'ip' || request()->get('tab') === 'device')) || (request()->routeIs('admin.settings') && request()->get('tab') === 'security') ? 'open' : '' }}">
            <button type="button" class="nav-dropdown-toggle" onclick="toggleDropdown(this)">
                <i class="fas fa-shield-halved"></i>
                <span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;margin-right:5px;margin-top:-2px;opacity:0.7;">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>Keamanan</span>
                <i class="fas fa-chevron-down nav-chevron"></i>
            </button>
            <div class="nav-dropdown-menu">
                <a href="{{ route('admin.audit-logs') }}" class="nav-dropdown-item {{ request()->routeIs('admin.audit-logs') && !request()->has('tab') ? 'active' : '' }}">
                    <i class="fas fa-clipboard-list"></i>
                    <span>Audit Log</span>
                </a>
                <a href="{{ route('admin.audit-logs') }}?tab=activity" class="nav-dropdown-item {{ request()->routeIs('admin.audit-logs') && request()->get('tab') === 'activity' ? 'active' : '' }}">
                    <i class="fas fa-user-clock"></i>
                    <span>Activity Log</span>
                </a>
                <a href="{{ route('admin.audit-logs') }}?tab=login" class="nav-dropdown-item {{ request()->routeIs('admin.audit-logs') && request()->get('tab') === 'login' ? 'active' : '' }}">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Login Log</span>
                </a>
                <a href="{{ route('admin.audit-logs') }}?tab=api" class="nav-dropdown-item {{ request()->routeIs('admin.audit-logs') && request()->get('tab') === 'api' ? 'active' : '' }}">
                    <i class="fas fa-code"></i>
                    <span>API Log</span>
                </a>
                <a href="{{ route('admin.blacklist') }}?tab=ip" class="nav-dropdown-item {{ request()->routeIs('admin.blacklist') && request()->get('tab') === 'ip' ? 'active' : '' }}">
                    <i class="fas fa-ban"></i>
                    <span>IP Block</span>
                </a>
                <a href="{{ route('admin.blacklist') }}?tab=device" class="nav-dropdown-item {{ request()->routeIs('admin.blacklist') && request()->get('tab') === 'device' ? 'active' : '' }}">
                    <i class="fas fa-mobile-alt"></i>
                    <span>Device Block</span>
                </a>
                <a href="{{ route('admin.rate-limiter') }}" class="nav-dropdown-item {{ request()->routeIs('admin.rate-limiter') ? 'active' : '' }}">
                    <i class="fas fa-lock"></i>
                    <span>Rate Limit & Captcha</span>
                </a>
            </div>
        </div>

        {{-- ADVANCED --}}
        <div class="nav-label">Sistem & Integrasi</div>

        <a href="{{ route('admin.integrations') }}" class="nav-link {{ request()->routeIs('admin.integrations') ? 'active' : '' }}">
            <i class="fas fa-plug"></i>
            <span>Integrasi Layanan</span>
        </a>

        <a href="{{ route('admin.monitoring') }}" class="nav-link {{ request()->routeIs('admin.monitoring') ? 'active' : '' }}">
            <i class="fas fa-server"></i>
            <span>Monitoring Server</span>
        </a>

        <a href="{{ route('admin.maintenance') }}" class="nav-link {{ request()->routeIs('admin.maintenance') ? 'active' : '' }}">
            <i class="fas fa-wrench"></i>
            <span>Mode Maintenance</span>
        </a>

        {{-- SEO --}}
        <div class="nav-dropdown {{ request()->routeIs('admin.seo') ? 'open' : '' }}">
            <button type="button" class="nav-dropdown-toggle" onclick="toggleDropdown(this)">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;">
                    <circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/>
                    <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                </svg>
                <span>SEO</span>
                <i class="fas fa-chevron-down nav-chevron"></i>
            </button>
            <div class="nav-dropdown-menu">
                <a href="{{ route('admin.seo') }}?tab=meta" class="nav-dropdown-item {{ request()->routeIs('admin.seo') && request()->get('tab', 'meta') === 'meta' ? 'active' : '' }}">
                    <i class="fas fa-tags"></i>
                    <span>Meta Tag</span>
                </a>
                <a href="{{ route('admin.seo') }}?tab=sitemap" class="nav-dropdown-item {{ request()->routeIs('admin.seo') && request()->get('tab') === 'sitemap' ? 'active' : '' }}">
                    <i class="fas fa-sitemap"></i>
                    <span>Sitemap</span>
                </a>
                <a href="{{ route('admin.seo') }}?tab=robots" class="nav-dropdown-item {{ request()->routeIs('admin.seo') && request()->get('tab') === 'robots' ? 'active' : '' }}">
                    <i class="fas fa-robot"></i>
                    <span>Robots.txt</span>
                </a>
                <a href="{{ route('admin.seo') }}?tab=analytics" class="nav-dropdown-item {{ request()->routeIs('admin.seo') && request()->get('tab') === 'analytics' ? 'active' : '' }}">
                    <i class="fab fa-google"></i>
                    <span>Google Analytics</span>
                </a>
                <a href="{{ route('admin.seo') }}?tab=console" class="nav-dropdown-item {{ request()->routeIs('admin.seo') && request()->get('tab') === 'console' ? 'active' : '' }}">
                    <i class="fas fa-search"></i>
                    <span>Search Console</span>
                </a>
            </div>
        </div>

        <a href="{{ route('admin.cache') }}" class="nav-link {{ request()->routeIs('admin.cache') ? 'active' : '' }}">
            <i class="fas fa-broom"></i>
            <span>Cache Manager</span>
        </a>

        <a href="{{ route('admin.settings') }}" class="nav-link {{ request()->routeIs('admin.settings') && !request()->has('tab') ? 'active' : '' }}">
            <i class="fas fa-cog"></i>
            <span>Pengaturan</span>
        </a>

        <a href="{{ route('home') }}" class="nav-link" target="_blank">
            <i class="fas fa-globe"></i>
            <span>Lihat Website</span>
            <i class="fas fa-external-link-alt nav-link-external"></i>
        </a>

    </nav>

    {{-- System Status Footer --}}
    <div class="sidebar-footer">
        <div class="sidebar-status-bar">
            <div class="status-item">
                <span class="status-dot status-dot--green"></span>
                <span class="status-label">Server Online</span>
            </div>
            <div class="status-item">
                <i class="fas fa-database" style="font-size:10px; color: var(--text-muted);"></i>
                <span class="status-label">DB Aktif</span>
            </div>
        </div>

        <a href="{{ route('logout') }}" class="nav-link nav-link--danger"
            onclick="event.preventDefault(); document.getElementById('sidebar-logout-form').submit();">
            <i class="fas fa-sign-out-alt"></i>
            <span>Keluar</span>
        </a>
        <form id="sidebar-logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>

</aside>
