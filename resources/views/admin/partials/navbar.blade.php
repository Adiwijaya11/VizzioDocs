<header class="header">
    <div class="header-left">
        <button class="hamburger" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        <h1>@yield('page_title', 'Dashboard') <span>Admin</span></h1>
    </div>

    <div class="header-right">
        <div class="header-search">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Cari sesuatu..." id="globalSearch" onkeyup="handleGlobalSearch(event)">
        </div>

        <div class="admin-badge">
            <i class="fas fa-shield-alt"></i>
            <span>Administrator</span>
        </div>

        <div class="user-profile">
            <div class="user-avatar">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <div class="user-info">
                <div class="name">{{ Auth::user()->name }}</div>
                <div class="email">{{ Auth::user()->email }}</div>
            </div>

            <div class="profile-dropdown" id="profileDropdown">
                <a href="{{ route('admin.settings') }}" class="dropdown-item">
                    <i class="fas fa-user-cog"></i> Profile Saya
                </a>
                <a href="{{ route('admin.dashboard') }}" class="dropdown-item">
                    <i class="fas fa-tachometer-alt"></i> Dashboard Admin
                </a>
                <a href="{{ route('home') }}" class="dropdown-item" target="_blank">
                    <i class="fas fa-external-link-alt"></i> Ke Website
                </a>
                <a href="{{ route('logout') }}" class="dropdown-item danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i> Keluar
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
    </div>
</header>

<script>
    function handleGlobalSearch(e) {
        if (e.key === 'Enter') {
            const q = e.target.value.trim();
            if (q.length > 0) {
                // Simple search routing
                const currentRoute = '{{ request()->route()->getName() ?? "" }}';
                if (currentRoute.includes('users')) {
                    window.location.href = '{{ route("admin.users") }}?search=' + encodeURIComponent(q);
                } else if (currentRoute.includes('statistics')) {
                    window.location.href = '{{ route("admin.statistics") }}?search=' + encodeURIComponent(q);
                } else {
                    window.location.href = '{{ route("admin.users") }}?search=' + encodeURIComponent(q);
                }
            }
        }
    }
</script>
