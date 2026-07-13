{{-- ============================================================
     VizzioDocs — Ultra Modern Responsive Header
     ============================================================ --}}

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');

    *, *::before, *::after { box-sizing: border-box; }
    html { overflow-x: hidden; }
    body { overflow-x: hidden; }

    /* ── CSS Variables ─────────────────────────────────────────── */
    :root {
        --vd-primary: #6366f1;
        --vd-primary-dark: #4f46e5;
        --vd-secondary: #8b5cf6;
        --vd-accent: #06b6d4;
        --vd-surface: rgba(255, 255, 255, 0.85);
        --vd-surface-scrolled: rgba(255, 255, 255, 0.97);
        --vd-text-primary: #0f0f10;
        --vd-text-secondary: #4b5563;
        --vd-text-muted: #9ca3af;
        --vd-border: rgba(0, 0, 0, 0.06);
        --vd-nav-height: 70px;
        --vd-shadow: 0 4px 24px rgba(99,102,241,.08), 0 1px 3px rgba(0,0,0,.06);
        --vd-shadow-scrolled: 0 8px 32px rgba(99,102,241,.12), 0 2px 8px rgba(0,0,0,.08);
    }

    /* ── Header Shell ─────────────────────────────────────────── */
    .vd-header {
        position: fixed;
        top: 0; left: 0; right: 0;
        z-index: 10001;
        background: var(--vd-surface);
        backdrop-filter: blur(20px) saturate(180%);
        -webkit-backdrop-filter: blur(20px) saturate(180%);
        border-bottom: 1px solid var(--vd-border);
        box-shadow: var(--vd-shadow);
        transition: background 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    }

    .vd-header.is-scrolled {
        background: var(--vd-surface-scrolled);
        box-shadow: var(--vd-shadow-scrolled);
        border-bottom-color: rgba(99,102,241,.12);
    }

    @supports not (backdrop-filter: blur(20px)) {
        .vd-header { background: rgba(255,255,255,.98); }
    }

    .vd-header__inner {
        max-width: 1300px;
        margin: 0 auto;
        height: var(--vd-nav-height);
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 28px;
        gap: 12px;
    }

    @media (max-width: 768px) {
        .vd-header__inner { padding: 0 16px; gap: 8px; }
    }
    @media (max-width: 480px) {
        .vd-header__inner { padding: 0 12px; gap: 6px; }
        :root { --vd-nav-height: 62px; }
    }

    /* ── Logo ─────────────────────────────────────────────────── */
    .vd-logo {
        display: flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
        flex-shrink: 0;
        outline: none;
    }

    .vd-logo__mark {
        position: relative;
        width: 40px;
        height: 40px;
        flex-shrink: 0;
    }

    .vd-logo__mark-bg {
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, #6366f1, #8b5cf6, #a855f7);
        border-radius: 12px;
        transition: transform 0.4s cubic-bezier(.34,1.56,.64,1), box-shadow 0.3s ease;
        box-shadow: 0 4px 16px rgba(99,102,241,.4), 0 0 0 0 rgba(99,102,241,0);
    }

    .vd-logo:hover .vd-logo__mark-bg {
        transform: rotate(8deg) scale(1.05);
        box-shadow: 0 8px 24px rgba(99,102,241,.5), 0 0 20px rgba(139,92,246,.2);
    }

    .vd-logo__mark-inner {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
    }

    .vd-logo__mark-glow {
        position: absolute;
        inset: -2px;
        background: linear-gradient(135deg, #6366f1, #8b5cf6, #06b6d4);
        border-radius: 14px;
        opacity: 0;
        filter: blur(8px);
        transition: opacity 0.3s ease;
        z-index: -1;
    }

    .vd-logo:hover .vd-logo__mark-glow { opacity: 0.6; }

    .vd-logo__text {
        display: flex;
        flex-direction: column;
        gap: 1px;
    }

    .vd-logo__name {
        font-size: 21px;
        font-weight: 800;
        letter-spacing: -0.8px;
        line-height: 1;
        color: var(--vd-text-primary);
        font-family: 'Inter', sans-serif;
    }

    .vd-logo__name .vd-logo__accent {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #06b6d4 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .vd-logo__badge {
        font-size: 8.5px;
        font-weight: 700;
        letter-spacing: 0.18em;
        text-transform: uppercase;
        color: var(--vd-text-muted);
        line-height: 1;
    }

    @media (max-width: 480px) {
        .vd-logo__mark { width: 34px; height: 34px; border-radius: 10px; }
        .vd-logo__name { font-size: 17px; }
        .vd-logo__badge { display: none; }
    }

    /* ── Desktop Navigation ────────────────────────────────────── */
    .vd-nav {
        display: none;
        align-items: center;
        gap: 2px;
        flex: 1;
        justify-content: center;
    }

    @media (min-width: 900px) { .vd-nav { display: flex; } }

    .vd-nav__item {
        position: relative;
    }

    .vd-nav__link {
        display: flex;
        align-items: center;
        gap: 5px;
        padding: 8px 14px;
        border-radius: 10px;
        font-size: 13.5px;
        font-weight: 600;
        color: var(--vd-text-secondary);
        text-decoration: none;
        transition: background 0.2s ease, color 0.2s ease;
        white-space: nowrap;
        position: relative;
    }

    .vd-nav__link:hover {
        background: rgba(99,102,241,.07);
        color: var(--vd-primary);
    }

    .vd-nav__link.is-active {
        background: rgba(99,102,241,.1);
        color: var(--vd-primary);
        font-weight: 700;
    }

    .vd-nav__link-dot {
        width: 5px;
        height: 5px;
        border-radius: 50%;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        opacity: 0;
        transform: scale(0);
        transition: opacity 0.2s ease, transform 0.2s cubic-bezier(.34,1.56,.64,1);
        position: absolute;
        bottom: 3px;
        left: 50%;
        transform: translateX(-50%) scale(0);
    }

    .vd-nav__link:hover .vd-nav__link-dot,
    .vd-nav__link.is-active .vd-nav__link-dot {
        opacity: 1;
        transform: translateX(-50%) scale(1);
    }

    /* ── Dropdown Chevron ──────────────────────────────────────── */
    .vd-nav__chevron {
        width: 14px;
        height: 14px;
        color: var(--vd-text-muted);
        transition: transform 0.25s cubic-bezier(.4,0,.2,1), color 0.2s;
        flex-shrink: 0;
    }

    .vd-nav__item:hover .vd-nav__chevron,
    .vd-nav__item--open .vd-nav__chevron {
        transform: rotate(180deg);
        color: var(--vd-primary);
    }

    /* ── Mega Dropdown ─────────────────────────────────────────── */
    .vd-dropdown {
        position: absolute;
        top: calc(100% + 10px);
        left: 50%;
        transform: translateX(-50%) translateY(-8px);
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 20px 60px rgba(0,0,0,.12), 0 4px 16px rgba(99,102,241,.1), 0 0 0 1px rgba(0,0,0,.04);
        padding: 8px;
        min-width: 1200px;
        max-width: calc(100vw - 40px);
        width: auto;
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transition: opacity 0.22s ease, transform 0.22s cubic-bezier(.34,1.1,.64,1), visibility 0.22s ease;
        z-index: 1000;
    }

    .vd-nav__item:hover .vd-dropdown,
    .vd-nav__item--open .vd-dropdown {
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
        transform: translateX(-50%) translateY(0);
    }

    /* Dropdown caret */
    .vd-dropdown::before {
        content: '';
        position: absolute;
        top: -6px;
        left: 50%;
        transform: translateX(-50%);
        width: 12px;
        height: 12px;
        background: #fff;
        border-radius: 2px;
        rotate: 45deg;
        box-shadow: -2px -2px 6px rgba(0,0,0,.04);
    }

    .vd-dropdown__grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 2px;
    }

    .vd-dropdown__section {
        padding: 10px 6px 6px;
    }

    .vd-dropdown__section-label {
        font-size: 9.5px;
        font-weight: 700;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        color: var(--vd-text-muted);
        padding: 0 8px 8px;
        display: block;
    }

    .vd-dropdown__link {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 10px;
        border-radius: 10px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        color: var(--vd-text-secondary);
        transition: background 0.15s ease, color 0.15s ease, transform 0.15s ease;
        margin-bottom: 1px;
    }

    .vd-dropdown__link:hover {
        background: rgba(99,102,241,.07);
        color: var(--vd-primary);
        transform: translateX(2px);
    }

    .vd-dropdown__link-icon {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 13px;
        transition: transform 0.2s ease;
    }

    .vd-dropdown__link:hover .vd-dropdown__link-icon {
        transform: scale(1.1);
    }

    .vd-dropdown__divider {
        height: 1px;
        background: var(--vd-border);
        margin: 6px 0;
    }

    /* ── Header Right Actions ─────────────────────────────────── */
    .vd-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-shrink: 0;
    }

    @media (max-width: 480px) { .vd-actions { gap: 5px; } }

    /* Login Button */
    .vd-btn-login {
        display: none;
        font-size: 13px;
        font-weight: 600;
        color: var(--vd-text-secondary);
        text-decoration: none;
        padding: 8px 14px;
        border-radius: 10px;
        transition: background 0.2s ease, color 0.2s ease;
        white-space: nowrap;
    }

    .vd-btn-login:hover {
        background: rgba(99,102,241,.07);
        color: var(--vd-primary);
    }

    @media (min-width: 600px) { .vd-btn-login { display: block; } }

    /* Register / CTA Button */
    .vd-btn-cta {
        display: none;
        position: relative;
        font-size: 13px;
        font-weight: 700;
        color: #fff;
        text-decoration: none;
        padding: 9px 20px;
        border-radius: 11px;
        white-space: nowrap;
        overflow: hidden;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        box-shadow: 0 4px 14px rgba(99,102,241,.35);
        letter-spacing: -0.01em;
    }

    .vd-btn-cta::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        opacity: 0;
        transition: opacity 0.25s ease;
    }

    .vd-btn-cta span { position: relative; z-index: 1; }

    .vd-btn-cta:hover {
        transform: translateY(-1px) scale(1.02);
        box-shadow: 0 8px 24px rgba(99,102,241,.45);
    }

    .vd-btn-cta:hover::before { opacity: 1; }
    .vd-btn-cta:active { transform: scale(0.97); }

    @media (min-width: 480px) { .vd-btn-cta { display: block; } }

    /* ── Profile Dropdown ────────────────────────────────────────── */
    .vd-profile-dropdown {
        position: relative;
        display: none;
    }

    @media (min-width: 600px) { .vd-profile-dropdown { display: block; } }

    .vd-profile-btn {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 5px 10px 5px 5px;
        border-radius: 12px;
        background: none;
        border: 1.5px solid var(--vd-border);
        cursor: pointer;
        color: var(--vd-text-primary);
        font-family: inherit;
        font-size: 13px;
        font-weight: 600;
        transition: background 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
        -webkit-tap-highlight-color: transparent;
        outline: none;
        white-space: nowrap;
    }

    .vd-profile-btn:hover {
        background: rgba(99,102,241,.05);
        border-color: rgba(99,102,241,.2);
        box-shadow: 0 2px 8px rgba(99,102,241,.08);
    }

    .vd-profile-avatar {
        width: 30px;
        height: 30px;
        border-radius: 9px;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 13px;
        font-weight: 800;
        flex-shrink: 0;
        box-shadow: 0 2px 8px rgba(99,102,241,.3);
    }

    .vd-profile-name {
        max-width: 110px;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .vd-profile-chevron {
        color: var(--vd-text-muted);
        transition: transform 0.2s ease;
        flex-shrink: 0;
    }

    .vd-profile-btn.is-open .vd-profile-chevron {
        transform: rotate(180deg);
    }

    /* Profile Menu */
    .vd-profile-menu {
        position: absolute;
        top: calc(100% + 8px);
        right: 0;
        min-width: 240px;
        background: #fff;
        border-radius: 16px;
        padding: 8px;
        box-shadow: 0 12px 48px rgba(0,0,0,.15), 0 4px 16px rgba(0,0,0,.08);
        border: 1px solid rgba(0,0,0,.06);
        opacity: 0;
        visibility: hidden;
        transform: translateY(-6px) scale(0.96);
        transition: opacity 0.2s ease, transform 0.2s ease, visibility 0.2s ease;
        z-index: 10050;
        transform-origin: top right;
    }

    .vd-profile-menu.is-visible {
        opacity: 1;
        visibility: visible;
        transform: translateY(0) scale(1);
    }

    .vd-profile-menu__header {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 10px 12px;
    }

    .vd-profile-menu__avatar {
        width: 38px;
        height: 38px;
        border-radius: 11px;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 15px;
        font-weight: 800;
        flex-shrink: 0;
    }

    .vd-profile-menu__name {
        font-size: 14px;
        font-weight: 700;
        color: var(--vd-text-primary);
        line-height: 1.2;
    }

    .vd-profile-menu__email {
        font-size: 12px;
        color: var(--vd-text-muted);
        font-weight: 500;
        line-height: 1.3;
        word-break: break-all;
    }

    .vd-profile-menu__divider {
        height: 1px;
        background: var(--vd-border);
        margin: 4px 0;
    }

    .vd-profile-menu__item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 9px 10px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        color: var(--vd-text-secondary);
        text-decoration: none;
        transition: background 0.15s ease, color 0.15s ease;
        background: none;
        border: none;
        cursor: pointer;
        font-family: inherit;
        width: 100%;
        text-align: left;
    }

    .vd-profile-menu__item:hover {
        background: rgba(99,102,241,.06);
        color: var(--vd-primary);
    }

    .vd-profile-menu__item svg {
        width: 16px;
        height: 16px;
        flex-shrink: 0;
    }

    .vd-profile-menu__item--danger:hover {
        background: rgba(239,68,68,.08);
        color: #ef4444;
    }

    /* ── Plan Badge (Profile Button) ──────────────────────────── */
    .vd-plan-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 9px;
        font-weight: 800;
        letter-spacing: 0.04em;
        padding: 2px 7px;
        border-radius: 6px;
        line-height: 1.2;
        white-space: nowrap;
        flex-shrink: 0;
    }
    .vd-plan-badge--premium {
        background: linear-gradient(135deg, #fbbf24, #f59e0b);
        color: #1a1a2e;
        box-shadow: 0 1px 6px rgba(251,191,36,.4);
    }
    .vd-plan-badge--free {
        background: rgba(99,102,241,.12);
        color: #6366f1;
        font-weight: 700;
        min-width: 18px;
    }

    /* ── Plan Label (Profile Menu Header) ──────────────────────── */
    .vd-profile-menu__plan {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-top: 3px;
        flex-wrap: wrap;
    }
    .vd-plan-label {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.02em;
    }
    .vd-plan-label--premium {
        color: #d97706;
    }
    .vd-plan-label--free {
        color: var(--vd-text-muted);
    }
    .vd-plan-label__quota {
        font-size: 10.5px;
        font-weight: 600;
        color: var(--vd-text-muted);
    }
    .vd-plan-label__expiry {
        font-size: 10px;
        font-weight: 600;
        color: #d97706;
        background: rgba(251,191,36,.15);
        padding: 1px 8px;
        border-radius: 6px;
        white-space: nowrap;
        display: inline-block;
        line-height: 1.5;
    }

    /* ── Upgrade Menu Item ────────────────────────────────────── */
    .vd-profile-menu__item--upgrade {
        background: linear-gradient(135deg, rgba(99,102,241,.06), rgba(139,92,246,.06));
        color: var(--vd-primary) !important;
        position: relative;
        overflow: hidden;
    }
    .vd-profile-menu__item--upgrade::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(99,102,241,.04), rgba(139,92,246,.04));
        opacity: 0;
        transition: opacity 0.2s ease;
    }
    .vd-profile-menu__item--upgrade:hover::after {
        opacity: 1;
    }
    .vd-profile-menu__item--upgrade svg {
        color: #f59e0b;
    }

    /* ── Premium Menu Item ─────────────────────────────────────── */
    .vd-profile-menu__item--premium {
        background: linear-gradient(135deg, rgba(251,191,36,.08), rgba(245,158,11,.08));
        color: #92400e !important;
        position: relative;
        overflow: hidden;
    }
    .vd-profile-menu__item--premium::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(251,191,36,.06), rgba(245,158,11,.06));
        opacity: 0;
        transition: opacity 0.2s ease;
    }
    .vd-profile-menu__item--premium:hover::after {
        opacity: 1;
    }
    .vd-profile-menu__item--premium svg {
        color: #d97706;
    }

    /* ── Hamburger ─────────────────────────────────────────────── */
    .vd-hamburger {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 11px;
        background: rgba(99,102,241,.06);
        border: 1.5px solid rgba(99,102,241,.15);
        cursor: pointer;
        color: #374151;
        transition: background 0.2s ease, border-color 0.2s ease, transform 0.15s ease;
        flex-shrink: 0;
        -webkit-tap-highlight-color: transparent;
        outline: none;
    }

    .vd-hamburger:hover {
        background: rgba(99,102,241,.12);
        border-color: rgba(99,102,241,.3);
        color: var(--vd-primary);
    }

    .vd-hamburger:active { transform: scale(0.93); }

    /* Animated bars */
    .vd-hamburger__bars {
        display: flex;
        flex-direction: column;
        gap: 4.5px;
    }

    .vd-hamburger__bar {
        width: 18px;
        height: 2px;
        border-radius: 2px;
        background: currentColor;
        transition: transform 0.3s cubic-bezier(.4,0,.2,1), opacity 0.3s ease, width 0.3s ease;
        transform-origin: center;
    }

    .vd-hamburger.is-active .vd-hamburger__bar:nth-child(1) {
        transform: translateY(6.5px) rotate(45deg);
    }
    .vd-hamburger.is-active .vd-hamburger__bar:nth-child(2) {
        opacity: 0;
        transform: scaleX(0);
    }
    .vd-hamburger.is-active .vd-hamburger__bar:nth-child(3) {
        transform: translateY(-6.5px) rotate(-45deg);
    }

    @media (max-width: 480px) {
        .vd-hamburger { width: 36px; height: 36px; border-radius: 9px; }
    }

    @media (min-width: 900px) { .vd-hamburger { display: none; } }

    /* ── Scroll Lock ───────────────────────────────────────────── */
    html.vd-scroll-lock {
        overflow: hidden !important;
        scrollbar-gutter: stable;
    }
    body.vd-scroll-lock { overflow: hidden !important; }

    /* ── Drawer Backdrop ─────────────────────────────────────── */
    .vd-backdrop {
        position: fixed;
        inset: var(--vd-nav-height, 70px) 0 0 0;
        z-index: 9999;
        background: rgba(15, 15, 16, 0.55);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.35s ease, visibility 0.35s ease;
        pointer-events: none;
        -webkit-tap-highlight-color: transparent;
    }

    .vd-backdrop.is-visible {
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
    }

    @media (min-width: 900px) { .vd-backdrop { display: none; } }

    /* ── Mobile Drawer ─────────────────────────────────────────── */
    .vd-drawer {
        position: fixed;
        top: var(--vd-nav-height, 70px);
        right: 0;
        width: min(360px, 90vw);
        height: calc(100dvh - var(--vd-nav-height, 70px));
        max-height: calc(100dvh - var(--vd-nav-height, 70px));
        z-index: 10002;
        background: #fefefe;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        transform: translateX(100%);
        transition: transform 0.38s cubic-bezier(.4,0,.2,1);
        box-shadow: -24px 0 80px rgba(0,0,0,.15), -1px 0 0 rgba(0,0,0,.05);
        border-left: 1px solid rgba(0,0,0,.06);
    }

    .vd-drawer.is-open { transform: translateX(0); }

    @media (min-width: 900px) { .vd-drawer { display: none; } }

    /* Drawer Header */
    .vd-drawer__head {
        display: none !important; /* Hide redundant head since main navbar is right above it */
    }

    .vd-drawer__logo {
        display: flex;
        align-items: center;
        gap: 9px;
        text-decoration: none;
    }

    .vd-drawer__logo-mark {
        width: 32px; height: 32px;
        border-radius: 10px;
        background: linear-gradient(135deg, #6366f1, #8b5cf6, #a855f7);
        display: flex; align-items: center; justify-content: center;
        color: #fff;
        flex-shrink: 0;
        box-shadow: 0 3px 10px rgba(99,102,241,.35);
    }

    .vd-drawer__logo-name {
        font-size: 16px;
        font-weight: 800;
        color: var(--vd-text-primary);
        letter-spacing: -0.5px;
    }

    .vd-drawer__logo-name span {
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .vd-drawer__close {
        display: flex; align-items: center; justify-content: center;
        width: 32px; height: 32px;
        border-radius: 9px;
        background: #f3f4f6;
        border: none; cursor: pointer;
        color: #6b7280;
        transition: background 0.2s, color 0.2s, transform 0.15s;
        -webkit-tap-highlight-color: transparent;
        outline: none;
    }

    .vd-drawer__close:hover { background: #fee2e2; color: #ef4444; transform: scale(1.05); }

    /* Drawer Body */
    .vd-drawer__body {
        flex: 1;
        overflow-y: auto;
        padding: 16px 12px;
        -webkit-overflow-scrolling: touch;
    }

    /* Drawer Section Label */
    .vd-drawer__section-label {
        font-size: 9.5px;
        font-weight: 700;
        letter-spacing: 0.18em;
        text-transform: uppercase;
        color: var(--vd-text-muted);
        padding: 8px 10px 6px;
        display: block;
        margin-top: 6px;
    }

    .vd-drawer__section-label:first-child { margin-top: 0; }

    /* Drawer Links */
    .vd-drawer__link {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 10px;
        border-radius: 12px;
        text-decoration: none;
        color: #1f2937;
        font-weight: 600;
        font-size: 13.5px;
        transition: background 0.15s ease, color 0.15s ease, transform 0.15s ease;
        margin-bottom: 2px;
        -webkit-tap-highlight-color: transparent;
    }

    .vd-drawer__link:hover {
        background: rgba(99,102,241,.07);
        color: var(--vd-primary);
        transform: translateX(3px);
    }

    .vd-drawer__link-icon {
        width: 34px; height: 34px;
        border-radius: 10px;
        background: #f3f4f6;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
        transition: background 0.2s, box-shadow 0.2s;
        font-size: 14px;
    }

    .vd-drawer__link:hover .vd-drawer__link-icon {
        background: rgba(99,102,241,.12);
        box-shadow: 0 2px 8px rgba(99,102,241,.15);
    }

    /* Drawer Footer */
    .vd-drawer__footer {
        padding: 14px 12px;
        border-top: 1px solid rgba(0,0,0,.06);
        background: #f9fafb;
        flex-shrink: 0;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .vd-drawer__btn-login {
        display: flex; align-items: center; justify-content: center;
        padding: 11px; gap: 8px;
        font-size: 13px; font-weight: 700;
        color: #374151;
        text-decoration: none;
        border: 1.5px solid rgba(0,0,0,.1);
        border-radius: 11px;
        background: #fff;
        transition: border-color 0.2s, color 0.2s, background 0.2s;
        letter-spacing: -0.01em;
    }

    .vd-drawer__btn-login:hover { border-color: var(--vd-primary); color: var(--vd-primary); }

    .vd-drawer__btn-register {
        display: flex; align-items: center; justify-content: center;
        padding: 12px; gap: 8px;
        font-size: 13px; font-weight: 700;
        color: #fff;
        text-decoration: none;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        border-radius: 11px;
        transition: opacity 0.2s, transform 0.15s, box-shadow 0.2s;
        box-shadow: 0 4px 14px rgba(99,102,241,.3);
        letter-spacing: -0.01em;
    }

    .vd-drawer__btn-register:hover {
        opacity: 0.92;
        box-shadow: 0 6px 20px rgba(99,102,241,.4);
    }
    .vd-drawer__btn-register:active { transform: scale(0.98); }

    /* Drawer Profile Info (Mobile) */
    .vd-drawer__profile-info {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 8px 0;
    }

    .vd-drawer__profile-avatar {
        width: 40px;
        height: 40px;
        border-radius: 11px;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 14px;
        font-weight: 800;
        flex-shrink: 0;
        box-shadow: 0 2px 8px rgba(99,102,241,.3);
    }

    .vd-drawer__profile-text {
        flex: 1;
        min-width: 0;
    }

    .vd-drawer__profile-name {
        font-size: 14px;
        font-weight: 700;
        color: var(--vd-text-primary);
        line-height: 1.2;
    }

    .vd-drawer__profile-email {
        font-size: 12px;
        color: var(--vd-text-muted);
        font-weight: 500;
        line-height: 1.3;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .vd-drawer__profile-plan {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 4px;
    }

    .vd-drawer__plan-badge {
        display: inline-flex;
        align-items: center;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 0.02em;
        padding: 2px 8px;
        border-radius: 6px;
        text-transform: uppercase;
    }

    .vd-drawer__plan-badge--free {
        background: rgba(107,114,128,.12);
        color: #6b7280;
    }

    .vd-drawer__plan-badge--premium {
        background: linear-gradient(135deg, rgba(250,204,21,.2), rgba(234,179,8,.2));
        color: #a16207;
        box-shadow: inset 0 1px 0 rgba(250,204,21,.3);
    }

    .vd-drawer__quota-text {
        font-size: 10px;
        font-weight: 600;
        color: var(--vd-text-muted);
    }
    .vd-drawer__plan-expiry {
        font-size: 10px;
        font-weight: 600;
        color: #a16207;
        background: rgba(234,179,8,.12);
        padding: 1px 8px;
        border-radius: 6px;
        white-space: nowrap;
        display: inline-block;
        line-height: 1.5;
        margin-top: 2px;
    }

    .vd-drawer__btn-upgrade {
        display: block; text-align: center;
        padding: 11px;
        font-size: 13px; font-weight: 700;
        color: #fff;
        text-decoration: none;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        border-radius: 11px;
        transition: opacity 0.2s, transform 0.15s, box-shadow 0.2s;
        box-shadow: 0 4px 14px rgba(99,102,241,.3);
        letter-spacing: -0.01em;
    }

    .vd-drawer__btn-upgrade:hover {
        opacity: 0.92;
        box-shadow: 0 6px 20px rgba(99,102,241,.4);
    }
    .vd-drawer__btn-upgrade:active { transform: scale(0.98); }

    .vd-drawer__btn-upgrade--premium {
        background: linear-gradient(135deg, #fbbf24, #f59e0b);
        box-shadow: 0 4px 14px rgba(251,191,36,.35);
    }
    .vd-drawer__btn-upgrade--premium:hover {
        box-shadow: 0 6px 20px rgba(251,191,36,.45);
    }

    .vd-drawer__btn-logout {
        display: block; text-align: center;
        padding: 11px;
        font-size: 13px; font-weight: 700;
        color: #ef4444;
        text-decoration: none;
        border: 1.5px solid rgba(239,68,68,.25);
        border-radius: 11px;
        background: rgba(239,68,68,.04);
        transition: background 0.2s, border-color 0.2s;
        letter-spacing: -0.01em;
    }

    .vd-drawer__btn-logout:hover {
        background: rgba(239,68,68,.1);
        border-color: rgba(239,68,68,.4);
    }

    /* ── Skip Link ─────────────────────────────────────────────── */
    .vd-skip-link {
        position: absolute; top: -100%; left: 16px;
        background: var(--vd-primary); color: #fff;
        padding: 8px 16px; font-size: 13px; font-weight: 700;
        border-radius: 8px; text-decoration: none;
        z-index: 9999; transition: top 0.2s;
    }

    .vd-skip-link:focus { top: 16px; }

    /* ── Screen Reader Only ─────────────────────────────────────── */
    .sr-only {
        position: absolute; width: 1px; height: 1px;
        padding: 0; margin: -1px;
        overflow: hidden; clip: rect(0,0,0,0);
        white-space: nowrap; border-width: 0;
    }

    /* ── Tool Lock Icon ─────────────────────────────────────────── */
    .vd-tool-lock {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-left: auto;
        padding: 0 2px;
        flex-shrink: 0;
        opacity: 0.85;
        transition: opacity 0.2s ease;
    }
    .vd-tool-lock svg {
        display: block;
        width: 12px;
        height: 12px;
    }
    .vd-dropdown__link .vd-tool-lock {
        margin-left: 6px;
    }
    .vd-drawer__link .vd-tool-lock {
        margin-left: auto;
        padding-right: 4px;
    }
    .vd-tool-lock--locked svg {
        color: #f43f5e;
        filter: drop-shadow(0 0 3px rgba(244,63,94,0.4));
    }
    .vd-dropdown__link:hover .vd-tool-lock {
        opacity: 1;
    }
    .vd-drawer__link:hover .vd-tool-lock {
        opacity: 1;
    }
</style>

{{-- Skip Link --}}
<a href="#main-content" class="vd-skip-link">Lewati ke konten utama</a>

{{-- ═══════════════════════════════════════════════════════════
     HEADER
═══════════════════════════════════════════════════════════ --}}
<header class="vd-header" id="vd-header" role="banner">
    <div class="vd-header__inner">

        <a href="{{ route('home') }}" class="vd-logo" aria-label="VizzioDocs – Halaman Utama">
            <div class="vd-logo__mark">
                <div class="vd-logo__mark-glow"></div>
                <div class="vd-logo__mark-bg"></div>
                <div class="vd-logo__mark-inner">
                    {{-- Document icon — melambangkan manipulasi dokumen --}}
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 4a2 2 0 012-2h6l6 6v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" fill="white" stroke="rgba(255,255,255,0.2)" stroke-width="0.5"/>
                        <path d="M12 2v6h6" fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="1.2" stroke-linejoin="round"/>
                        <rect x="7" y="9.5" width="8" height="1.5" rx="0.75" fill="rgba(255,255,255,0.7)"/>
                        <rect x="7" y="12.5" width="5.5" height="1.5" rx="0.75" fill="rgba(255,255,255,0.5)"/>
                        <rect x="7" y="15.5" width="7" height="1.5" rx="0.75" fill="rgba(255,255,255,0.5)"/>
                        <circle cx="17.5" cy="16.5" r="1.8" fill="none" stroke="rgba(255,255,255,0.5)" stroke-width="0.6"/>
                        <circle cx="17.5" cy="16.5" r="0.7" fill="white"/>
                    </svg>
                </div>
            </div>
            <div class="vd-logo__text">
                <span class="vd-logo__name">Vizzio<span class="vd-logo__accent">Docs</span></span>
                <span class="vd-logo__badge">Smart Document Suite</span>
            </div>
        </a>

        {{-- ── Desktop Navigation ───────────────────────────────────── --}}
        <nav class="vd-nav" aria-label="Navigasi utama">

            {{-- Home --}}
            <div class="vd-nav__item">
                <a href="{{ route('home') }}" class="vd-nav__link {{ request()->routeIs('home') ? 'is-active' : '' }}">
                    Beranda
                    <span class="vd-nav__link-dot" aria-hidden="true"></span>
                </a>
            </div>

            {{-- Tools Dropdown --}}
            <div class="vd-nav__item" id="vd-tools-nav">
                <button type="button" class="vd-nav__link" id="vd-tools-btn" aria-haspopup="true" aria-expanded="false" style="background:none;border:none;cursor:pointer;font-family:inherit;">
                    Tools
                    <svg class="vd-nav__chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                    <span class="vd-nav__link-dot" aria-hidden="true"></span>
                </button>

                <div class="vd-dropdown" id="vd-tools-dropdown" role="menu">
                    <div class="vd-dropdown__grid">

                        {{-- COL 1: Populer & Manipulasi --}}
                        <div class="vd-dropdown__section">
                            <span class="vd-dropdown__section-label">Populer</span>

                            <a href="{{ route('merge.index') }}" class="vd-dropdown__link" role="menuitem">
                                <div class="vd-dropdown__link-icon" style="background:linear-gradient(135deg,#a855f7,#ec4899);box-shadow:0 2px 8px rgba(168,85,247,.35)">
                                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/></svg>
                                </div>
                                Gabungkan PDF
                            </a>

                            <a href="{{ route('compress.index') }}" class="vd-dropdown__link" role="menuitem">
                                <div class="vd-dropdown__link-icon" style="background:linear-gradient(135deg,#6366f1,#7c3aed);box-shadow:0 2px 8px rgba(99,102,241,.35)">
                                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                                </div>
                                Kompres PDF
                            </a>

                            <a href="{{ route('jpg-to-pdf.index') }}" class="vd-dropdown__link" role="menuitem">
                                <div class="vd-dropdown__link-icon" style="background:linear-gradient(135deg,#10b981,#16a34a);box-shadow:0 2px 8px rgba(16,185,129,.35)">
                                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                JPG ke PDF
                            </a>

                            <a href="{{ route('pdf-to-word.index') }}" class="vd-dropdown__link" role="menuitem">
                                <div class="vd-dropdown__link-icon" style="background:linear-gradient(135deg,#3b82f6,#6366f1);box-shadow:0 2px 8px rgba(59,130,246,.35)">
                                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                PDF ke Word
                            </a>

                            <a href="{{ route('optimize-pdf.index') }}" class="vd-dropdown__link" role="menuitem">
                                <div class="vd-dropdown__link-icon" style="background:linear-gradient(135deg,#84cc16,#16a34a);box-shadow:0 2px 8px rgba(132,204,22,.35)">
                                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                </div>
                                Optimize PDF
                            </a>

                            <span class="vd-dropdown__section-label" style="margin-top:10px">Edit & Atur</span>

                            <a href="{{ route('split.index') }}" class="vd-dropdown__link" role="menuitem">
                                <div class="vd-dropdown__link-icon" style="background:linear-gradient(135deg,#0ea5e9,#06b6d4);box-shadow:0 2px 8px rgba(14,165,233,.35)">
                                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879M12 12L9.121 9.121m0 5.758a3 3 0 11-4.243 4.243 3 3 0 014.243-4.243zm0-5.758a3 3 0 11-4.243-4.243 3 3 0 014.243 4.243z"/></svg>
                                </div>
                                Pisahkan PDF
                            </a>

                            <a href="{{ route('crop.index') }}" class="vd-dropdown__link" role="menuitem">
                                <div class="vd-dropdown__link-icon" style="background:linear-gradient(135deg,#f43f5e,#ec4899);box-shadow:0 2px 8px rgba(244,63,94,.35)">
                                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                                </div>
                                Crop PDF
                            </a>
                        </div>

                        {{-- COL 2: Halaman & Konversi Gambar --}}
                        <div class="vd-dropdown__section">
                            <span class="vd-dropdown__section-label">Halaman</span>

                            <a href="{{ route('rotate.index') }}" class="vd-dropdown__link" role="menuitem">
                                <div class="vd-dropdown__link-icon" style="background:linear-gradient(135deg,#f59e0b,#ea580c);box-shadow:0 2px 8px rgba(245,158,11,.35)">
                                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 8H17"/></svg>
                                </div>
                                Putar PDF
                            </a>

                            <a href="{{ route('remove-pages.index') }}" class="vd-dropdown__link" role="menuitem">
                                <div class="vd-dropdown__link-icon" style="background:linear-gradient(135deg,#ef4444,#f43f5e);box-shadow:0 2px 8px rgba(239,68,68,.35)">
                                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </div>
                                Hapus Halaman
                            </a>

                            <a href="{{ route('extract-pages.index') }}" class="vd-dropdown__link" role="menuitem">
                                <div class="vd-dropdown__link-icon" style="background:linear-gradient(135deg,#6366f1,#3b82f6);box-shadow:0 2px 8px rgba(99,102,241,.35)">
                                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                </div>
                                Ekstrak Halaman
                            </a>

                            <a href="{{ route('organize-pdf.index') }}" class="vd-dropdown__link" role="menuitem">
                                <div class="vd-dropdown__link-icon" style="background:linear-gradient(135deg,#a855f7,#ec4899);box-shadow:0 2px 8px rgba(168,85,247,.35)">
                                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                                </div>
                                Atur Halaman
                            </a>

                            <a href="{{ route('page-numbers.index') }}" class="vd-dropdown__link" role="menuitem">
                                <div class="vd-dropdown__link-icon" style="background:linear-gradient(135deg,#f59e0b,#eab308);box-shadow:0 2px 8px rgba(245,158,11,.35)">
                                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h2m-2 4h6m-4-2v4"/></svg>
                                </div>
                                Nomor Halaman
                            </a>

                            <span class="vd-dropdown__section-label" style="margin-top:10px">Gambar</span>

                            <a href="{{ route('png-to-pdf.index') }}" class="vd-dropdown__link" role="menuitem">
                                <div class="vd-dropdown__link-icon" style="background:linear-gradient(135deg,#14b8a6,#06b6d4);box-shadow:0 2px 8px rgba(20,184,166,.35)">
                                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                PNG ke PDF
                            </a>

                            <a href="{{ route('pdf-to-jpg.index') }}" class="vd-dropdown__link" role="menuitem">
                                <div class="vd-dropdown__link-icon" style="background:linear-gradient(135deg,#06b6d4,#0ea5e9);box-shadow:0 2px 8px rgba(6,182,212,.35)">
                                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0022.5 18.75V5.25A2.25 2.25 0 0020.25 3H3.75A2.25 2.25 0 001.5 5.25v13.5A2.25 2.25 0 003.75 21zm16.5-13.5h.008v.008h-.008V7.5z"/></svg>
                                </div>
                                PDF ke JPG
                            </a>
                        </div>

                        {{-- COL 3: Konversi Office & Teks --}}
                        <div class="vd-dropdown__section">
                            <span class="vd-dropdown__section-label">Konversi Office</span>

                            <a href="{{ route('word-to-pdf.index') }}" class="vd-dropdown__link" role="menuitem">
                                <div class="vd-dropdown__link-icon" style="background:linear-gradient(135deg,#3b82f6,#6366f1);box-shadow:0 2px 8px rgba(59,130,246,.35)">
                                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                </div>
                                Word ke PDF
                            </a>

                            <a href="{{ route('excel-to-pdf.index') }}" class="vd-dropdown__link" role="menuitem">
                                <div class="vd-dropdown__link-icon" style="background:linear-gradient(135deg,#22c55e,#10b981);box-shadow:0 2px 8px rgba(34,197,94,.35)">
                                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                Excel ke PDF
                            </a>

                            <a href="{{ route('pptx-to-pdf.index') }}" class="vd-dropdown__link" role="menuitem">
                                <div class="vd-dropdown__link-icon" style="background:linear-gradient(135deg,#f97316,#ef4444);box-shadow:0 2px 8px rgba(249,115,22,.35)">
                                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25A2.25 2.25 0 015.25 3h13.5A2.25 2.25 0 0121 5.25z"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0l-2-2m2 2l2 2"/></svg>
                                </div>
                                PowerPoint ke PDF
                            </a>

                            <a href="{{ route('pdf-to-excel.index') }}" class="vd-dropdown__link" role="menuitem">
                                <div class="vd-dropdown__link-icon" style="background:linear-gradient(135deg,#10b981,#16a34a);box-shadow:0 2px 8px rgba(16,185,129,.35)">
                                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 01-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-9.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-7.5A1.125 1.125 0 0112 18.375m9.75-12.75c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125m19.5 0v1.5c0 .621-.504 1.125-1.125 1.125M2.25 5.625v1.5c0 .621.504 1.125 1.125 1.125m0 0h17.25m-17.25 0h7.5c.621 0 1.125.504 1.125 1.125M3.375 8.25c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m17.25-3.75h-7.5c-.621 0-1.125.504-1.125 1.125m8.625-1.125c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125M12 10.875v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 10.875c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125M13.125 12h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125M20.625 12c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5M12 14.625v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 14.625c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125m0 0v.375"/></svg>
                                </div>
                                PDF ke Excel
                            </a>

                            <a href="{{ route('pdf-to-pptx.index') }}" class="vd-dropdown__link" role="menuitem">
                                <div class="vd-dropdown__link-icon" style="background:linear-gradient(135deg,#f97316,#ef4444);box-shadow:0 2px 8px rgba(249,115,22,.35)">
                                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25A2.25 2.25 0 015.25 3h13.5A2.25 2.25 0 0121 5.25z"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 12V6m0 0l-2 2m2-2l2 2"/></svg>
                                </div>
                                PDF ke PowerPoint
                            </a>

                            <span class="vd-dropdown__section-label" style="margin-top:10px">Teks</span>

                            <a href="{{ route('pdf-to-txt.index') }}" class="vd-dropdown__link" role="menuitem">
                                <div class="vd-dropdown__link-icon" style="background:linear-gradient(135deg,#64748b,#475569);box-shadow:0 2px 8px rgba(100,116,139,.35)">
                                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                                </div>
                                PDF ke TXT
                            </a>

                            <a href="{{ route('pdf-to-markdown.index') }}" class="vd-dropdown__link" role="menuitem">
                                <div class="vd-dropdown__link-icon" style="background:linear-gradient(135deg,#8b5cf6,#d946ef);box-shadow:0 2px 8px rgba(139,92,246,.35)">
                                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                                </div>
                                PDF ke Markdown
                            </a>
                        </div>

                        {{-- COL 4: Keamanan, Lainnya, & CTA --}}
                        <div class="vd-dropdown__section">
                            <span class="vd-dropdown__section-label">Keamanan</span>

                            <a href="{{ route('protect-pdf.index') }}" class="vd-dropdown__link" role="menuitem">
                                <div class="vd-dropdown__link-icon" style="background:linear-gradient(135deg,#ef4444,#f43f5e);box-shadow:0 2px 8px rgba(239,68,68,.35)">
                                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                                </div>
                                Proteksi PDF
                            </a>

                            <a href="{{ route('unlock-pdf.index') }}" class="vd-dropdown__link" role="menuitem">
                                <div class="vd-dropdown__link-icon" style="background:linear-gradient(135deg,#22c55e,#10b981);box-shadow:0 2px 8px rgba(34,197,94,.35)">
                                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5V6.75a4.5 4.5 0 119 0v3.75M3.75 21.75h16.5a1.5 1.5 0 001.5-1.5V12a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 12v8.25a1.5 1.5 0 001.5 1.5z"/></svg>
                                </div>
                                Buka Kunci PDF
                            </a>

                            <a href="{{ route('watermark-pdf.index') }}" class="vd-dropdown__link" role="menuitem">
                                <div class="vd-dropdown__link-icon" style="background:linear-gradient(135deg,#3b82f6,#6366f1);box-shadow:0 2px 8px rgba(59,130,246,.35)">
                                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.395m3.42 3.42a15.995 15.995 0 004.764-4.648l3.876-5.814a1.151 1.151 0 00-1.597-1.597L14.146 6.32a15.996 15.996 0 00-4.649 4.763m3.42 3.42a6.776 6.776 0 00-3.42-3.42"/></svg>
                                </div>
                                Watermark PDF
                            </a>

                            <span class="vd-dropdown__section-label" style="margin-top:10px">Lainnya</span>

                            <a href="{{ route('html-to-pdf.index') }}" class="vd-dropdown__link" role="menuitem">
                                <div class="vd-dropdown__link-icon" style="background:linear-gradient(135deg,#0ea5e9,#3b82f6);box-shadow:0 2px 8px rgba(14,165,233,.35)">
                                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418"/></svg>
                                </div>
                                HTML ke PDF
                            </a>

                            <a href="{{ route('scan-to-pdf.index') }}" class="vd-dropdown__link" role="menuitem">
                                <div class="vd-dropdown__link-icon" style="background:linear-gradient(135deg,#f97316,#f59e0b);box-shadow:0 2px 8px rgba(249,115,22,.35)">
                                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z"/><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z"/></svg>
                                </div>
                                Scan ke PDF
                            </a>

                            <a href="{{ route('pdf-to-pdfa.index') }}" class="vd-dropdown__link" role="menuitem">
                                <div class="vd-dropdown__link-icon" style="background:linear-gradient(135deg,#14b8a6,#06b6d4);box-shadow:0 2px 8px rgba(20,184,166,.35)">
                                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
                                </div>
                                PDF ke PDF/A
                            </a>

                            <a href="{{ route('repair-pdf.index') }}" class="vd-dropdown__link" role="menuitem">
                                <div class="vd-dropdown__link-icon" style="background:linear-gradient(135deg,#64748b,#374151);box-shadow:0 2px 8px rgba(100,116,139,.35)">
                                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A1.5 1.5 0 0021 17.25l-5.83-5.83m0 0a2.75 2.75 0 10-3.88-3.88m3.88 3.88L12 14M10.12 7.88L3 15v3a3 3 0 003 3h3l7.12-7.12m-7.12 7.12l1.41-1.41"/></svg>
                                </div>
                                Perbaiki PDF
                            </a>

                            <div class="vd-dropdown__divider"></div>
                            <a href="{{ route('fitur') }}" class="vd-dropdown__link" role="menuitem" style="font-weight:700;color:#6366f1;background:rgba(99,102,241,.06);border-radius:10px;">
                                <div class="vd-dropdown__link-icon" style="background:linear-gradient(135deg,#6366f1,#8b5cf6);box-shadow:0 2px 8px rgba(99,102,241,.35)">
                                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                                </div>
                                Lihat Semua (28) →
                            </a>
                        </div>

                    </div>
                </div>
            </div>

            {{-- About --}}
            <div class="vd-nav__item">
                <a href="{{ route('about') }}" class="vd-nav__link {{ request()->routeIs('about') ? 'is-active' : '' }}">
                    Tentang
                    <span class="vd-nav__link-dot" aria-hidden="true"></span>
                </a>
            </div>

        </nav>

        {{-- ── Right Actions ─────────────────────────────────────────── --}}
        <div class="vd-actions">
            @auth
                {{-- Profile Dropdown (Desktop) --}}
                <div class="vd-profile-dropdown">
                    <button class="vd-profile-btn" id="vd-profile-btn" aria-haspopup="true" aria-expanded="false" type="button">
                        <div class="vd-profile-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
                        <span class="vd-profile-name">{{ Auth::user()->name }}</span>
                        @if(Auth::user()->isPremium())
                            <span class="vd-plan-badge vd-plan-badge--premium">PREMIUM</span>
                        @else
                            <span class="vd-plan-badge vd-plan-badge--free">{{ Auth::user()->getRemainingQuota() }}</span>
                        @endif
                        <svg class="vd-profile-chevron" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div class="vd-profile-menu" id="vd-profile-menu" role="menu" aria-label="Menu pengguna">
                        <div class="vd-profile-menu__header">
                            <div class="vd-profile-menu__avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
                            <div>
                                <div class="vd-profile-menu__name">{{ Auth::user()->name }}</div>
                                <div class="vd-profile-menu__email">{{ Auth::user()->email }}</div>
                                <div class="vd-profile-menu__plan">
                                    @if(Auth::user()->isPremium())
                                        <span class="vd-plan-label vd-plan-label--premium">✦ Premium</span>
                                        @if(Auth::user()->premium_expires_at)
                                            <span class="vd-plan-label__expiry">Hingga {{ Auth::user()->premium_expires_at->format('d M Y \\j\\a\\m H:i') }}</span>
                                        @endif
                                    @else
                                        <span class="vd-plan-label vd-plan-label--free">Gratis</span>
                                        <span class="vd-plan-label__quota">Sisa kuota: {{ Auth::user()->getRemainingQuota() }}/20</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="vd-profile-menu__divider"></div>
                        @if(!Auth::user()->isPremium())
                            <a href="{{ route('upgrade.index') }}" class="vd-profile-menu__item vd-profile-menu__item--upgrade">
                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                Upgrade ke Premium
                            </a>
                        @endif
                        @if(Auth::user()->isPremium())
                            <a href="{{ route('upgrade.index') }}" class="vd-profile-menu__item vd-profile-menu__item--premium">
                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/></svg>
                                Cek Premium
                            </a>
                        @endif

                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="vd-profile-menu__item" style="color:#6366f1;font-weight:600;">
                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                Dashboard Admin
                            </a>
                            <div class="vd-profile-menu__divider"></div>
                        @endif

                        <form method="POST" action="{{ route('logout') }}" id="logout-form" style="display:none;">@csrf</form>
                        <button class="vd-profile-menu__item vd-profile-menu__item--danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" role="menuitem">
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            Keluar
                        </button>
                    </div>
                </div>
            @endauth

            @guest
                <a href="{{ route('login') }}" class="vd-btn-login">Masuk</a>
                <a href="{{ route('register') }}" class="vd-btn-cta">
                    <span>Daftar Gratis</span>
                </a>
            @endguest

            {{-- Hamburger --}}
            <button
                class="vd-hamburger"
                id="vd-ham-btn"
                aria-label="Buka menu navigasi"
                aria-expanded="false"
                aria-controls="vd-drawer"
                type="button"
            >
                <div class="vd-hamburger__bars" aria-hidden="true">
                    <span class="vd-hamburger__bar"></span>
                    <span class="vd-hamburger__bar"></span>
                    <span class="vd-hamburger__bar"></span>
                </div>
            </button>
        </div>

    </div>
</header>

{{-- Hidden JSON data for Tool Lock JS --}}
<script id="vd-tool-lock-data" type="application/json">@json($lockedPaths ?? [])</script>

{{-- ═══════════════════════════════════════════════════════════
     BACKDROP
═══════════════════════════════════════════════════════════ --}}
<div class="vd-backdrop" id="vd-backdrop" aria-hidden="true"></div>

{{-- ═══════════════════════════════════════════════════════════
     MOBILE DRAWER
═══════════════════════════════════════════════════════════ --}}
<div
    class="vd-drawer"
    id="vd-drawer"
    role="dialog"
    aria-modal="true"
    aria-label="Menu navigasi"
    aria-hidden="true"
>
    {{-- Drawer Head --}}
    <div class="vd-drawer__head">
        <a href="{{ route('home') }}" class="vd-drawer__logo" tabindex="-1" id="vd-drawer-logo">
            <div class="vd-drawer__logo-mark">
                {{-- VizzioDocs Document Icon --}}
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4 4a2 2 0 012-2h6l6 6v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" fill="white" stroke="rgba(255,255,255,0.2)" stroke-width="0.5"/>
                    <path d="M12 2v6h6" fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="1.2" stroke-linejoin="round"/>
                    <rect x="7" y="9.5" width="8" height="1.5" rx="0.75" fill="rgba(255,255,255,0.7)"/>
                    <rect x="7" y="12.5" width="5.5" height="1.5" rx="0.75" fill="rgba(255,255,255,0.5)"/>
                    <rect x="7" y="15.5" width="7" height="1.5" rx="0.75" fill="rgba(255,255,255,0.5)"/>
                    <circle cx="17.5" cy="16.5" r="1.8" fill="none" stroke="rgba(255,255,255,0.5)" stroke-width="0.6"/>
                    <circle cx="17.5" cy="16.5" r="0.7" fill="white"/>
                </svg>
            </div>
            <span class="vd-drawer__logo-name">Vizzio<span>Docs</span></span>
        </a>
        <button class="vd-drawer__close" id="vd-drawer-close" aria-label="Tutup menu" type="button">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    {{-- Drawer Body --}}
    <div class="vd-drawer__body" id="vd-drawer-nav">

        <span class="vd-drawer__section-label">Navigasi</span>

        <a href="{{ route('home') }}" class="vd-drawer__link" tabindex="-1">
            <div class="vd-drawer__link-icon" style="background:linear-gradient(135deg,#6366f1,#7c3aed);box-shadow:0 2px 8px rgba(99,102,241,.35)"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg></div> Beranda
        </a>
        <a href="{{ route('fitur') }}" class="vd-drawer__link" tabindex="-1">
            <div class="vd-drawer__link-icon" style="background:linear-gradient(135deg,#6366f1,#8b5cf6);box-shadow:0 2px 8px rgba(99,102,241,.35)"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg></div> Semua Fitur
        </a>
        <a href="{{ route('about') }}" class="vd-drawer__link" tabindex="-1">
            <div class="vd-drawer__link-icon" style="background:linear-gradient(135deg,#0ea5e9,#3b82f6);box-shadow:0 2px 8px rgba(14,165,233,.35)"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div> Tentang Kami
        </a>

        <span class="vd-drawer__section-label">Organasi PDF</span>

        <a href="{{ route('compress.index') }}" class="vd-drawer__link" tabindex="-1">
            <div class="vd-drawer__link-icon" style="background:linear-gradient(135deg,#6366f1,#7c3aed);box-shadow:0 2px 8px rgba(99,102,241,.35)"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg></div> Kompres PDF
        </a>
        <a href="{{ route('merge.index') }}" class="vd-drawer__link" tabindex="-1">
            <div class="vd-drawer__link-icon" style="background:linear-gradient(135deg,#a855f7,#ec4899);box-shadow:0 2px 8px rgba(168,85,247,.35)"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/></svg></div> Gabungkan PDF
        </a>
        <a href="{{ route('split.index') }}" class="vd-drawer__link" tabindex="-1">
            <div class="vd-drawer__link-icon" style="background:linear-gradient(135deg,#0ea5e9,#06b6d4);box-shadow:0 2px 8px rgba(14,165,233,.35)"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879M12 12L9.121 9.121m0 5.758a3 3 0 11-4.243 4.243 3 3 0 014.243-4.243zm0-5.758a3 3 0 11-4.243-4.243 3 3 0 014.243 4.243z"/></svg></div> Pisahkan PDF
        </a>
        <a href="{{ route('crop.index') }}" class="vd-drawer__link" tabindex="-1">
            <div class="vd-drawer__link-icon" style="background:linear-gradient(135deg,#f43f5e,#ec4899);box-shadow:0 2px 8px rgba(244,63,94,.35)"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg></div> Crop PDF
        </a>
        <a href="{{ route('rotate.index') }}" class="vd-drawer__link" tabindex="-1">
            <div class="vd-drawer__link-icon" style="background:linear-gradient(135deg,#f59e0b,#ea580c);box-shadow:0 2px 8px rgba(245,158,11,.35)"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 8H17"/></svg></div> Putar PDF
        </a>

        <span class="vd-drawer__section-label">Kelola Halaman</span>

        <a href="{{ route('watermark-pdf.index') }}" class="vd-drawer__link" tabindex="-1">
            <div class="vd-drawer__link-icon" style="background:linear-gradient(135deg,#3b82f6,#6366f1);box-shadow:0 2px 8px rgba(59,130,246,.35)"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.395m3.42 3.42a15.995 15.995 0 004.764-4.648l3.876-5.814a1.151 1.151 0 00-1.597-1.597L14.146 6.32a15.996 15.996 0 00-4.649 4.763m3.42 3.42a6.776 6.776 0 00-3.42-3.42"/></svg></div> Watermark
        </a>
        <a href="{{ route('remove-pages.index') }}" class="vd-drawer__link" tabindex="-1">
            <div class="vd-drawer__link-icon" style="background:linear-gradient(135deg,#ef4444,#f43f5e);box-shadow:0 2px 8px rgba(239,68,68,.35)"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></div> Hapus Halaman
        </a>
        <a href="{{ route('extract-pages.index') }}" class="vd-drawer__link" tabindex="-1">
            <div class="vd-drawer__link-icon" style="background:linear-gradient(135deg,#6366f1,#3b82f6);box-shadow:0 2px 8px rgba(99,102,241,.35)"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg></div> Ekstrak Halaman
        </a>
        <a href="{{ route('organize-pdf.index') }}" class="vd-drawer__link" tabindex="-1">
            <div class="vd-drawer__link-icon" style="background:linear-gradient(135deg,#a855f7,#ec4899);box-shadow:0 2px 8px rgba(168,85,247,.35)"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg></div> Atur PDF
        </a>
        <a href="{{ route('page-numbers.index') }}" class="vd-drawer__link" tabindex="-1">
            <div class="vd-drawer__link-icon" style="background:linear-gradient(135deg,#f59e0b,#eab308);box-shadow:0 2px 8px rgba(245,158,11,.35)"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h2m-2 4h6m-4-2v4"/></svg></div> Nomor Halaman
        </a>

        <span class="vd-drawer__section-label">Konversi</span>

        <a href="{{ route('jpg-to-pdf.index') }}" class="vd-drawer__link" tabindex="-1">
            <div class="vd-drawer__link-icon" style="background:linear-gradient(135deg,#10b981,#16a34a);box-shadow:0 2px 8px rgba(16,185,129,.35)"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div> JPG ke PDF
        </a>
        <a href="{{ route('png-to-pdf.index') }}" class="vd-drawer__link" tabindex="-1">
            <div class="vd-drawer__link-icon" style="background:linear-gradient(135deg,#14b8a6,#06b6d4);box-shadow:0 2px 8px rgba(20,184,166,.35)"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div> PNG ke PDF
        </a>
        <a href="{{ route('word-to-pdf.index') }}" class="vd-drawer__link" tabindex="-1">
            <div class="vd-drawer__link-icon" style="background:linear-gradient(135deg,#3b82f6,#6366f1);box-shadow:0 2px 8px rgba(59,130,246,.35)"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg></div> Word ke PDF
        </a>
        <a href="{{ route('excel-to-pdf.index') }}" class="vd-drawer__link" tabindex="-1">
            <div class="vd-drawer__link-icon" style="background:linear-gradient(135deg,#22c55e,#10b981);box-shadow:0 2px 8px rgba(34,197,94,.35)"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></div> Excel ke PDF
        </a>
        <a href="{{ route('pdf-to-jpg.index') }}" class="vd-drawer__link" tabindex="-1">
            <div class="vd-drawer__link-icon" style="background:linear-gradient(135deg,#06b6d4,#0ea5e9);box-shadow:0 2px 8px rgba(6,182,212,.35)"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0022.5 18.75V5.25A2.25 2.25 0 0020.25 3H3.75A2.25 2.25 0 001.5 5.25v13.5A2.25 2.25 0 003.75 21zm16.5-13.5h.008v.008h-.008V7.5z"/></svg></div> PDF ke JPG
        </a>
        <a href="{{ route('pdf-to-word.index') }}" class="vd-drawer__link" tabindex="-1">
            <div class="vd-drawer__link-icon" style="background:linear-gradient(135deg,#3b82f6,#6366f1);box-shadow:0 2px 8px rgba(59,130,246,.35)"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></div> PDF ke Word
        </a>
        <a href="{{ route('pdf-to-txt.index') }}" class="vd-drawer__link" tabindex="-1">
            <div class="vd-drawer__link-icon" style="background:linear-gradient(135deg,#64748b,#475569);box-shadow:0 2px 8px rgba(100,116,139,.35)"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg></div> PDF ke TXT
        </a>
        <a href="{{ route('pdf-to-markdown.index') }}" class="vd-drawer__link" tabindex="-1">
            <div class="vd-drawer__link-icon" style="background:linear-gradient(135deg,#8b5cf6,#d946ef);box-shadow:0 2px 8px rgba(139,92,246,.35)"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg></div> PDF ke Markdown
        </a>

        <span class="vd-drawer__section-label">Konversi Lanjutan</span>

        <a href="{{ route('pdf-to-excel.index') }}" class="vd-drawer__link" tabindex="-1">
            <div class="vd-drawer__link-icon" style="background:linear-gradient(135deg,#10b981,#16a34a);box-shadow:0 2px 8px rgba(16,185,129,.35)"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 01-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-9.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-7.5A1.125 1.125 0 0112 18.375m9.75-12.75c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125m19.5 0v1.5c0 .621-.504 1.125-1.125 1.125M2.25 5.625v1.5c0 .621.504 1.125 1.125 1.125m0 0h17.25m-17.25 0h7.5c.621 0 1.125.504 1.125 1.125M3.375 8.25c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m17.25-3.75h-7.5c-.621 0-1.125.504-1.125 1.125m8.625-1.125c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125M12 10.875v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 10.875c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125M13.125 12h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125M20.625 12c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5M12 14.625v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 14.625c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125m0 0v.375"/></svg></div> PDF ke Excel
        </a>
        <a href="{{ route('html-to-pdf.index') }}" class="vd-drawer__link" tabindex="-1">
            <div class="vd-drawer__link-icon" style="background:linear-gradient(135deg,#0ea5e9,#3b82f6);box-shadow:0 2px 8px rgba(14,165,233,.35)"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418"/></svg></div> HTML ke PDF
        </a>
        <a href="{{ route('scan-to-pdf.index') }}" class="vd-drawer__link" tabindex="-1">
            <div class="vd-drawer__link-icon" style="background:linear-gradient(135deg,#f97316,#f59e0b);box-shadow:0 2px 8px rgba(249,115,22,.35)"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z"/><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z"/></svg></div> Scan ke PDF
        </a>
        <a href="{{ route('pdf-to-pptx.index') }}" class="vd-drawer__link" tabindex="-1">
            <div class="vd-drawer__link-icon" style="background:linear-gradient(135deg,#f97316,#ef4444);box-shadow:0 2px 8px rgba(249,115,22,.35)"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25A2.25 2.25 0 015.25 3h13.5A2.25 2.25 0 0121 5.25z"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 12V6m0 0l-2 2m2-2l2 2"/></svg></div> PDF ke PPT
        </a>
        <a href="{{ route('pptx-to-pdf.index') }}" class="vd-drawer__link" tabindex="-1">
            <div class="vd-drawer__link-icon" style="background:linear-gradient(135deg,#f97316,#ef4444);box-shadow:0 2px 8px rgba(249,115,22,.35)"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25A2.25 2.25 0 015.25 3h13.5A2.25 2.25 0 0121 5.25z"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0l-2-2m2 2l2-2"/></svg></div> PPT ke PDF
        </a>
        <a href="{{ route('pdf-to-pdfa.index') }}" class="vd-drawer__link" tabindex="-1">
            <div class="vd-drawer__link-icon" style="background:linear-gradient(135deg,#14b8a6,#06b6d4);box-shadow:0 2px 8px rgba(20,184,166,.35)"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg></div> PDF ke PDF/A
        </a>

        <span class="vd-drawer__section-label">Keamanan</span>

        <a href="{{ route('protect-pdf.index') }}" class="vd-drawer__link" tabindex="-1">
            <div class="vd-drawer__link-icon" style="background:linear-gradient(135deg,#ef4444,#f43f5e);box-shadow:0 2px 8px rgba(239,68,68,.35)"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg></div> Proteksi PDF
        </a>
        <a href="{{ route('unlock-pdf.index') }}" class="vd-drawer__link" tabindex="-1">
            <div class="vd-drawer__link-icon" style="background:linear-gradient(135deg,#22c55e,#10b981);box-shadow:0 2px 8px rgba(34,197,94,.35)"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5V6.75a4.5 4.5 0 119 0v3.75M3.75 21.75h16.5a1.5 1.5 0 001.5-1.5V12a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 12v8.25a1.5 1.5 0 001.5 1.5z"/></svg></div> Buka Kunci PDF
        </a>

        <span class="vd-drawer__section-label">Perbaikan</span>

        <a href="{{ route('optimize-pdf.index') }}" class="vd-drawer__link" tabindex="-1">
            <div class="vd-drawer__link-icon" style="background:linear-gradient(135deg,#84cc16,#16a34a);box-shadow:0 2px 8px rgba(132,204,22,.35)"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg></div> Optimasi PDF
        </a>
        <a href="{{ route('repair-pdf.index') }}" class="vd-drawer__link" tabindex="-1">
            <div class="vd-drawer__link-icon" style="background:linear-gradient(135deg,#64748b,#374151);box-shadow:0 2px 8px rgba(100,116,139,.35)"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A1.5 1.5 0 0021 17.25l-5.83-5.83m0 0a2.75 2.75 0 10-3.88-3.88m3.88 3.88L12 14M10.12 7.88L3 15v3a3 3 0 003 3h3l7.12-7.12m-7.12 7.12l1.41-1.41"/></svg></div> Perbaiki PDF
        </a>

    </div>

    {{-- Drawer Footer --}}
    <div class="vd-drawer__footer">
        @auth
            <div class="vd-drawer__profile-info">
                <div class="vd-drawer__profile-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
                <div class="vd-drawer__profile-text">
                    <div class="vd-drawer__profile-name">{{ Auth::user()->name }}</div>
                    <div class="vd-drawer__profile-email">{{ Auth::user()->email }}</div>
                    <div class="vd-drawer__profile-plan">
                        @if(Auth::user()->isPremium())
                            <span class="vd-drawer__plan-badge vd-drawer__plan-badge--premium">✦ Premium</span>
                            @if(Auth::user()->premium_expires_at)
                                <span class="vd-drawer__plan-expiry">Hingga {{ Auth::user()->premium_expires_at->format('d M Y \\j\\a\\m H:i') }}</span>
                            @endif
                        @else
                            <span class="vd-drawer__plan-badge vd-drawer__plan-badge--free">Gratis</span>
                            <span class="vd-drawer__quota-text">Kuota: {{ Auth::user()->getRemainingQuota() }}/20</span>
                        @endif
                    </div>
                </div>
            </div>
            @if(!Auth::user()->isPremium())
                <a href="{{ route('upgrade.index') }}" class="vd-drawer__btn-upgrade">Upgrade</a>
            @else
                <a href="{{ route('upgrade.index') }}" class="vd-drawer__btn-upgrade vd-drawer__btn-upgrade--premium">Cek Premium</a>
            @endif
            <form method="POST" action="{{ route('logout') }}" id="logout-form-drawer">@csrf</form>
            <a href="#" class="vd-drawer__btn-logout" onclick="event.preventDefault(); document.getElementById('logout-form-drawer').submit();">Keluar</a>
        @endauth
        @guest
            <a href="{{ route('login') }}" class="vd-drawer__btn-login" tabindex="-1" id="vd-drawer-login"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-in"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path><polyline points="10 17 15 12 10 7"></polyline><line x1="15" y1="12" x2="3" y2="12"></line></svg> Masuk</a>
            <a href="{{ route('register') }}" class="vd-drawer__btn-register" tabindex="-1" id="vd-drawer-register"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-star"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg> Daftar Gratis</a>
        @endguest
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════
     TOOL LOCK DATA (for JS)
═══════════════════════════════════════════════════════════ --}}
<script id="vd-tool-lock-data" type="application/json">{{ json_encode($lockedPaths) }}</script>

{{-- ═══════════════════════════════════════════════════════════
     JAVASCRIPT
═══════════════════════════════════════════════════════════ --}}
<script>
(function () {
    'use strict';

    // ── Elements ──────────────────────────────────────────────────────
    const header    = document.getElementById('vd-header');
    const hamBtn    = document.getElementById('vd-ham-btn');
    const drawer    = document.getElementById('vd-drawer');
    const closeBtn  = document.getElementById('vd-drawer-close');
    const backdrop  = document.getElementById('vd-backdrop');

    // Dropdown
    const toolsBtn      = document.getElementById('vd-tools-btn');
    const toolsDropdown = document.getElementById('vd-tools-dropdown');
    const toolsNav      = document.getElementById('vd-tools-nav');

    // ── Scroll Effect ─────────────────────────────────────────────────
    let lastScroll = 0;
    let ticking = false;

    function onScroll() {
        const y = window.scrollY;
        if (!ticking) {
            requestAnimationFrame(() => {
                header.classList.toggle('is-scrolled', y > 20);
                lastScroll = y;
                ticking = false;
            });
            ticking = true;
        }
    }

    window.addEventListener('scroll', onScroll, { passive: true });

    // ── Drawer State ──────────────────────────────────────────────────
    let isOpen = false;
    let prevFocus = null;

    function getFocusables() {
        return Array.from(
            drawer.querySelectorAll('a[href], button:not([disabled])')
        ).filter(el => el.offsetParent !== null);
    }

    function setDrawerTabIndex(val) {
        drawer.querySelectorAll('a, button').forEach(el => el.setAttribute('tabindex', val));
    }

    function openDrawer() {
        if (isOpen) return;
        isOpen = true;
        prevFocus = document.activeElement;

        document.documentElement.classList.add('vd-scroll-lock');
        document.body.classList.add('vd-scroll-lock');

        drawer.classList.add('is-open');
        backdrop.classList.add('is-visible');
        hamBtn.classList.add('is-active');

        drawer.setAttribute('aria-hidden', 'false');
        hamBtn.setAttribute('aria-expanded', 'true');

        setDrawerTabIndex(0);

        setTimeout(() => {
            const f = getFocusables();
            if (f.length) f[0].focus();
        }, 50);
    }

    function closeDrawer() {
        if (!isOpen) return;
        isOpen = false;

        drawer.classList.remove('is-open');
        backdrop.classList.remove('is-visible');
        hamBtn.classList.remove('is-active');

        drawer.setAttribute('aria-hidden', 'true');
        hamBtn.setAttribute('aria-expanded', 'false');

        setDrawerTabIndex(-1);

        setTimeout(() => {
            document.documentElement.classList.remove('vd-scroll-lock');
            document.body.classList.remove('vd-scroll-lock');
        }, 380);

        if (prevFocus) prevFocus.focus();
    }

    // ── Focus Trap ────────────────────────────────────────────────────
    function trapFocus(e) {
        if (!isOpen || e.key !== 'Tab') return;
        const f = getFocusables();
        if (!f.length) return;
        const first = f[0], last = f[f.length - 1];
        if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
        else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
    }

    // ── Dropdown Toggle ───────────────────────────────────────────────
    let dropdownOpen = false;

    function openDropdown() {
        dropdownOpen = true;
        toolsNav.classList.add('vd-nav__item--open');
        toolsBtn.setAttribute('aria-expanded', 'true');
    }

    function closeDropdown() {
        dropdownOpen = false;
        toolsNav.classList.remove('vd-nav__item--open');
        toolsBtn.setAttribute('aria-expanded', 'false');
    }

    // Hover intent
    let hoverTimer;
    toolsNav.addEventListener('mouseenter', () => {
        clearTimeout(hoverTimer);
        openDropdown();
    });
    toolsNav.addEventListener('mouseleave', () => {
        hoverTimer = setTimeout(closeDropdown, 150);
    });

    // Click toggle for keyboard/touch
    toolsBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        dropdownOpen ? closeDropdown() : openDropdown();
    });

    // Close dropdown on outside click
    document.addEventListener('click', (e) => {
        if (!toolsNav.contains(e.target)) closeDropdown();
    });

    // ── Profile Dropdown ─────────────────────────────────────────────────
    const profileBtn = document.getElementById('vd-profile-btn');
    const profileMenu = document.getElementById('vd-profile-menu');

    if (profileBtn && profileMenu) {
        let profileOpen = false;

        function openProfileMenu() {
            profileOpen = true;
            profileMenu.classList.add('is-visible');
            profileBtn.classList.add('is-open');
            profileBtn.setAttribute('aria-expanded', 'true');
        }

        function closeProfileMenu() {
            profileOpen = false;
            profileMenu.classList.remove('is-visible');
            profileBtn.classList.remove('is-open');
            profileBtn.setAttribute('aria-expanded', 'false');
        }

        profileBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            profileOpen ? closeProfileMenu() : openProfileMenu();
        });

        document.addEventListener('click', (e) => {
            if (profileOpen && !profileBtn.parentElement.contains(e.target)) {
                closeProfileMenu();
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && profileOpen) closeProfileMenu();
        });
    }

    // Close dropdown on Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            if (isOpen) { closeDrawer(); return; }
            closeDropdown();
        }
        trapFocus(e);
    });

    // ── Events ────────────────────────────────────────────────────────
    hamBtn.addEventListener('click', () => isOpen ? closeDrawer() : openDrawer());
    closeBtn.addEventListener('click', closeDrawer);
    backdrop.addEventListener('click', closeDrawer);

    // Close drawer on resize to desktop
    let resizeTimer;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
            if (window.innerWidth >= 900 && isOpen) closeDrawer();
        }, 100);
    });

    // ── Init ──────────────────────────────────────────────────────────
    setDrawerTabIndex(-1);
    drawer.setAttribute('aria-hidden', 'true');

    // ── Tool Lock Icon Injection ──────────────────────────────────────
    (function injectLockIcons() {
        var lockDataEl = document.getElementById('vd-tool-lock-data');
        if (!lockDataEl) return;
        var lockedPaths;
        try { lockedPaths = JSON.parse(lockDataEl.textContent || lockDataEl.innerText); } catch(e) { return; }
        if (!lockedPaths || !lockedPaths.length) return;

        // Build a set of locked paths for fast lookup
        var locked = {};
        for (var i = 0; i < lockedPaths.length; i++) {
            locked[lockedPaths[i]] = true;
        }

        // Lock SVG markup
        var lockSvg = '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>';

        // Helper: get pathname from an <a> href
        function getPathFromLink(link) {
            var href = link.getAttribute('href');
            if (!href) return null;
            try {
                var u = new URL(href, window.location.origin);
                return u.pathname;
            } catch(e) {
                if (href.charAt(0) === '/') return href;
                return null;
            }
        }

        // Toast notification for locked tools
        var toastEl = null;
        function showLockedToast() {
            if (toastEl) return;
            toastEl = document.createElement('div');
            toastEl.setAttribute('role', 'alert');
            toastEl.style.cssText = 'position:fixed;bottom:24px;left:50%;transform:translateX(-50%);z-index:99999;background:linear-gradient(135deg,#1e1e2e,#2a2a3e);color:#fff;padding:16px 28px;border-radius:14px;box-shadow:0 12px 48px rgba(0,0,0,.35),0 0 0 1px rgba(255,255,255,.06);font-family:"Inter",sans-serif;font-size:14px;font-weight:500;display:flex;align-items:center;gap:12px;pointer-events:auto;animation:vdToastIn 0.35s cubic-bezier(.34,1.56,.64,1);max-width:90vw;';
            toastEl.innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#f43f5e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg><span>Fitur ini <strong>sedang dalam perbaikan</strong>. Silakan coba lagi nanti.</span>';
            document.body.appendChild(toastEl);
            setTimeout(function() {
                if (toastEl) {
                    toastEl.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                    toastEl.style.opacity = '0';
                    toastEl.style.transform = 'translateX(-50%) translateY(20px)';
                    setTimeout(function() { if (toastEl) { toastEl.remove(); toastEl = null; } }, 350);
                }
            }, 4000);
        }

        // Inject lock icon + click blocker into a link
        function maybeInject(link) {
            var path = getPathFromLink(link);
            if (!path || !locked[path]) return;
            if (link.querySelector('.vd-tool-lock')) return;

            var span = document.createElement('span');
            span.className = 'vd-tool-lock vd-tool-lock--locked';
            span.setAttribute('aria-hidden', 'true');
            span.innerHTML = lockSvg;
            link.appendChild(span);

            // Block navigation on click
            link.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                showLockedToast();
            });
        }

        // 1) Desktop dropdown tool links
        var dropdownLinks = document.querySelectorAll('#vd-tools-dropdown .vd-dropdown__link');
        for (var j = 0; j < dropdownLinks.length; j++) {
            maybeInject(dropdownLinks[j]);
        }

        // 2) Mobile drawer tool links
        var drawerLinks = document.querySelectorAll('#vd-drawer-nav .vd-drawer__link');
        for (var k = 0; k < drawerLinks.length; k++) {
            maybeInject(drawerLinks[k]);
        }
    })();

    // ── Toast keyframe animation ───────────────────────────────────
    var styleSheet = document.createElement('style');
    styleSheet.textContent = '@keyframes vdToastIn { from { opacity:0; transform:translateX(-50%) translateY(20px) scale(.95); } to { opacity:1; transform:translateX(-50%) translateY(0) scale(1); } }';
    document.head.appendChild(styleSheet);

})();
</script>