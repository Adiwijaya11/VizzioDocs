<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Panel Admin VizzioDocs - Kelola platform PDF tools">
    <title>@yield('title', 'Admin Panel') - VizzioDocs</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --bg-primary: #f8f9fc;
            --bg-secondary: #ffffff;
            --bg-card: #ffffff;
            --bg-card-hover: #f1f3f9;
            --text-primary: #1e293b;
            --text-secondary: #475569;
            --text-muted: #94a3b8;
            --accent: #6366f1;
            --accent-light: #8b5cf6;
            --accent-gradient: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #ec4899 100%);
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;
            --border: #e2e8f0;
            --sidebar-width: 260px;
            --header-height: 70px;
            --radius: 16px;
            --radius-sm: 10px;
            --radius-xs: 6px;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
        }

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--bg-primary); }
        ::-webkit-scrollbar-thumb { background: var(--border); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--text-muted); }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-10px); }
            to { opacity: 1; transform: translateX(0); }
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        @keyframes shimmer {
            0% { background-position: -200px 0; }
            100% { background-position: 200px 0; }
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--bg-secondary);
            border-right: 1px solid var(--border);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            display: flex;
            flex-direction: column;
            z-index: 100;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-brand {
            padding: 22px 24px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-brand .brand-icon {
            width: 38px;
            height: 38px;
            background: var(--accent-gradient);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: #fff;
            transition: transform 0.3s ease;
        }

        .sidebar-brand:hover .brand-icon {
            transform: rotate(-5deg) scale(1.05);
        }

        .sidebar-brand .brand-text h2 {
            font-size: 18px;
            font-weight: 700;
            background: var(--accent-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .sidebar-brand .brand-text span {
            font-size: 11px;
            color: var(--text-muted);
            display: block;
            margin-top: -2px;
            -webkit-text-fill-color: var(--text-muted);
        }

        .sidebar-nav {
            flex: 1;
            padding: 12px;
            overflow-y: auto;
        }

        .sidebar-nav .nav-label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--text-muted);
            padding: 16px 12px 8px;
            font-weight: 600;
        }

        .sidebar-nav .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 14px;
            color: var(--text-secondary);
            text-decoration: none;
            border-radius: var(--radius-sm);
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
            margin-bottom: 2px;
            position: relative;
        }

        .sidebar-nav .nav-link i {
            width: 20px;
            text-align: center;
            font-size: 16px;
            transition: transform 0.2s ease;
        }

        .sidebar-nav .nav-link:hover {
            background: rgba(139, 92, 246, 0.1);
            color: var(--text-primary);
        }

        .sidebar-nav .nav-link:hover i {
            transform: scale(1.1);
        }

        .sidebar-nav .nav-link.active {
            background: rgba(139, 92, 246, 0.15);
            color: var(--accent-light);
        }

        .sidebar-nav .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 24px;
            background: var(--accent-gradient);
            border-radius: 0 3px 3px 0;
        }

        .sidebar-nav .nav-link.active i {
            color: var(--accent);
        }

        .sidebar-footer {
            padding: 12px;
            border-top: 1px solid var(--border);
        }

        .sidebar-footer .nav-link {
            color: var(--text-muted);
            font-size: 13px;
        }

        .sidebar-footer .nav-link:hover {
            background: rgba(139, 92, 246, 0.08);
            color: var(--text-primary);
        }

        /* ===== NAV DROPDOWN ===== */
        .nav-dropdown {
            margin-bottom: 2px;
            display: flex;
            flex-direction: column;
        }

        .nav-dropdown-toggle {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 14px;
            color: var(--text-secondary);
            background: none;
            border: none;
            text-decoration: none;
            border-radius: var(--radius-sm);
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            text-align: left;
            position: relative;
            outline: none;
        }

        .nav-dropdown-toggle i:first-child {
            width: 20px;
            text-align: center;
            font-size: 16px;
            transition: transform 0.2s ease;
        }

        .nav-dropdown-toggle:hover {
            background: rgba(139, 92, 246, 0.08);
            color: var(--text-primary);
        }

        .nav-dropdown-toggle .nav-chevron {
            margin-left: auto;
            font-size: 10px;
            opacity: 0.5;
            transition: transform 0.3s ease;
        }

        .nav-dropdown.open .nav-chevron {
            transform: rotate(180deg);
        }

        .nav-dropdown-menu {
            display: none;
            padding-left: 24px;
            flex-direction: column;
            gap: 2px;
            margin-top: 2px;
            margin-bottom: 4px;
        }

        .nav-dropdown.open .nav-dropdown-menu {
            display: flex;
        }

        .nav-dropdown-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 14px;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            border-radius: var(--radius-sm);
            transition: all 0.2s ease;
        }

        .nav-dropdown-item i {
            font-size: 12px;
            width: 16px;
            text-align: center;
            opacity: 0.7;
        }

        .nav-dropdown-item:hover {
            background: rgba(139, 92, 246, 0.06);
            color: var(--text-primary);
        }

        .nav-dropdown-item.active {
            background: rgba(139, 92, 246, 0.1);
            color: var(--accent-light);
            font-weight: 600;
        }

        /* Nav badge (count pills) */
        .nav-badge {
            margin-left: auto;
            font-size: 10px;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 20px;
            background: rgba(139, 92, 246, 0.15);
            color: var(--accent-light);
            white-space: nowrap;
            flex-shrink: 0;
        }

        .nav-badge--gold {
            background: rgba(253, 203, 110, 0.2);
            color: var(--warning);
        }

        .nav-badge--green {
            background: rgba(0, 184, 148, 0.15);
            color: var(--success);
        }

        .nav-badge--red {
            background: rgba(225, 112, 85, 0.15);
            color: var(--danger);
        }

        /* External link icon (tiny) */
        .nav-link-external {
            margin-left: auto;
            font-size: 9px !important;
            opacity: 0.4;
            width: auto !important;
        }

        /* Danger nav link (logout) */
        .nav-link--danger {
            color: var(--text-muted);
        }

        .nav-link--danger:hover {
            background: rgba(239, 68, 68, 0.1) !important;
            color: var(--danger) !important;
        }

        /* Status bar in footer */
        .sidebar-status-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 8px 14px;
            margin-bottom: 6px;
            background: rgba(16, 185, 129, 0.06);
            border: 1px solid rgba(16, 185, 129, 0.12);
            border-radius: var(--radius-xs);
        }

        .status-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .status-label {
            font-size: 10px;
            font-weight: 600;
            color: var(--text-muted);
        }

        .status-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .status-dot--green {
            background: var(--success);
            box-shadow: 0 0 6px rgba(16, 185, 129, 0.5);
            animation: pulse-dot 2s infinite;
        }

        @keyframes pulse-dot {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ===== HEADER / NAVBAR ===== */
        .header {
            min-height: var(--header-height);
            height: var(--header-height);
            background: var(--bg-secondary);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            position: sticky;
            top: 0;
            z-index: 99;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .header-left h1 {
            font-size: 20px;
            font-weight: 700;
            line-height: 1.2;
            margin: 0;
            padding: 0;
        }

        .header-left h1 span {
            color: var(--accent-light);
        }

        .hamburger {
            display: none;
            background: none;
            border: none;
            color: var(--text-primary);
            font-size: 22px;
            cursor: pointer;
            padding: 4px;
            border-radius: 8px;
            transition: background 0.2s;
        }

        .hamburger:hover {
            background: rgba(255,255,255,0.05);
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .header-search {
            position: relative;
        }

        .header-search input {
            background: rgba(255,255,255,0.05);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 8px 16px 8px 38px;
            color: var(--text-primary);
            font-size: 13px;
            width: 220px;
            outline: none;
            transition: all 0.3s;
            font-family: 'Inter', sans-serif;
        }

        .header-search input::placeholder {
            color: var(--text-muted);
        }

        .header-search input:focus {
            border-color: var(--accent);
            background: rgba(139, 92, 246, 0.05);
            width: 280px;
        }

        .header-search i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 14px;
        }

        .admin-badge {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 6px 14px;
            background: rgba(139, 92, 246, 0.15);
            border: 1px solid rgba(139, 92, 246, 0.3);
            border-radius: 20px;
            font-size: 13px;
            color: var(--accent-light);
            font-weight: 500;
        }

        .admin-badge i {
            font-size: 12px;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            position: relative;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--accent-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 700;
            color: #fff;
            transition: transform 0.2s;
        }

        .user-profile:hover .user-avatar {
            transform: scale(1.05);
        }

        .user-info .name {
            font-size: 13px;
            font-weight: 600;
        }

        .user-info .email {
            font-size: 11px;
            color: var(--text-muted);
        }

        .profile-dropdown {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            min-width: 200px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(8px);
            transition: all 0.25s ease;
            box-shadow: 0 20px 60px rgba(0,0,0,0.4);
            z-index: 200;
        }

            .profile-dropdown.show {
                opacity: 1;
                visibility: visible;
                transform: translateY(0);
            }

            .profile-dropdown .dropdown-item {
                display: flex;
                align-items: center;
                gap: 10px;
                padding: 12px 16px;
                color: var(--text-secondary);
                text-decoration: none;
                font-size: 13px;
                font-weight: 500;
                transition: all 0.15s;
            }

            .profile-dropdown .dropdown-item:hover {
                background: rgba(139, 92, 246, 0.08);
                color: var(--text-primary);
            }

            .profile-dropdown .dropdown-item i {
                width: 16px;
                text-align: center;
                font-size: 14px;
            }

            .profile-dropdown .dropdown-item.danger {
                border-top: 1px solid var(--border);
            }

            .profile-dropdown .dropdown-item.danger:hover {
                background: rgba(225, 112, 85, 0.1);
                color: var(--danger);
            }

        /* ===== CONTENT AREA ===== */
        .content {
            padding: 28px 32px;
            flex: 1;
        }

        /* Page Header */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
            animation: fadeInUp 0.4s ease;
        }

        .page-header-left h1 {
            font-size: 24px;
            font-weight: 800;
        }

        .page-header-left h1 span {
            color: var(--accent-light);
        }

        .page-header-left p {
            color: var(--text-muted);
            font-size: 14px;
            margin-top: 4px;
        }

        .page-header-right {
            display: flex;
            gap: 10px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: var(--radius-sm);
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: all 0.25s ease;
            font-family: 'Inter', sans-serif;
        }

        .btn-primary {
            background: var(--accent-gradient);
            color: #fff;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(139, 92, 246, 0.3);
        }

        .btn-outline {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--text-secondary);
        }

        .btn-outline:hover {
            border-color: var(--accent);
            color: var(--accent-light);
            background: rgba(139, 92, 246, 0.05);
        }

        .btn-danger {
            background: rgba(225, 112, 85, 0.15);
            color: var(--danger);
        }

        .btn-danger:hover {
            background: rgba(225, 112, 85, 0.25);
        }

        .btn-sm {
            padding: 6px 14px;
            font-size: 12px;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 24px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            animation: fadeInUp 0.5s ease forwards;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--accent-gradient);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .stat-card:hover::before { opacity: 1; }
        .stat-card:hover {
            border-color: rgba(139, 92, 246, 0.3);
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(139, 92, 246, 0.1);
        }

        .stat-card:nth-child(2) { animation-delay: 0.1s; }
        .stat-card:nth-child(3) { animation-delay: 0.2s; }
        .stat-card:nth-child(4) { animation-delay: 0.3s; }

        .stat-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .stat-icon.purple { background: rgba(139, 92, 246, 0.15); color: var(--accent-light); }
        .stat-icon.green { background: rgba(0, 184, 148, 0.15); color: var(--success); }
        .stat-icon.orange { background: rgba(253, 203, 110, 0.15); color: var(--warning); }
        .stat-icon.blue { background: rgba(116, 185, 255, 0.15); color: var(--info); }
        .stat-icon.red { background: rgba(225, 112, 85, 0.15); color: var(--danger); }
        .stat-icon.pink { background: rgba(253, 121, 168, 0.15); color: #fd79a8; }

        .stat-value {
            font-size: 32px;
            font-weight: 800;
            line-height: 1;
            margin-bottom: 4px;
        }

        .stat-change {
            font-size: 12px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .stat-change.up { color: var(--success); }
        .stat-change.down { color: var(--danger); }

        .stat-label {
            font-size: 13px;
            color: var(--text-muted);
            font-weight: 500;
        }

        /* Section */
        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .section-header h2 {
            font-size: 18px;
            font-weight: 700;
        }

        .section-header h2 span {
            color: var(--accent-light);
        }

        /* Card */
        .card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
            animation: fadeInUp 0.5s ease forwards;
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 24px;
            border-bottom: 1px solid var(--border);
        }

        .card-header h3 {
            font-size: 15px;
            font-weight: 700;
        }

        .card-body {
            padding: 24px;
        }

        /* Table */
        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table thead {
            background: rgba(139, 92, 246, 0.08);
        }

        table th {
            padding: 14px 20px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-muted);
            font-weight: 600;
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }

        table td {
            padding: 14px 20px;
            font-size: 14px;
            color: var(--text-secondary);
            border-bottom: 1px solid var(--border);
        }

        table tr:last-child td { border-bottom: none; }
        table tr:hover td { background: rgba(139, 92, 246, 0.03); }

        .table-empty {
            text-align: center;
            padding: 40px !important;
            color: var(--text-muted);
        }

        .table-empty i {
            font-size: 32px;
            display: block;
            margin-bottom: 12px;
            opacity: 0.5;
        }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-admin { background: rgba(139, 92, 246, 0.15); color: var(--accent-light); }
        .badge-user { background: rgba(0, 184, 148, 0.12); color: var(--success); }
        .badge-premium { background: rgba(253, 203, 110, 0.15); color: var(--warning); }
        .badge-free { background: rgba(160, 163, 194, 0.1); color: var(--text-muted); }

        /* Alert */
        .alert {
            padding: 14px 18px;
            border-radius: var(--radius-sm);
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            animation: fadeInUp 0.4s ease;
        }

        .alert-success { background: rgba(0, 184, 148, 0.1); border: 1px solid rgba(0, 184, 148, 0.2); color: var(--success); }
        .alert-danger { background: rgba(225, 112, 85, 0.1); border: 1px solid rgba(225, 112, 85, 0.2); color: var(--danger); }
        .alert-info { background: rgba(116, 185, 255, 0.1); border: 1px solid rgba(116, 185, 255, 0.2); color: var(--info); }

        /* Chart Wrapper */
        .chart-wrapper {
            position: relative;
            width: 100%;
            max-height: 300px;
        }

        .chart-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
            margin-bottom: 28px;
        }

        .chart-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 24px;
            animation: fadeInUp 0.5s ease forwards;
        }

        .chart-card h3 {
            font-size: 15px;
            font-weight: 700;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .chart-card h3 i {
            color: var(--accent-light);
            font-size: 16px;
        }

        /* Statistik mini */
        .stat-mini-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .stat-mini-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 16px;
            background: rgba(255,255,255,0.02);
            border-radius: var(--radius-sm);
            border: 1px solid var(--border);
            transition: all 0.2s;
        }

        .stat-mini-item:hover {
            background: rgba(139, 92, 246, 0.05);
            border-color: rgba(139, 92, 246, 0.2);
        }

        .stat-mini-item .left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .stat-mini-item .left .mini-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }

        .stat-mini-item .left .mini-label {
            font-size: 13px;
            font-weight: 500;
        }

        .stat-mini-item .right {
            font-size: 16px;
            font-weight: 700;
            color: var(--text-primary);
        }

        /* Search & Filter Bar */
        .filter-bar {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .filter-bar .search-input {
            flex: 1;
            min-width: 200px;
            background: rgba(255,255,255,0.05);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            padding: 10px 16px 10px 38px;
            color: var(--text-primary);
            font-size: 13px;
            outline: none;
            transition: all 0.3s;
            font-family: 'Inter', sans-serif;
        }

        .filter-bar .search-input:focus {
            border-color: var(--accent);
            background: rgba(139, 92, 246, 0.05);
        }

        .filter-bar .search-wrapper {
            position: relative;
            flex: 1;
        }

        .filter-bar .search-wrapper i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
        }

        .filter-bar select {
            background: rgba(255,255,255,0.05);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            padding: 10px 14px;
            color: var(--text-secondary);
            font-size: 13px;
            outline: none;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            min-width: 130px;
        }

        /* Pagination */
        .pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 24px;
            border-top: 1px solid var(--border);
        }

        .pagination .info {
            font-size: 13px;
            color: var(--text-muted);
        }

        .pagination .links {
            display: flex;
            gap: 4px;
        }

        .pagination .links a, .pagination .links span {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
        }

        .pagination .links a {
            color: var(--text-secondary);
            background: rgba(255,255,255,0.03);
        }

        .pagination .links a:hover {
            background: rgba(139, 92, 246, 0.1);
            color: var(--accent-light);
        }

        .pagination .links span.active {
            background: var(--accent);
            color: #fff;
        }

        .pagination .links span.disabled {
            color: var(--text-muted);
            opacity: 0.5;
        }

        /* Settings */
        .settings-section {
            margin-bottom: 28px;
        }

        .settings-section h3 {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .settings-section h3 i {
            color: var(--accent-light);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 6px;
        }

        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="number"],
        .form-group textarea,
        .form-group select {
            width: 100%;
            background: rgba(255,255,255,0.05);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            padding: 11px 16px;
            color: var(--text-primary);
            font-size: 14px;
            outline: none;
            transition: all 0.3s;
            font-family: 'Inter', sans-serif;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            border-color: var(--accent);
            background: rgba(139, 92, 246, 0.05);
        }

        .form-group .help-text {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 4px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .toggle-switch {
            position: relative;
            display: inline-flex !important;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }

        .toggle-switch input {
            display: none !important;
        }

        .toggle-slider {
            width: 44px;
            height: 24px;
            background: var(--border);
            border-radius: 12px;
            transition: all 0.3s;
            position: relative;
            flex-shrink: 0;
        }

        .toggle-slider::after {
            content: '';
            position: absolute;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: var(--text-muted);
            top: 3px;
            left: 3px;
            transition: all 0.3s;
        }

        .toggle-switch input:checked + .toggle-slider {
            background: var(--accent);
        }

        .toggle-switch input:checked + .toggle-slider::after {
            left: 23px;
            background: #fff;
        }

        .grid-2col {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 28px;
        }

        .sidebar-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            z-index: 90;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 900px) {
            .grid-2col {
                grid-template-columns: 1fr;
            }
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.open {
                transform: translateX(0);
            }
            .sidebar.open + .sidebar-backdrop {
                display: block;
                opacity: 1;
            }
            .main-content {
                margin-left: 0;
            }
            .hamburger {
                display: block;
            }
            .chart-grid {
                grid-template-columns: 1fr;
            }
            .form-row {
                grid-template-columns: 1fr;
            }
            .header-search {
                display: none;
            }
            .admin-badge span {
                display: none;
            }
            .user-info {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .content {
                padding: 20px 16px;
            }
            .header {
                padding: 0 16px;
            }
            .stats-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 600px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }
            .page-header-right {
                width: 100%;
            }
            .page-header-right .btn,
            .page-header-right span.btn {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            .filter-bar {
                flex-direction: column;
                align-items: stretch;
            }
            .filter-bar .search-wrapper {
                min-width: 100%;
            }
        }
    </style>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.all.min.js"></script>
    <style>
        /* Custom SweetAlert2 Theme for VizzioDocs Admin */
        .swal2-popup {
            border-radius: 20px !important;
            background: #ffffff !important;
            color: #1e293b !important;
            border: 1px solid #e2e8f0 !important;
            box-shadow: 0 20px 50px rgba(99, 102, 241, 0.1) !important;
            font-family: 'Inter', sans-serif !important;
            padding: 2rem 1.5rem !important;
        }
        .swal2-title {
            font-size: 20px !important;
            font-weight: 700 !important;
            color: #1e293b !important;
            margin-top: 10px !important;
        }
        .swal2-html-container {
            font-size: 14px !important;
            color: #475569 !important;
            line-height: 1.6 !important;
        }
        .swal2-actions {
            margin-top: 24px !important;
            gap: 12px !important;
        }
        .swal2-confirm {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #ec4899 100%) !important;
            border: none !important;
            border-radius: 12px !important;
            padding: 10px 24px !important;
            font-weight: 600 !important;
            font-size: 13px !important;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3) !important;
            transition: all 0.2s !important;
            color: #ffffff !important;
        }
        .swal2-confirm:hover {
            transform: translateY(-1px) !important;
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4) !important;
        }
        .swal2-cancel {
            background: #f1f5f9 !important;
            color: #475569 !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 12px !important;
            padding: 10px 24px !important;
            font-weight: 600 !important;
            font-size: 13px !important;
            transition: all 0.2s !important;
        }
        .swal2-cancel:hover {
            background: #e2e8f0 !important;
            color: #1e293b !important;
        }
        .swal2-icon {
            border-width: 3px !important;
            margin: 0 auto 10px !important;
        }
    </style>
    @stack('styles')
