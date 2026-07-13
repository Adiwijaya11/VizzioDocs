@extends('layouts.app')

@section('hideFooter')
@section('title', 'Upgrade ke Premium — VizzioDocs')

@section('content')
<style>
    /* ── Premium Upgrade Page - Full Redesign ──────────────────── */
    .upgrade-section {
        padding-top: 0;
        padding-bottom: 100px;
        background: linear-gradient(135deg, #eef2ff 0%, #f5f3ff 40%, #fdf2f8 70%, #f0f4ff 100%);
        min-height: 100vh;
        position: relative;
        overflow: hidden;
    }

    /* Decorative blobs */
    .upgrade-section::before {
        content: '';
        position: absolute;
        top: -200px;
        right: -200px;
        width: 600px;
        height: 600px;
        background: radial-gradient(circle, rgba(99,102,241,.08) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .upgrade-section::after {
        content: '';
        position: absolute;
        bottom: -150px;
        left: -150px;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(168,85,247,.07) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .upgrade-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 24px;
        position: relative;
        z-index: 1;
    }

    /* ── Hero Section — cards full viewport ───────── */
    .upgrade-hero {
        min-height: auto;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding-top: calc(var(--vd-nav-height, 70px) + 2px);
        padding-bottom: 40px;
    }

    /* ── Header (below hero) ─────────────────────── */
    .upgrade-header {
        text-align: center;
        padding: 80px 0 40px;
    }

    .upgrade-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: linear-gradient(135deg, #eef2ff, #ede9fe);
        border: 1.5px solid rgba(99,102,241,.25);
        padding: 8px 22px;
        border-radius: 100px;
        font-size: 13px;
        font-weight: 700;
        color: #4f46e5;
        letter-spacing: 0.03em;
        text-transform: uppercase;
        margin-bottom: 20px;
        box-shadow: 0 2px 12px rgba(99,102,241,.12);
    }

    .upgrade-header h1 {
        font-size: 48px;
        font-weight: 900;
        letter-spacing: -0.04em;
        color: #0f172a;
        line-height: 1.1;
        margin-bottom: 16px;
    }

    .upgrade-header h1 span {
        background: linear-gradient(135deg, #6366f1, #8b5cf6, #ec4899);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .upgrade-header p {
        font-size: 18px;
        color: #64748b;
        font-weight: 500;
        max-width: 600px;
        margin: 0 auto;
        line-height: 1.7;
    }

    /* ── Two Columns Layout ─────────────────────── */
    .upgrade-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 28px;
        align-items: start;
    }

    @media (max-width: 1000px) {
        .upgrade-grid {
            grid-template-columns: 1fr;
            max-width: 600px;
            margin: 0 auto;
        }
        .upgrade-header h1 { font-size: 34px; }
    }

    @media (max-width: 480px) {
        .upgrade-header h1 { font-size: 28px; }
    }

    /* ── Card Base ───────────────────────────────── */
    .upgrade-card {
        border-radius: 32px;
        background: rgba(255,255,255,.75);
        backdrop-filter: blur(24px) saturate(180%);
        -webkit-backdrop-filter: blur(24px) saturate(180%);
        border: 1px solid rgba(255,255,255,.8);
        box-shadow:
            0 4px 24px rgba(99,102,241,.06),
            0 1px 4px rgba(0,0,0,.04),
            inset 0 1px 0 rgba(255,255,255,.8);
        overflow: hidden;
        transition: box-shadow 0.35s ease, transform 0.35s ease;
    }

    .upgrade-card:hover {
        box-shadow:
            0 12px 48px rgba(99,102,241,.1),
            0 4px 12px rgba(0,0,0,.06),
            inset 0 1px 0 rgba(255,255,255,.8);
        transform: translateY(-3px);
    }

    .upgrade-card--premium {
        background: linear-gradient(145deg, rgba(255,255,255,.92), rgba(255,255,255,.78));
        position: relative;
    }

    .upgrade-card--premium::before {
        content: '';
        position: absolute;
        inset: -1px;
        border-radius: 32px;
        padding: 1.5px;
        background: linear-gradient(135deg, #6366f1, #8b5cf6, #ec4899);
        -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        pointer-events: none;
    }

    .upgrade-card__body {
        padding: 24px 28px;
    }

    @media (max-width: 480px) {
        .upgrade-card__body { padding: 18px 16px; }
    }

    /* ── Premium Card Specific ───────────────────── */
    .premium-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 14px;
    }

    .premium-icon {
        width: 42px;
        height: 42px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        box-shadow: 0 4px 16px rgba(99,102,241,.3);
        flex-shrink: 0;
    }

    .premium-header__text h2 {
        font-size: 18px;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.03em;
    }

    .premium-header__text p {
        font-size: 12px;
        color: #64748b;
        font-weight: 500;
        margin-top: 1px;
    }

    .premium-price {
        text-align: center;
        padding: 18px 16px;
        background: linear-gradient(135deg, #eef2ff, #ede9fe);
        border-radius: 16px;
        border: 1px solid rgba(99,102,241,.12);
        margin-bottom: 16px;
    }

    .premium-price__amount {
        font-size: 38px;
        font-weight: 900;
        letter-spacing: -0.04em;
        color: #0f172a;
        line-height: 1;
    }

    .premium-price__amount span {
        font-size: 20px;
        font-weight: 600;
        color: #94a3b8;
    }

    .premium-price__period {
        font-size: 13px;
        color: #64748b;
        font-weight: 500;
        margin-top: 4px;
    }

    .benefit-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-bottom: 14px;
    }

    .benefit-item {
        display: flex;
        align-items: center;
        gap: 14px;
        font-size: 15px;
        font-weight: 600;
        color: #334155;
    }

    .benefit-item__icon {
        width: 28px;
        height: 28px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 14px;
        font-weight: 700;
    }

    .benefit-item__icon--check {
        background: rgba(99,102,241,.1);
        color: #4f46e5;
    }

    .benefit-item__icon--star {
        background: rgba(168,85,247,.1);
        color: #7c3aed;
    }

    /* ── Radio Tiers ─────────────────────────────── */
    .premium-tiers {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-bottom: 24px;
    }

    .premium-tier {
        position: relative;
    }

    .premium-tier input[type="radio"] {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .premium-tier label {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 18px;
        background: #fff;
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        cursor: pointer;
        transition: all 0.25s ease;
        font-size: 14px;
        font-weight: 600;
        color: #334155;
    }

    .premium-tier label:hover {
        border-color: #a5b4fc;
        background: #eef2ff;
    }

    .premium-tier input[type="radio"]:checked + label {
        border-color: #6366f1;
        background: linear-gradient(135deg, #eef2ff, #ede9fe);
        box-shadow: 0 0 0 4px rgba(99,102,241,.12);
    }

    .premium-tier label .tier-info {
        display: flex;
        flex-direction: column;
    }

    .premium-tier label .tier-name {
        font-size: 16px;
        font-weight: 700;
        color: #0f172a;
    }

    .premium-tier label .tier-desc {
        font-size: 12px;
        font-weight: 500;
        color: #94a3b8;
        margin-top: 1px;
    }

    .premium-tier label .tier-price {
        font-size: 20px;
        font-weight: 800;
        color: #4f46e5;
    }

    .premium-tier label .tier-price small {
        font-size: 12px;
        font-weight: 500;
        color: #94a3b8;
    }

    .premium-tier .tier-badge {
        position: absolute;
        top: -10px;
        right: 18px;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        color: #fff;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        padding: 4px 14px;
        border-radius: 100px;
        pointer-events: none;
        box-shadow: 0 2px 8px rgba(99,102,241,.25);
    }

    /* ── Button Upgrade ──────────────────────────── */
    .btn-upgrade {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        width: 100%;
        padding: 14px 20px;
        font-size: 15px;
        font-weight: 800;
        font-family: inherit;
        border: none;
        border-radius: 16px;
        cursor: pointer;
        text-align: center;
        text-decoration: none;
        background: linear-gradient(135deg, #6366f1, #8b5cf6, #ec4899);
        color: #fff;
        box-shadow: 0 8px 32px rgba(99,102,241,.3);
        transition: transform 0.2s, box-shadow 0.25s;
        letter-spacing: -0.01em;
        position: relative;
        overflow: hidden;
    }

    .btn-upgrade::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, transparent 0%, rgba(255,255,255,.1) 50%, transparent 100%);
        transform: translateX(-100%);
        transition: transform 0.6s;
    }

    .btn-upgrade:hover::after {
        transform: translateX(100%);
    }

    .btn-upgrade:hover {
        transform: translateY(-3px);
        box-shadow: 0 16px 48px rgba(99,102,241,.4);
    }

    .btn-upgrade:active { transform: scale(0.97); }
    .btn-upgrade:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }

    /* --- Coupon Form --- */
    .coupon-form {
        display: flex;
        gap: 10px;
        margin-bottom: 24px;
    }

    .coupon-input {
        flex-grow: 1;
        padding: 12px 18px;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        font-size: 15px;
        color: #334155;
        background: #fff;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .coupon-input::placeholder {
        color: #94a3b8;
    }

    .coupon-input:focus {
        border-color: #a5b4fc;
        box-shadow: 0 0 0 3px rgba(99,102,241,.1);
        outline: none;
    }

    .btn-apply-coupon {
        padding: 12px 20px;
        background: linear-gradient(135deg, #8b5cf6, #ec4899);
        color: #fff;
        border: none;
        border-radius: 16px;
        font-size: 15px;
        font-weight: 700;
        cursor: pointer;
        transition: transform 0.2s, box-shadow 0.2s;
        box-shadow: 0 4px 16px rgba(139,92,246,.25);
    }

    .btn-apply-coupon:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(139,92,246,.35);
    }

    .btn-apply-coupon:active {
        transform: translateY(0);
        box-shadow: 0 2px 8px rgba(139,92,246,.25);
    }

    .coupon-message {
        padding: 14px 18px;
        border-radius: 14px;
        font-size: 14px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
        /* Default styles, will be overridden by JS */
        background: rgba(99,102,241,.08);
        border: 1px solid rgba(99,102,241,.15);
        color: #4f46e5;
    }

    .coupon-message.success {
        background: rgba(16, 185, 129, 0.1);
        border-color: rgba(16, 185, 129, 0.2);
        color: #0d9488;
    }

    .coupon-message.error {
        background: rgba(239, 68, 68, 0.1);
        border-color: rgba(239, 68, 68, 0.2);
        color: #dc2626;
    }

    @media (max-width: 480px) {
        .coupon-form {
            flex-direction: column;
        }
        .btn-apply-coupon {
            width: 100%;
        }
    }
    /* ── Comparison Table Card ───────────────────── */
    .comparison-header {
        font-size: 18px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 18px;
        letter-spacing: -0.03em;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .comparison-header svg {
        width: 20px;
        height: 20px;
        color: #6366f1;
        flex-shrink: 0;
    }

    .comparison-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 6px;
    }

    .comparison-table th {
        text-align: left;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #94a3b8;
        padding: 0 12px 8px;
    }

    .comparison-table th:first-child { padding-left: 0; }
    .comparison-table th:nth-child(2),
    .comparison-table th:nth-child(3) { text-align: center; }

    .comparison-table td {
        padding: 12px 10px;
        font-size: 13px;
        font-weight: 600;
        color: #334155;
        border-radius: 0;
    }

    .comparison-table tr td:first-child {
        border-radius: 12px 0 0 12px;
        padding-left: 14px;
    }

    .comparison-table tr td:last-child {
        border-radius: 0 12px 12px 0;
        padding-right: 14px;
    }

    .comparison-table td:nth-child(2),
    .comparison-table td:nth-child(3) {
        text-align: center;
        font-weight: 700;
    }

    .comparison-table td:nth-child(2) {
        color: #94a3b8;
    }

    .comparison-table td:nth-child(3) {
        color: #4f46e5;
    }

    .comparison-table .td-free {
        background: rgba(241,245,249,.5);
    }

    .comparison-table .td-premium {
        background: rgba(238,242,255,.6);
    }

    .comparison-table tr:hover td {
        background: rgba(255,255,255,.85);
    }

    .comparison-table tr:hover .td-premium {
        background: rgba(238,242,255,.9);
    }

    .badge-free {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: rgba(148,163,184,.1);
        color: #64748b;
        font-size: 11px;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 8px;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .badge-unlimited {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(99,102,241,.1);
        color: #4f46e5;
        font-size: 11px;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 8px;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .badge-unlimited svg,
    .badge-free svg {
        width: 12px;
        height: 12px;
        flex-shrink: 0;
    }

    /* ── Alerts ──────────────────────────────────── */
    .upgrade-alert {
        padding: 18px 22px;
        border-radius: 18px;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 28px;
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .upgrade-alert--success {
        background: rgba(99,102,241,.08);
        border: 1px solid rgba(99,102,241,.15);
        color: #4f46e5;
    }

    .upgrade-alert--info {
        background: rgba(6,182,212,.08);
        border: 1px solid rgba(6,182,212,.15);
        color: #0891b2;
    }

    .upgrade-alert--error {
        background: rgba(239,68,68,.08);
        border: 1px solid rgba(239,68,68,.15);
        color: #dc2626;
    }

    /* ── Already Premium Banner ──────────────────── */
    .premium-banner {
        text-align: center;
        padding: 48px 36px;
        border-radius: 24px;
        background: linear-gradient(135deg, #eef2ff, #ede9fe);
        border: 1.5px solid rgba(99,102,241,.15);
    }

    .premium-banner__icon {
        margin-bottom: 16px;
    }

    .premium-banner__title {
        font-size: 24px;
        font-weight: 900;
        color: #4f46e5;
        letter-spacing: -0.03em;
        margin-bottom: 8px;
    }

    .premium-banner__text {
        font-size: 15px;
        color: #6366f1;
        font-weight: 500;
        line-height: 1.6;
    }

    .premium-banner__expiry {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-top: 16px;
        padding: 8px 18px;
        background: rgba(99,102,241,.1);
        border: 1px solid rgba(99,102,241,.15);
        border-radius: 100px;
        font-size: 13px;
        font-weight: 700;
        color: #4f46e5;
    }

    /* ── Guest State ─────────────────────────────── */
    .guest-actions {
        text-align: center;
    }

    .guest-actions .btn-upgrade {
        margin-bottom: 16px;
    }

    .guest-actions .guest-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 14px;
        font-weight: 600;
        color: #6366f1;
        text-decoration: none;
        transition: color 0.2s;
        margin-top: 4px;
    }

    .guest-actions .guest-link:hover {
        color: #4f46e5;
    }

    .guest-actions .guest-link svg {
        transition: transform 0.2s;
    }

    .guest-actions .guest-link:hover svg {
        transform: rotate(72deg);
    }

    /* ── Tips Box ────────────────────────────────── */
    .tips-box {
        margin-top: 16px;
        padding: 14px 18px;
        border-radius: 14px;
        background: linear-gradient(135deg, #eef2ff, #ede9fe);
        border: 1px solid rgba(99,102,241,.12);
    }

    .tips-box p {
        font-size: 12px;
        font-weight: 600;
        color: #4f46e5;
        line-height: 1.5;
    }

    .tips-box svg {
        display: inline;
        vertical-align: middle;
    }

    /* ── Responsive fine-tune ────────────────────── */
    @media (max-width: 480px) {
        .premium-tier label {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }
        .premium-tier label .tier-price {
            align-self: flex-start;
        }
    }
</style>

<section class="upgrade-section">
    <div class="upgrade-container">

        {{-- ════════════════════════════════════════════════════════ --}}
        {{-- HERO SECTION — Cards full viewport (100svh) --}}
        {{-- ════════════════════════════════════════════════════════ --}}
        <div class="upgrade-hero">

            @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const applyCouponBtn = document.getElementById('applyCouponBtn');
            const couponCodeInput = document.getElementById('couponCode');
            const couponMessage = document.getElementById('couponMessage');

            if (applyCouponBtn && couponCodeInput && couponMessage) {
                applyCouponBtn.addEventListener('click', async function() {
                    const code = couponCodeInput.value.trim();
                    if (code === '') {
                        showMessage('error', 'Kode kupon tidak boleh kosong.', false);
                        return;
                    }

                    try {
                        const response = await fetch('{{ route('premium.applyCoupon') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: JSON.stringify({ coupon_code: code }),
                        });

                        const data = await response.json();

                        if (response.ok) {
                            showMessage('success', data.message, true);
                            // Optionally, update the price display or redirect
                        } else {
                            showMessage('error', data.message || 'Gagal menerapkan kupon.', false);
                        }
                    } catch (error) {
                        console.error('Error applying coupon:', error);
                        showMessage('error', 'Terjadi kesalahan. Coba lagi nanti.', false);
                    }
                });
            }

            function showMessage(type, text, isSuccess) {
                couponMessage.style.display = 'flex';
                couponMessage.innerHTML = `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>${text}`;
                couponMessage.classList.remove('success', 'error');
                couponMessage.classList.add(type);

                if (isSuccess) {
                    // Optionally, disable input and button after successful application
                    couponCodeInput.disabled = true;
                    applyCouponBtn.disabled = true;
                    applyCouponBtn.textContent = 'Diterapkan';
                }
            }
        });
    </script>
    @endpush

            @guest
                {{-- Not logged in --}}
                <div class="upgrade-grid" style="max-width:600px;margin:0 auto;">
                    <div class="upgrade-card upgrade-card--premium" style="grid-column:1/-1;">
                        <div class="upgrade-card__body guest-actions">
                            <div class="premium-icon" style="margin:0 auto 24px;">
                                <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/></svg>
                            </div>
                            <h2 style="font-size:24px;font-weight:800;color:#0f172a;margin-bottom:8px;">Mulai Perjalanan Premium</h2>
                            <p style="font-size:14px;color:#64748b;margin-bottom:28px;line-height:1.6;max-width:380px;margin-left:auto;margin-right:auto;">Masuk atau daftar akun dulu untuk mengakses halaman upgrade Premium.</p>
                            <a href="{{ route('login') }}" class="btn-upgrade">Masuk ke Akun</a>
                            <a href="{{ route('register') }}" class="guest-link">
                                Belum punya akun? Daftar
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            </a>
                        </div>
                    </div>
                </div>

            @elseif(Auth::user()->isPremium())
                {{-- Already Premium --}}
                <div class="upgrade-grid" style="max-width:600px;margin:0 auto;">
                    <div class="upgrade-card" style="grid-column:1/-1;">
                        <div class="upgrade-card__body">
                            <div class="premium-banner">
                                <div class="premium-banner__icon">
                                    <svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"><path d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/></svg>
                                </div>
                                <div class="premium-banner__title">Kamu sudah Premium!</div>
                                <div class="premium-banner__text">
                                    Nikmati semua 28 tools tanpa batas. Terima kasih telah menjadi bagian dari VizzioDocs Premium
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="display:inline;vertical-align:middle;"><path d="M7 10v8M21 10v4a5 5 0 0 1-5 5H3v-4a5 5 0 0 1 5-5h1c2 0 4-1 5-3l.5-1c.25-.5.75-1 1.5-1H21v4l-2 1"/></svg>
                                </div>
                                @if(Auth::user()->premium_expires_at)
                                    <div class="premium-banner__expiry">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                        Premium aktif hingga {{ Auth::user()->premium_expires_at->format('d M Y \j\a\m H:i') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            @else
                {{-- Free User — Two Columns --}}
                <div class="upgrade-grid">
                    {{-- LEFT: Premium Card --}}
                    <div class="upgrade-card upgrade-card--premium">
                        <div class="upgrade-card__body">
                            <div class="premium-header">
                                <div class="premium-icon">
                                    <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/></svg>
                                </div>
                                <div class="premium-header__text">
                                    <h2>Akses Premium</h2>
                                    <p>Nikmati semua fitur tanpa batasan</p>
                                </div>
                            </div>

                            <div class="premium-price">
                                <div class="premium-price__amount">Mulai <span>Rp5.000</span></div>
                                <div class="premium-price__period">Pilih periode — akses premium sesuai kebutuhan</div>
                            </div>

                            <div class="benefit-list">
                                <div class="benefit-item">
                                    <div class="benefit-item__icon benefit-item__icon--check">✓</div>
                                    Akses tanpa batas ke 28 tools
                                </div>
                                <div class="benefit-item">
                                    <div class="benefit-item__icon benefit-item__icon--check">✓</div>
                                    Tanpa kuota harian
                                </div>
                                <div class="benefit-item">
                                    <div class="benefit-item__icon benefit-item__icon--check">✓</div>
                                    Processing prioritas lebih cepat
                                </div>
                                <div class="benefit-item">
                                    <div class="benefit-item__icon benefit-item__icon--star">✦</div>
                                    Support pengembangan VizzioDocs
                                </div>
                            </div>

                            <form method="POST" action="{{ route('premium.purchase') }}" id="upgradeForm">
                                @csrf

                                <div class="premium-tiers">
                                    <div class="premium-tier">
                                        <input type="radio" name="period" value="daily" id="periodDaily" checked>
                                        <label for="periodDaily">
                                            <span class="tier-info">
                                                <span class="tier-name">Harian</span>
                                                <span class="tier-desc">Akses premium 1 × 24 jam</span>
                                            </span>
                                            <span class="tier-price">Rp5.000 <small>/hari</small></span>
                                        </label>
                                    </div>

                                    <div class="premium-tier">
                                        <input type="radio" name="period" value="weekly" id="periodWeekly">
                                        <label for="periodWeekly">
                                            <span class="tier-info">
                                                <span class="tier-name">Mingguan</span>
                                                <span class="tier-desc">Akses premium 7 hari penuh</span>
                                            </span>
                                            <span class="tier-price">Rp20.000 <small>/minggu</small></span>
                                        </label>
                                        <span class="tier-badge">Best value</span>
                                    </div>

                                    <div class="premium-tier">
                                        <input type="radio" name="period" value="monthly" id="periodMonthly">
                                        <label for="periodMonthly">
                                            <span class="tier-info">
                                                <span class="tier-name">Bulanan</span>
                                                <span class="tier-desc">Akses premium 30 hari penuh</span>
                                            </span>
                                            <span class="tier-price">Rp35.000 <small>/bulan</small></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="coupon-form" id="couponForm">
                                    <input type="text" name="coupon_code" id="couponCode" placeholder="Masukkan kode kupon" class="coupon-input">
                                    <button type="button" id="applyCouponBtn" class="btn-apply-coupon">Terapkan</button>
                                </div>

                                <div class="coupon-message" id="couponMessage" style="display: none; margin-bottom: 16px;"></div>

                                <button type="submit" class="btn-upgrade">
                                    Upgrade Sekarang
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- RIGHT: Free vs Premium Comparison --}}
                    <div class="upgrade-card">
                        <div class="upgrade-card__body">
                            <div class="comparison-header">
                                <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Bandingkan Paket
                            </div>

                            <table class="comparison-table">
                                <thead>
                                    <tr>
                                        <th>Fitur</th>
                                        <th><span class="badge-free">Gratis</span></th>
                                        <th><span class="badge-unlimited"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg> Premium</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Kuota Harian</td>
                                        <td class="td-free">20 tool/hari</td>
                                        <td class="td-premium">Tak terbatas</td>
                                    </tr>
                                    <tr>
                                        <td>Semua 28 Tools</td>
                                        <td class="td-free"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:inline;vertical-align:middle;"><polyline points="20 6 9 17 4 12"/></svg></td>
                                        <td class="td-premium"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:inline;vertical-align:middle;"><polyline points="20 6 9 17 4 12"/></svg></td>
                                    </tr>
                                    <tr>
                                        <td>Kecepatan Processing</td>
                                        <td class="td-free">Standar</td>
                                        <td class="td-premium"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#4f46e5" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="display:inline;vertical-align:middle;"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg> Prioritas</td>
                                    </tr>
                                    <tr>
                                        <td>Iklan</td>
                                        <td class="td-free">Ada</td>
                                        <td class="td-premium"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#4f46e5" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="display:inline;vertical-align:middle;"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg> Bebas iklan</td>
                                    </tr>
                                    <tr>
                                        <td>Batas Upload</td>
                                        <td class="td-free">{{ $adminMaxFileSizeMB }} MB/file</td>
                                        <td class="td-premium">{{ $adminMaxFileSizePremiumMB }} MB/file</td>
                                    </tr>
                                    <tr>
                                        <td>Akses Fitur Baru</td>
                                        <td class="td-free">Standar</td>
                                        <td class="td-premium"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#4f46e5" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="display:inline;vertical-align:middle;"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg> Early access</td>
                                    </tr>
                                    <tr>
                                        <td>Support</td>
                                        <td class="td-free">Email</td>
                                        <td class="td-premium"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#4f46e5" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="display:inline;vertical-align:middle;"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg> Prioritas</td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="tips-box">
                                <p>
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#4f46e5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18h6"/><path d="M10 22h4"/><path d="M15.09 14c.18-.98.65-1.74 1.41-2.5A4.65 4.65 0 0 0 18 8 6 6 0 0 0 6 8c0 1 .23 2.23 1.5 3.5A4.61 4.61 0 0 1 8.91 14"/></svg>
                                    <strong>Tips:</strong> Dengan Premium, kamu bisa menggunakan semua tools tanpa khawatir kuota habis. Cocok untuk kerja profesional dan kebutuhan dokumen harian.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endguest

        </div>
        {{-- ═══ End Hero ═══ --}}

        {{-- ════════════════════════════════════════════════════════ --}}
        {{-- HEADER + Alerts - Di bawah hero section --}}
        {{-- ════════════════════════════════════════════════════════ --}}
        <div class="upgrade-header">
            <div class="upgrade-badge">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/></svg>
                Premium
            </div>
            <h1>Upgrade ke <span>Premium</span></h1>
            <p>Buka akses tanpa batas ke seluruh 28 tools manipulasi dokumen. Bebas kuota, bebas hambatan.</p>
        </div>

        @if(session('success'))
            <div class="upgrade-alert upgrade-alert--success" style="max-width:800px;margin:0 auto 32px;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('info'))
            <div class="upgrade-alert upgrade-alert--info" style="max-width:800px;margin:0 auto 32px;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                {{ session('info') }}
            </div>
        @endif
        @if(session('error'))
            <div class="upgrade-alert upgrade-alert--error" style="max-width:800px;margin:0 auto 32px;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                {{ session('error') }}
            </div>
        @endif

    </div>
</section>
@endsection
