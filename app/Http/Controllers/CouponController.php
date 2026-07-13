<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Validate a coupon code and return discount info.
     * Does NOT activate premium — just validates and calculates discount.
     */
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string|max:255',
            'plan' => 'required|string|in:daily,weekly,monthly,harian,mingguan,bulanan',
        ]);

        $code = strtoupper(trim($request->coupon_code));

        // Map plan key to internal plan name
        $planMap = [
            'daily' => 'harian',
            'weekly' => 'mingguan',
            'monthly' => 'bulanan',
            'harian' => 'harian',
            'mingguan' => 'mingguan',
            'bulanan' => 'bulanan',
        ];
        $plan = $planMap[$request->plan] ?? 'harian';

        $originalPrice = $this->paymentService->getPlanPrice($plan);

        $coupon = Coupon::where('code', $code)->first();

        // Check if coupon exists
        if (!$coupon) {
            return response()->json([
                'valid' => false,
                'message' => 'Kode kupon tidak valid atau tidak ditemukan.',
            ], 404);
        }

        // Check if coupon is active
        if (!$coupon->is_active) {
            return response()->json([
                'valid' => false,
                'message' => 'Kupon ini sudah tidak aktif.',
            ], 400);
        }

        // Check expiry
        if ($coupon->expires_at && now()->greaterThan($coupon->expires_at)) {
            return response()->json([
                'valid' => false,
                'message' => 'Kupon sudah kadaluwarsa.',
            ], 400);
        }

        // Check usage limit
        if ($coupon->usage_limit !== null && $coupon->used_count >= $coupon->usage_limit) {
            return response()->json([
                'valid' => false,
                'message' => 'Kupon sudah mencapai batas penggunaan.',
            ], 400);
        }

        // Check min purchase
        if ($coupon->min_purchase > 0 && $originalPrice < $coupon->min_purchase) {
            return response()->json([
                'valid' => false,
                'message' => 'Minimal pembelian Rp ' . number_format($coupon->min_purchase, 0, ',', '.') . ' untuk menggunakan kupon ini.',
            ], 400);
        }

        // Calculate discount
        $discount = 0;
        if ($coupon->type === 'percentage') {
            $discount = $originalPrice * ($coupon->value / 100);
        } elseif ($coupon->type === 'fixed') {
            $discount = $coupon->value;
        }

        $finalPrice = $originalPrice - $discount;
        if ($finalPrice < 0) {
            $finalPrice = 0;
        }

        $discountLabel = $coupon->type === 'percentage'
            ? $coupon->value . '%'
            : 'Rp ' . number_format($coupon->value, 0, ',', '.');

        return response()->json([
            'valid' => true,
            'message' => 'Kupon berhasil diterapkan! Diskon ' . $discountLabel . '.',
            'data' => [
                'coupon_code' => $coupon->code,
                'coupon_name' => $coupon->name ?? $coupon->code,
                'discount_type' => $coupon->type,
                'discount_value' => (float) $coupon->value,
                'discount' => (float) $discount,
                'original_price' => (float) $originalPrice,
                'final_price' => (float) $finalPrice,
                'discount_label' => $discountLabel,
            ],
        ]);
    }
}