</head>
<body>

    {{-- Sidebar --}}
    @include('admin.partials.sidebar')

    {{-- Sidebar Backdrop --}}
    <div class="sidebar-backdrop" id="sidebarBackdrop" onclick="toggleSidebar()"></div>

    {{-- Main Content --}}
    <div class="main-content">

        {{-- Header / Navbar --}}
        @include('admin.partials.navbar')

        {{-- Page Content --}}
        <div class="content">
            @yield('content')
        </div>

    </div>

    <script>
        // Toggle sidebar
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
        }

        // Save sidebar state (scroll position & open dropdowns)
        function saveSidebarState() {
            const sidebar = document.getElementById('sidebar');
            if (!sidebar) return;

            const sidebarNav = sidebar.querySelector('.sidebar-nav');
            if (sidebarNav) {
                // Save scroll position
                localStorage.setItem('admin_sidebar_scroll', sidebarNav.scrollTop);
            }

            // Save open dropdown indexes
            const dropdowns = document.querySelectorAll('.nav-dropdown');
            const openDropdowns = [];
            dropdowns.forEach((dropdown, index) => {
                if (dropdown.classList.contains('open')) {
                    openDropdowns.push(index);
                }
            });
            localStorage.setItem('admin_sidebar_open_dropdowns', JSON.stringify(openDropdowns));
        }

        // Toggle dropdown in sidebar
        function toggleDropdown(button) {
            const dropdown = button.parentElement;
            dropdown.classList.toggle('open');
            saveSidebarState();
        }

        // Restore sidebar state and attach event listeners on page load
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            if (sidebar) {
                const sidebarNav = sidebar.querySelector('.sidebar-nav');

                // 1. Restore open dropdowns
                const openDropdowns = JSON.parse(localStorage.getItem('admin_sidebar_open_dropdowns') || 'null');
                const dropdowns = document.querySelectorAll('.nav-dropdown');
                if (openDropdowns !== null) {
                    dropdowns.forEach(dropdown => dropdown.classList.remove('open'));
                    openDropdowns.forEach(index => {
                        if (dropdowns[index]) {
                            dropdowns[index].classList.add('open');
                        }
                    });
                } else {
                    // Fallback to active route check
                    const activeItems = document.querySelectorAll('.nav-dropdown-item.active');
                    activeItems.forEach(item => {
                        const dropdown = item.closest('.nav-dropdown');
                        if (dropdown) {
                            dropdown.classList.add('open');
                        }
                    });
                }

                // 2. Restore scroll position
                const savedScroll = localStorage.getItem('admin_sidebar_scroll');
                if (savedScroll !== null && sidebarNav) {
                    sidebarNav.scrollTop = parseInt(savedScroll, 10);
                }

                // 3. Listen to scroll events to save position
                if (sidebarNav) {
                    sidebarNav.addEventListener('scroll', function() {
                        localStorage.setItem('admin_sidebar_scroll', sidebarNav.scrollTop);
                    });
                }

                // 4. Save state when clicking any link
                const sidebarLinks = sidebar.querySelectorAll('a');
                sidebarLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        saveSidebarState();
                    });
                });
            }
        });

        // Profile dropdown toggle
        document.addEventListener('DOMContentLoaded', function() {
            const profile = document.querySelector('.user-profile');
            const dropdown = document.getElementById('profileDropdown');

            if (profile && dropdown) {
                profile.addEventListener('click', function(e) {
                    e.stopPropagation();
                    dropdown.classList.toggle('show');
                });

                document.addEventListener('click', function() {
                    dropdown.classList.remove('show');
                });

                dropdown.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }
        });
    </script>
        <script>
        // Custom SweetAlert2 Confirmation Interceptor for Admin Panel
        document.addEventListener('DOMContentLoaded', function() {
            // Intercept clicks on buttons or links with onclick containing "confirm"
            document.addEventListener('click', function(e) {
                let target = e.target.closest('[onclick*="confirm"]');
                if (target) {
                    // Prevent default click behavior
                    e.preventDefault();
                    e.stopPropagation();
                    
                    let onclickAttr = target.getAttribute('onclick');
                    let confirmText = onclickAttr.match(/confirm\(['"](.*?)['"]\)/);
                    let message = confirmText ? confirmText[1] : 'Apakah Anda yakin ingin melakukan tindakan ini?';
                    
                    let isCache = message.toLowerCase().includes('cache');
                    
                    Swal.fire({
                        title: 'Konfirmasi Tindakan',
                        text: message,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Lanjutkan',
                        cancelButtonText: 'Batal',
                        focusCancel: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Show premium loading spinner
                            Swal.fire({
                                title: isCache ? 'Membersihkan Cache...' : 'Memproses...',
                                text: isCache ? 'Mohon tunggu sebentar, sistem sedang membersihkan cache.' : 'Mohon tunggu sebentar, permintaan sedang diproses.',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                showConfirmButton: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                            
                            // Temporarily disable inline onclick to prevent recursion, then trigger click
                            let originalOnclick = target.onclick;
                            target.onclick = null;
                            target.click();
                            setTimeout(() => {
                                target.onclick = originalOnclick;
                            }, 100);
                        }
                    });
                }
            }, true); // Use capture phase to intercept before inline handlers run
            
            // Intercept form submissions with onsubmit containing "confirm"
            document.addEventListener('submit', function(e) {
                let form = e.target;
                let onsubmitAttr = form.getAttribute('onsubmit');
                if (onsubmitAttr && onsubmitAttr.includes('confirm')) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    let confirmText = onsubmitAttr.match(/confirm\(['"](.*?)['"]\)/);
                    let message = confirmText ? confirmText[1] : 'Apakah Anda yakin ingin mengirimkan form ini?';
                    
                    let isDelete = message.toLowerCase().includes('hapus') || message.toLowerCase().includes('delete');
                    
                    Swal.fire({
                        title: 'Konfirmasi Tindakan',
                        text: message,
                        icon: isDelete ? 'warning' : 'question',
                        showCancelButton: true,
                        confirmButtonText: isDelete ? 'Ya, Hapus' : 'Ya, Lanjutkan',
                        cancelButtonText: 'Batal',
                        focusCancel: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: 'Memproses...',
                                text: 'Mohon tunggu sebentar.',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                showConfirmButton: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                            form.submit();
                        }
                    });
                }
            }, true);
        });
    </script>
    @stack('scripts')

    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });
            Toast.fire({
                icon: 'success',
                title: 'Berhasil',
                text: {!! json_encode(session('success')) !!}
            });
        });
    </script>
    @endif

    @if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });
            Toast.fire({
                icon: 'error',
                title: 'Gagal',
                text: {!! json_encode(session('error')) !!}
            });
        });
    </script>
    @endif

</body>
</html>
