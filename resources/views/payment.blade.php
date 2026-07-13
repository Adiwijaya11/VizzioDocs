@extends('layouts.app')

@section('title', 'Pembayaran Premium — VizzioDocs')

@section('content')
<style>
    /* ── Payment Page Styles ───────────────────────────── */
    .payment-section {
        padding-top: calc(var(--vd-nav-height, 70px) + 2px);
        padding-bottom: 80px;
        min-height: 100vh;
        background: linear-gradient(135deg, #eef2ff 0%, #f5f3ff 40%, #fdf2f8 70%, #f0f4ff 100%);
        position: relative;
        overflow: hidden;
    }

    .payment-section::before {
        content: '';
        position: absolute;
        top: -150px;
        right: -150px;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(99,102,241,.08) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .payment-section::after {
        content: '';
        position: absolute;
        bottom: -100px;
        left: -100px;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(168,85,247,.07) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .payment-container {
        max-width: 840px;
        margin: 0 auto;
        padding: 0 24px;
        position: relative;
        z-index: 1;
    }

    /* ── Header ─────────────────────────── */
    .payment-header {
        text-align: center;
        padding: 40px 0 32px;
    }

    .payment-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: linear-gradient(135deg, #eef2ff, #ede9fe);
        border: 1.5px solid rgba(99,102,241,.25);
        padding: 6px 20px;
        border-radius: 100px;
        font-size: 12px;
        font-weight: 700;
        color: #4f46e5;
        letter-spacing: 0.03em;
        text-transform: uppercase;
        margin-bottom: 16px;
        box-shadow: 0 2px 12px rgba(99,102,241,.12);
    }

    .payment-header h1 {
        font-size: 36px;
        font-weight: 900;
        letter-spacing: -0.04em;
        color: #0f172a;
        line-height: 1.15;
        margin-bottom: 10px;
    }

    .payment-header h1 span {
        background: linear-gradient(135deg, #6366f1, #8b5cf6, #ec4899);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .payment-header p {
        font-size: 16px;
        color: #64748b;
        font-weight: 500;
        max-width: 480px;
        margin: 0 auto;
        line-height: 1.6;
    }

    /* ── Card ───────────────────────────── */
    .payment-card {
        border-radius: 32px;
        background: rgba(255,255,255,.75);
        backdrop-filter: blur(24px) saturate(180%);
        -webkit-backdrop-filter: blur(24px) saturate(180%);
        border: 1px solid rgba(255,255,255,.8);
        box-shadow: 0 4px 24px rgba(99,102,241,.06), 0 1px 4px rgba(0,0,0,.04), inset 0 1px 0 rgba(255,255,255,.8);
        overflow: hidden;
        margin-bottom: 24px;
    }

    .payment-card--premium {
        background: linear-gradient(145deg, rgba(255,255,255,.92), rgba(255,255,255,.78));
        position: relative;
    }

    .payment-card--premium::before {
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

    .payment-card__body {
        padding: 28px 32px;
    }

    @media (max-width: 520px) {
        .payment-card__body { padding: 20px 18px; }
    }

    /* ── Order Summary ───────────────────── */
    .order-summary {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 18px 20px;
        background: linear-gradient(135deg, #eef2ff, #ede9fe);
        border-radius: 18px;
        border: 1px solid rgba(99,102,241,.12);
        margin-bottom: 28px;
    }

    .order-summary__icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        box-shadow: 0 4px 16px rgba(99,102,241,.3);
    }

    .order-summary__icon svg {
        width: 24px;
        height: 24px;
        color: #fff;
    }

    .order-summary__info {
        flex: 1;
        min-width: 0;
    }

    .order-summary__plan {
        font-size: 18px;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.03em;
    }

    .order-summary__invoice {
        font-size: 13px;
        color: #64748b;
        font-weight: 500;
        margin-top: 2px;
    }

    .order-summary__amount {
        text-align: right;
        flex-shrink: 0;
    }

    .order-summary__price {
        font-size: 24px;
        font-weight: 900;
        color: #4f46e5;
        letter-spacing: -0.03em;
    }

    .order-summary__price small {
        font-size: 13px;
        font-weight: 600;
        color: #94a3b8;
    }

    .order-summary__expiry {
        font-size: 12px;
        font-weight: 600;
        color: #f59e0b;
        margin-top: 2px;
    }

    /* ── Price Detail ────────────────────── */
    .price-detail {
        margin-bottom: 24px;
    }

    .price-detail__row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        font-size: 15px;
        font-weight: 600;
        color: #475569;
        border-bottom: 1px solid rgba(226,232,240,.6);
    }

    .price-detail__row:last-child {
        border-bottom: none;
        padding-top: 14px;
        font-size: 18px;
        font-weight: 800;
        color: #0f172a;
    }

    .price-detail__row .discount {
        color: #0d9488;
    }

    .price-detail__row .total {
        color: #4f46e5;
        font-size: 22px;
    }

    /* ── Payment Methods ──────────────────── */
    .pm-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 16px;
        font-size: 16px;
        font-weight: 800;
        color: #0f172a;
    }

    .pm-header svg {
        width: 20px;
        height: 20px;
        color: #6366f1;
        flex-shrink: 0;
    }

    .pm-group {
        margin-bottom: 20px;
    }

    .pm-group__title {
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #94a3b8;
        margin-bottom: 10px;
        padding-left: 4px;
    }

    .pm-option {
        position: relative;
        margin-bottom: 8px;
    }

    .pm-option input[type="radio"] {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .pm-option label {
        display: flex;
        align-items: center;
        gap: 14px;
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

    .pm-option label:hover {
        border-color: #a5b4fc;
        background: #f8faff;
    }

    .pm-option input[type="radio"]:checked + label {
        border-color: #6366f1;
        background: linear-gradient(135deg, #eef2ff, #ede9fe);
        box-shadow: 0 0 0 4px rgba(99,102,241,.12);
    }

    .pm-option__icon {
        width: 38px;
        height: 38px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 18px;
        background: rgba(99,102,241,.1);
        color: #4f46e5;
        transition: all 0.3s;
    }

    .pm-option input[type="radio"]:checked + label .pm-option__icon {
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        color: #fff;
        box-shadow: 0 4px 12px rgba(99,102,241,.25);
    }

    .pm-option__info {
        flex: 1;
    }

    .pm-option__name {
        font-size: 15px;
        font-weight: 700;
        color: #0f172a;
    }

    .pm-option__desc {
        font-size: 12px;
        font-weight: 500;
        color: #94a3b8;
        margin-top: 1px;
    }

    /* ── Pay Button ──────────────────────── */
    .btn-pay {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        width: 100%;
        padding: 16px 24px;
        font-size: 16px;
        font-weight: 800;
        font-family: inherit;
        border: none;
        border-radius: 16px;
        cursor: pointer;
        text-align: center;
        background: linear-gradient(135deg, #6366f1, #8b5cf6, #ec4899);
        color: #fff;
        box-shadow: 0 8px 32px rgba(99,102,241,.3);
        transition: transform 0.2s, box-shadow 0.25s, opacity 0.25s;
        letter-spacing: -0.01em;
        position: relative;
        overflow: hidden;
    }

    .btn-pay::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, transparent 0%, rgba(255,255,255,.1) 50%, transparent 100%);
        transform: translateX(-100%);
        transition: transform 0.6s;
    }

    .btn-pay:hover::after {
        transform: translateX(100%);
    }

    .btn-pay:hover {
        transform: translateY(-3px);
        box-shadow: 0 16px 48px rgba(99,102,241,.4);
    }

    .btn-pay:active { transform: scale(0.97); }
    .btn-pay:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }
    .btn-pay:disabled::after { display: none; }

    .btn-pay .spinner {
        display: none;
        width: 20px;
        height: 20px;
        border: 3px solid rgba(255,255,255,.3);
        border-top-color: #fff;
        border-radius: 50%;
        animation: spin 0.7s linear infinite;
    }

    .btn-pay .btn-text {
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-pay.loading .spinner { display: inline-block; }
    .btn-pay.loading .btn-text { display: none; }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* ── Payment Instructions ─────────────── */
    .payment-instructions {
        display: none;
        animation: fadeInUp 0.5s ease;
    }

    .payment-instructions.show {
        display: block;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .instructions-card {
        padding: 24px 28px;
        background: linear-gradient(135deg, #f0fdf4, #ecfdf5);
        border: 1px solid rgba(16,185,129,.2);
        border-radius: 20px;
        margin-top: 20px;
    }

    .instructions-card--pending {
        background: linear-gradient(135deg, #fffbeb, #fef3c7);
        border-color: rgba(245,158,11,.2);
    }

    .instructions-card__icon {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
        font-size: 28px;
    }

    .instructions-card__icon--success {
        background: linear-gradient(135deg, #10b981, #059669);
        box-shadow: 0 8px 24px rgba(16,185,129,.3);
    }

    .instructions-card__icon--pending {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        box-shadow: 0 8px 24px rgba(245,158,11,.3);
    }

    .instructions-card__title {
        text-align: center;
        font-size: 20px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 6px;
        letter-spacing: -0.03em;
    }

    .instructions-card__subtitle {
        text-align: center;
        font-size: 14px;
        color: #64748b;
        font-weight: 500;
        margin-bottom: 20px;
    }

    .instructions-detail {
        padding: 18px 20px;
        background: #fff;
        border-radius: 16px;
        border: 1px solid rgba(226,232,240,.6);
        margin-bottom: 14px;
    }

    .instructions-detail__label {
        font-size: 12px;
        font-weight: 700;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 6px;
    }

    .instructions-detail__value {
        font-size: 20px;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: 0.02em;
        font-family: 'Courier New', monospace;
        word-break: break-all;
    }

    .instructions-detail__copy {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-top: 8px;
        padding: 6px 16px;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        color: #6366f1;
        background: #fff;
        cursor: pointer;
        transition: all 0.2s;
    }

    .instructions-detail__copy:hover {
        background: #eef2ff;
        border-color: #a5b4fc;
    }

    .instructions-detail__copy.copied {
        background: #d1fae5;
        border-color: #6ee7b7;
        color: #0d9488;
    }

    .instructions-steps {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .instructions-steps li {
        display: flex;
        gap: 12px;
        padding: 10px 0;
        font-size: 14px;
        font-weight: 500;
        color: #475569;
        border-bottom: 1px solid rgba(226,232,240,.4);
        line-height: 1.5;
    }

    .instructions-steps li:last-child {
        border-bottom: none;
    }

    .instructions-steps__num {
        width: 24px;
        height: 24px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 12px;
        font-weight: 800;
        background: rgba(99,102,241,.1);
        color: #4f46e5;
    }

    .instructions-steps li svg {
        width: 20px;
        height: 20px;
        flex-shrink: 0;
        color: #10b981;
    }

    /* ── QR Code ─────────────────────────── */
    .qr-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 10px 0;
    }

    .qr-container img {
        width: 200px;
        height: 200px;
        border-radius: 16px;
        background: #fff;
        padding: 12px;
        border: 1px solid rgba(226,232,240,.6);
        box-shadow: 0 4px 16px rgba(0,0,0,.06);
    }

    .qr-container__label {
        margin-top: 12px;
        font-size: 13px;
        font-weight: 600;
        color: #94a3b8;
    }

    /* ── Status Polling ───────────────────── */
    .status-poll {
        display: none;
        text-align: center;
        margin-top: 16px;
        padding: 16px;
        background: rgba(99,102,241,.06);
        border-radius: 14px;
        border: 1px solid rgba(99,102,241,.1);
    }

    .status-poll.show { display: block; }

    .status-poll__text {
        font-size: 14px;
        font-weight: 600;
        color: #4f46e5;
    }

    .status-poll__dots {
        display: inline-flex;
        gap: 4px;
        margin-left: 4px;
    }

    .status-poll__dots span {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #6366f1;
        animation: bounce 1.4s infinite ease-in-out both;
    }

    .status-poll__dots span:nth-child(1) { animation-delay: -0.32s; }
    .status-poll__dots span:nth-child(2) { animation-delay: -0.16s; }
    .status-poll__dots span:nth-child(3) { animation-delay: 0s; }

    @keyframes bounce {
        0%, 80%, 100% { transform: scale(0); }
        40% { transform: scale(1); }
    }

    /* ── Success State ────────────────────── */
    .success-card {
        text-align: center;
        padding: 40px 32px;
    }

    .success-card__icon {
        width: 72px;
        height: 72px;
        border-radius: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        background: linear-gradient(135deg, #10b981, #059669);
        box-shadow: 0 8px 32px rgba(16,185,129,.3);
        animation: scaleIn 0.5s cubic-bezier(.34,1.56,.64,1);
    }

    .success-card__title {
        font-size: 24px;
        font-weight: 900;
        color: #0f172a;
        margin-bottom: 8px;
        letter-spacing: -0.03em;
    }

    .success-card__text {
        font-size: 15px;
        color: #64748b;
        font-weight: 500;
        line-height: 1.6;
        max-width: 400px;
        margin: 0 auto 28px;
    }

    .btn-dashboard {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 14px 32px;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        color: #fff;
        border: none;
        border-radius: 14px;
        font-size: 15px;
        font-weight: 700;
        font-family: inherit;
        cursor: pointer;
        text-decoration: none;
        transition: transform 0.2s, box-shadow 0.25s;
        box-shadow: 0 6px 24px rgba(99,102,241,.25);
    }

    .btn-dashboard:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 32px rgba(99,102,241,.35);
    }

    @keyframes scaleIn {
        from { transform: scale(0); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }

    /* ── Expired / Error State ────────────── */
    .expired-card {
        text-align: center;
        padding: 40px 32px;
    }

    .expired-card__icon {
        width: 72px;
        height: 72px;
        border-radius: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        background: linear-gradient(135deg, #ef4444, #dc2626);
        box-shadow: 0 8px 32px rgba(239,68,68,.3);
    }

    .expired-card__title {
        font-size: 24px;
        font-weight: 900;
        color: #0f172a;
        margin-bottom: 8px;
    }

    .expired-card__text {
        font-size: 15px;
        color: #64748b;
        margin-bottom: 28px;
    }

    /* ── Error message ────────────────────── */
    .payment-error {
        display: none;
        padding: 14px 18px;
        background: rgba(239,68,68,.1);
        border: 1px solid rgba(239,68,68,.2);
        border-radius: 14px;
        font-size: 14px;
        font-weight: 600;
        color: #dc2626;
        margin-bottom: 16px;
        align-items: center;
        gap: 10px;
    }

    .payment-error.show {
        display: flex;
    }

    .payment-error svg {
        width: 20px;
        height: 20px;
        flex-shrink: 0;
    }

    /* ── Footer actions ───────────────────── */
    .payment-footer {
        display: flex;
        justify-content: center;
        gap: 16px;
        flex-wrap: wrap;
        margin-top: 24px;
    }

    .payment-footer a {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 14px;
        font-weight: 600;
        color: #6366f1;
        text-decoration: none;
        transition: color 0.2s;
    }

    .payment-footer a:hover {
        color: #4f46e5;
    }

    .payment-footer a svg {
        transition: transform 0.2s;
    }

    .payment-footer a:hover svg {
        transform: translateX(-3px);
    }

    @media (max-width: 520px) {
        .payment-header h1 { font-size: 26px; }
        .order-summary { flex-wrap: wrap; }
        .order-summary__amount { width: 100%; text-align: left; }
        .payment-footer { flex-direction: column; align-items: center; }
    }
</style>

<section class="payment-section">
    <div class="payment-container">

        {{-- Header --}}
        <div class="payment-header">
            <div class="payment-badge">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/></svg>
                Pembayaran Premium
            </div>
            <h1>Selesaikan <span>Pembayaran</span></h1>
            <p>Pilih metode pembayaran dan lakukan pembayaran untuk menikmati akses Premium.</p>
        </div>

        {{-- Payment Card --}}
        @if ($payment->transaction_status === 'paid')
            {{-- ✅ Already Paid --}}
            <div class="payment-card payment-card--premium">
                <div class="payment-card__body success-card">
                    <div class="success-card__icon">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                    <div class="success-card__title">Pembayaran Berhasil!</div>
                    <div class="success-card__text">
                        Selamat! Kamu sekarang sudah menjadi pengguna VizzioDocs Premium. Nikmati semua tools tanpa batas.
                    </div>
                    <a href="{{ route('home') }}" class="btn-dashboard">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                        Ke Beranda
                    </a>
                </div>
            </div>
        @elseif ($payment->transaction_status === 'expired')
            {{-- ⏰ Expired --}}
            <div class="payment-card payment-card--premium">
                <div class="payment-card__body expired-card">
                    <div class="expired-card__icon">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                    </div>
                    <div class="expired-card__title">Waktu Pembayaran Habis</div>
                    <div class="expired-card__text">
                        Invoice ini sudah kadaluwarsa. Silakan lakukan upgrade kembali untuk mendapatkan invoice baru.
                    </div>
                    <a href="{{ route('upgrade.index') }}" class="btn-dashboard">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg>
                        Upgrade Lagi
                    </a>
                </div>
            </div>
        @elseif ($payment->transaction_status === 'failed')
            {{-- ❌ Failed --}}
            <div class="payment-card payment-card--premium">
                <div class="payment-card__body expired-card">
                    <div class="expired-card__icon">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                    </div>
                    <div class="expired-card__title">Pembayaran Gagal</div>
                    <div class="expired-card__text">
                        Pembayaran tidak dapat diproses. Silakan coba lagi dengan metode pembayaran yang berbeda.
                    </div>
                    <a href="{{ route('upgrade.index') }}" class="btn-dashboard">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg>
                        Coba Lagi
                    </a>
                </div>
            </div>
        @else
            {{-- 🔄 Pending — Active Payment --}}
            <div class="payment-card payment-card--premium">
                <div class="payment-card__body">

                    {{-- Order Summary --}}
                    <div class="order-summary">
                        <div class="order-summary__icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/></svg>
                        </div>
                        <div class="order-summary__info">
                            <div class="order-summary__plan">Paket {{ $payment->plan_name }}</div>
                            <div class="order-summary__invoice">Invoice: {{ $payment->invoice }}</div>
                        </div>
                        <div class="order-summary__amount">
                            <div class="order-summary__price">Rp {{ number_format($payment->final_price, 0, ',', '.') }}</div>
                            @if ($payment->expired_at)
                                <div class="order-summary__expiry">
                                    ⏱ {{ $payment->expired_at->diffForHumans() }}
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Price Detail --}}
                    <div class="price-detail">
                        <div class="price-detail__row">
                            <span>Harga {{ $payment->plan_name }}</span>
                            <span>Rp {{ number_format($payment->original_price, 0, ',', '.') }}</span>
                        </div>
                        @if ($payment->discount > 0)
                            <div class="price-detail__row">
                                <span>Diskon</span>
                                <span class="discount">- Rp {{ number_format($payment->discount, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        <div class="price-detail__row">
                            <span>Total Pembayaran</span>
                            <span class="total">Rp {{ number_format($payment->final_price, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    {{-- Payment Error --}}
                    <div class="payment-error" id="paymentError">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                        <span id="paymentErrorText"></span>
                    </div>

                    {{-- Payment Methods --}}
                    <div id="paymentMethodSection">
                        <div class="pm-header">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                            Pilih Metode Pembayaran
                        </div>

                        {{-- QRIS --}}
                        <div class="pm-group">
                            <div class="pm-group__title">QRIS</div>
                            <div class="pm-option">
                                <input type="radio" name="payment_method" value="qris" id="pmQris">
                                <label for="pmQris">
                                    <div class="pm-option__icon">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                                    </div>
                                    <div class="pm-option__info">
                                        <div class="pm-option__name">QRIS</div>
                                        <div class="pm-option__desc">Scan QR via Gojek, OVO, DANA, ShopeePay, dll.</div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        {{-- Virtual Account --}}
                        <div class="pm-group">
                            <div class="pm-group__title">Virtual Account</div>
                            <div class="pm-option">
                                <input type="radio" name="payment_method" value="bca" id="pmBca">
                                <label for="pmBca">
                                    <div class="pm-option__icon" style="font-size:14px;font-weight:900;letter-spacing:-0.03em;">BCA</div>
                                    <div class="pm-option__info">
                                        <div class="pm-option__name">BCA Virtual Account</div>
                                        <div class="pm-option__desc">Transfer via BCA Mobile / ATM / Internet Banking</div>
                                    </div>
                                </label>
                            </div>
                            <div class="pm-option">
                                <input type="radio" name="payment_method" value="bni" id="pmBni">
                                <label for="pmBni">
                                    <div class="pm-option__icon" style="font-size:14px;font-weight:900;letter-spacing:-0.03em;">BNI</div>
                                    <div class="pm-option__info">
                                        <div class="pm-option__name">BNI Virtual Account</div>
                                        <div class="pm-option__desc">Transfer via BNI Mobile / ATM / Internet Banking</div>
                                    </div>
                                </label>
                            </div>
                            <div class="pm-option">
                                <input type="radio" name="payment_method" value="mandiri" id="pmMandiri">
                                <label for="pmMandiri">
                                    <div class="pm-option__icon" style="font-size:13px;font-weight:900;letter-spacing:-0.03em;">MN</div>
                                    <div class="pm-option__info">
                                        <div class="pm-option__name">Mandiri Virtual Account</div>
                                        <div class="pm-option__desc">Transfer via Mandiri Mobile / ATM / Internet Banking</div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        {{-- E-Wallet --}}
                        <div class="pm-group">
                            <div class="pm-group__title">E-Wallet</div>
                            <div class="pm-option">
                                <input type="radio" name="payment_method" value="gopay" id="pmGopay">
                                <label for="pmGopay">
                                    <div class="pm-option__icon" style="font-weight:900;font-size:13px;">Go</div>
                                    <div class="pm-option__info">
                                        <div class="pm-option__name">GoPay</div>
                                        <div class="pm-option__desc">Bayar menggunakan GoPay</div>
                                    </div>
                                </label>
                            </div>
                            <div class="pm-option">
                                <input type="radio" name="payment_method" value="dana" id="pmDana">
                                <label for="pmDana">
                                    <div class="pm-option__icon" style="font-weight:900;font-size:13px;">DA</div>
                                    <div class="pm-option__info">
                                        <div class="pm-option__name">DANA</div>
                                        <div class="pm-option__desc">Bayar menggunakan DANA</div>
                                    </div>
                                </label>
                            </div>
                            <div class="pm-option">
                                <input type="radio" name="payment_method" value="shopeepay" id="pmShopeepay">
                                <label for="pmShopeepay">
                                    <div class="pm-option__icon" style="font-weight:900;font-size:11px;">SP</div>
                                    <div class="pm-option__info">
                                        <div class="pm-option__name">ShopeePay</div>
                                        <div class="pm-option__desc">Bayar menggunakan ShopeePay</div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        {{-- Pay Button --}}
                        <button type="button" id="payButton" class="btn-pay" onclick="processPayment()">
                            <span class="spinner"></span>
                            <span class="btn-text">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/><path d="M12 15a2 2 0 1 0 0-4 2 2 0 0 0 0 4z" fill="currentColor" stroke="none"/></svg>
                                Bayar Sekarang
                            </span>
                        </button>
                    </div>

                    {{-- Payment Instructions (shown after processing) --}}
                    <div class="payment-instructions" id="paymentInstructions">
                        {{-- Filled by JS --}}
                    </div>

                    {{-- Status Polling --}}
                    <div class="status-poll" id="statusPoll">
                        <div class="status-poll__text">
                            Menunggu pembayaran dikonfirmasi
                            <span class="status-poll__dots">
                                <span></span><span></span><span></span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer Actions --}}
            <div class="payment-footer">
                <a href="{{ route('upgrade.index') }}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                    Kembali ke Upgrade
                </a>
                <a href="{{ route('home') }}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                    Ke Beranda
                </a>
            </div>
        @endif

    </div>
</section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // If the payment is already paid/expired/failed, do nothing
        const status = '{{ $payment->transaction_status }}';
        if (status !== 'pending') return;

        // Check if this payment was already processed (has midtrans transaction id)
        @if ($payment->midtrans_transaction_id)
            // Payment was already initiated — show instructions and start polling
            const storedData = localStorage.getItem('payment_{{ $payment->invoice }}');
            if (storedData) {
                try {
                    const response = JSON.parse(storedData);
                    showPaymentInstructions(response);
                    startStatusPolling();
                } catch (e) {
                    // stored data is invalid, ignore
                }
            }
        @endif
    });

    /**
     * Process payment — send selected method to backend.
     */
    async function processPayment() {
        const selectedMethod = document.querySelector('input[name="payment_method"]:checked');
        const errorDiv = document.getElementById('paymentError');
        const errorText = document.getElementById('paymentErrorText');
        const payButton = document.getElementById('payButton');
        const instructions = document.getElementById('paymentInstructions');

        // Hide previous errors
        errorDiv.classList.remove('show');

        // Validate payment method selection
        if (!selectedMethod) {
            errorText.textContent = 'Silakan pilih metode pembayaran terlebih dahulu.';
            errorDiv.classList.add('show');
            return;
        }

        // Show loading state
        payButton.classList.add('loading');
        payButton.disabled = true;

        try {
            const response = await fetch('{{ route('payment.process', ['invoice' => $payment->invoice]) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({
                    payment_method: selectedMethod.value,
                }),
            });

            const data = await response.json();

            if (response.ok && data.success) {
                // Store response data for page refresh persistence
                localStorage.setItem('payment_{{ $payment->invoice }}', JSON.stringify(data.data));

                // Hide method selection, show instructions
                document.getElementById('paymentMethodSection').style.display = 'none';
                showPaymentInstructions(data.data);
                startStatusPolling();
            } else {
                errorText.textContent = data.message || 'Gagal memproses pembayaran. Silakan coba lagi.';
                errorDiv.classList.add('show');
                payButton.classList.remove('loading');
                payButton.disabled = false;
            }
        } catch (error) {
            console.error('Payment error:', error);
            errorText.textContent = 'Terjadi kesalahan jaringan. Silakan coba lagi.';
            errorDiv.classList.add('show');
            payButton.classList.remove('loading');
            payButton.disabled = false;
        }
    }

    /**
     * Show payment instructions based on Midtrans response.
     */
    function showPaymentInstructions(data) {
        const container = document.getElementById('paymentInstructions');
        container.innerHTML = '';

        // Determine payment type from the response
        const paymentType = data.payment_type || '';
        let html = '';

        if (paymentType === 'bank_transfer') {
            // Virtual Account
            const vaNumber = data.va_numbers && data.va_numbers[0] ? data.va_numbers[0].va_number : (data.permata_va_number || '-');
            const bank = data.va_numbers && data.va_numbers[0] ? data.va_numbers[0].bank.toUpperCase() : 'BANK';

            html = `
                <div class="instructions-card">
                    <div class="instructions-card__icon instructions-card__icon--pending">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                    </div>
                    <div class="instructions-card__title">Transfer ${bank}</div>
                    <div class="instructions-card__subtitle">Lakukan pembayaran melalui transfer ke Virtual Account di bawah ini</div>

                    <div class="instructions-detail">
                        <div class="instructions-detail__label">Nomor Virtual Account</div>
                        <div class="instructions-detail__value">${vaNumber}</div>
                        <button class="instructions-detail__copy" onclick="copyToClipboard('${vaNumber}', this)">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                            Salin Nomor VA
                        </button>
                    </div>

                    <div class="instructions-detail">
                        <div class="instructions-detail__label">Total Pembayaran</div>
                        <div class="instructions-detail__value">Rp ${formatRupiah(data.gross_amount || '{{ $payment->final_price }}')}</div>
                    </div>

                    <ul class="instructions-steps">
                        <li>
                            <span class="instructions-steps__num">1</span>
                            Buka aplikasi ${bank} Mobile / Internet Banking / ATM
                        </li>
                        <li>
                            <span class="instructions-steps__num">2</span>
                            Pilih menu "Transfer" &gt; "Virtual Account" / "Ke Rekening"
                        </li>
                        <li>
                            <span class="instructions-steps__num">3</span>
                            Masukkan nomor Virtual Account: <strong>${vaNumber}</strong>
                        </li>
                        <li>
                            <span class="instructions-steps__num">4</span>
                            Konfirmasi dan selesaikan pembayaran
                        </li>
                    </ul>
                </div>
            `;
        } else if (paymentType === 'qris') {
            // QRIS
            const qrUrl = data.actions && data.actions.find(a => a.name === 'generate-qr-code')?.url || '';

            html = `
                <div class="instructions-card">
                    <div class="instructions-card__icon instructions-card__icon--pending">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                    </div>
                    <div class="instructions-card__title">QRIS</div>
                    <div class="instructions-card__subtitle">Scan QR Code di bawah menggunakan aplikasi pembayaran</div>

                    <div class="qr-container">
                        ${qrUrl ? `<img src="${qrUrl}" alt="QR Code Pembayaran" id="qrisImage" crossorigin="anonymous">` : '<p style="color:#ef4444;font-weight:600;">QR Code tidak tersedia</p>'}
                        <div class="qr-container__label">Scan QR ini dengan Gojek, OVO, DANA, ShopeePay, dll.</div>
                    </div>

                    <div class="instructions-detail">
                        <div class="instructions-detail__label">Total Pembayaran</div>
                        <div class="instructions-detail__value">Rp ${formatRupiah(data.gross_amount || '{{ $payment->final_price }}')}</div>
                    </div>

                    <ul class="instructions-steps">
                        <li>
                            <span class="instructions-steps__num">1</span>
                            Buka aplikasi pembayaran (Gojek/OVO/DANA/ShopeePay/dll.)
                        </li>
                        <li>
                            <span class="instructions-steps__num">2</span>
                            Pilih menu "Scan QR" atau "Bayar QRIS"
                        </li>
                        <li>
                            <span class="instructions-steps__num">3</span>
                            Scan QR Code di atas
                        </li>
                        <li>
                            <span class="instructions-steps__num">4</span>
                            Konfirmasi dan selesaikan pembayaran
                        </li>
                    </ul>
                </div>
            `;
        } else if (paymentType === 'gopay') {
            // GoPay / E-Wallet
            const qrUrl = data.actions && data.actions.find(a => a.name === 'generate-qr-code')?.url || '';

            html = `
                <div class="instructions-card">
                    <div class="instructions-card__icon instructions-card__icon--pending">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/><path d="M12 6v6l4 2"/></svg>
                    </div>
                    <div class="instructions-card__title">E-Wallet</div>
                    <div class="instructions-card__subtitle">Scan QR Code untuk membayar menggunakan aplikasi E-Wallet</div>

                    <div class="qr-container">
                        ${qrUrl ? `<img src="${qrUrl}" alt="QR Code Pembayaran" crossorigin="anonymous">` : '<p style="color:#ef4444;font-weight:600;">QR Code tidak tersedia</p>'}
                        <div class="qr-container__label">Scan QR ini di aplikasi GoPay/ShopeePay/DANA</div>
                    </div>

                    <div class="instructions-detail">
                        <div class="instructions-detail__label">Total Pembayaran</div>
                        <div class="instructions-detail__value">Rp ${formatRupiah(data.gross_amount || '{{ $payment->final_price }}')}</div>
                    </div>

                    <ul class="instructions-steps">
                        <li>
                            <span class="instructions-steps__num">1</span>
                            Buka aplikasi E-Wallet kamu
                        </li>
                        <li>
                            <span class="instructions-steps__num">2</span>
                            Pilih menu "Bayar" atau "Scan QR"
                        </li>
                        <li>
                            <span class="instructions-steps__num">3</span>
                            Scan QR Code di atas
                        </li>
                        <li>
                            <span class="instructions-steps__num">4</span>
                            Konfirmasi dan selesaikan pembayaran
                        </li>
                    </ul>
                </div>
            `;
        } else {
            // Fallback
            html = `
                <div class="instructions-card">
                    <div class="instructions-card__title">Menunggu Pembayaran</div>
                    <div class="instructions-card__subtitle">Silakan selesaikan pembayaran Anda</div>
                    <ul class="instructions-steps">
                        <li>
                            <span class="instructions-steps__num">1</span>
                            Buka aplikasi pembayaran Anda
                        </li>
                        <li>
                            <span class="instructions-steps__num">2</span>
                            Lakukan pembayaran sebesar <strong>Rp ${formatRupiah(data.gross_amount || '{{ $payment->final_price }}')}</strong>
                        </li>
                        <li>
                            <span class="instructions-steps__num">3</span>
                            Setelah pembayaran berhasil, status akan ter-update otomatis
                        </li>
                    </ul>
                </div>
            `;
        }

        container.innerHTML = html;
        container.classList.add('show');
    }

    /**
     * Start polling payment status every 5 seconds.
     */
    let pollingInterval = null;

    function startStatusPolling() {
        const pollEl = document.getElementById('statusPoll');
        pollEl.classList.add('show');

        // Poll every 5 seconds
        pollingInterval = setInterval(async () => {
            try {
                const response = await fetch('{{ route('payment.status', ['invoice' => $payment->invoice]) }}');
                const data = await response.json();

                if (data.success && data.payment) {
                    if (data.payment.transaction_status === 'paid') {
                        // Payment confirmed!
                        clearInterval(pollingInterval);
                        pollingInterval = null;
                        showPaymentSuccess();
                    } else if (data.payment.transaction_status === 'expired') {
                        clearInterval(pollingInterval);
                        pollingInterval = null;
                        showPaymentExpired();
                    }
                }
            } catch (e) {
                console.error('Status poll error:', e);
            }
        }, 5000);

        // Auto-stop polling after 15 minutes
        setTimeout(() => {
            if (pollingInterval) {
                clearInterval(pollingInterval);
                pollingInterval = null;
                document.getElementById('statusPoll').classList.remove('show');
            }
        }, 15 * 60 * 1000);
    }

    /**
     * Show success state after payment is confirmed.
     */
    function showPaymentSuccess() {
        const container = document.getElementById('paymentInstructions');
        const pollEl = document.getElementById('statusPoll');
        pollEl.classList.remove('show');

        // Clear localStorage
        localStorage.removeItem('payment_{{ $payment->invoice }}');

        container.innerHTML = `
            <div class="instructions-card" style="border-color:rgba(16,185,129,.3);">
                <div class="instructions-card__icon instructions-card__icon--success">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                </div>
                <div class="instructions-card__title">Pembayaran Berhasil! 🎉</div>
                <div class="instructions-card__subtitle">Selamat! Kamu sekarang sudah menjadi pengguna Premium.</div>
                <div style="text-align:center;margin-top:12px;font-size:14px;color:#64748b;">
                    Mengarahkan ke halaman utama...
                </div>
            </div>
        `;

        // Auto-redirect to home after 2 seconds
        setTimeout(() => {
            window.location.href = '{{ route('home') }}';
        }, 2000);
    }

    /**
     * Show expired state.
     */
    function showPaymentExpired() {
        const container = document.getElementById('paymentInstructions');
        const pollEl = document.getElementById('statusPoll');
        pollEl.classList.remove('show');

        localStorage.removeItem('payment_{{ $payment->invoice }}');

        container.innerHTML = `
            <div class="instructions-card instructions-card--pending">
                <div class="instructions-card__icon instructions-card__icon--pending">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                </div>
                <div class="instructions-card__title">Waktu Habis</div>
                <div class="instructions-card__subtitle">Pembayaran tidak diselesaikan tepat waktu. Silakan coba lagi.</div>
                <div style="text-align:center;">
                    <a href="{{ route('upgrade.index') }}" class="btn-dashboard">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg>
                        Upgrade Lagi
                    </a>
                </div>
            </div>
        `;
    }

    /**
     * Copy text to clipboard with visual feedback.
     */
    function copyToClipboard(text, btn) {
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(() => {
                showCopied(btn);
            }).catch(() => {
                fallbackCopy(text, btn);
            });
        } else {
            fallbackCopy(text, btn);
        }
    }

    function fallbackCopy(text, btn) {
        const textarea = document.createElement('textarea');
        textarea.value = text;
        textarea.style.position = 'fixed';
        textarea.style.opacity = '0';
        document.body.appendChild(textarea);
        textarea.select();
        try {
            document.execCommand('copy');
            showCopied(btn);
        } catch (e) {
            // ignore
        }
        document.body.removeChild(textarea);
    }

    function showCopied(btn) {
        const original = btn.innerHTML;
        btn.innerHTML = `
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            Tersalin!
        `;
        btn.classList.add('copied');
        setTimeout(() => {
            btn.innerHTML = original;
            btn.classList.remove('copied');
        }, 2000);
    }

    /**
     * Format number to Rupiah.
     */
    function formatRupiah(value) {
        const num = parseInt(value) || 0;
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }
</script>
@endpush
