<?php

namespace App\Services;

use App\Models\Coupon;
use Carbon\Carbon;

class CouponService
{
    public function findValidCoupon(string $code, float $minPurchase = 0): ?Coupon
    {
        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon || !$coupon->is_active) {
            return null;
        }

        if ($coupon->expires_at && Carbon::now()->greaterThan($coupon->expires_at)) {
            return null;
        }

        if ($coupon->usage_limit !== null && $coupon->used_count >= $coupon->usage_limit) {
            return null;
        }

        if ($minPurchase > 0 && $coupon->min_purchase > $minPurchase) {
            return null;
        }

        return $coupon;
    }

    public function calculateDiscount(float $originalPrice, Coupon $coupon): float
    {
        $discount = 0;

        if ($coupon->type === 'percentage') {
            $discount = $originalPrice * ($coupon->value / 100);
        } elseif ($coupon->type === 'fixed') {
            $discount = $coupon->value;
        }

        return $discount;
    }

    public function markCouponUsed(Coupon $coupon): void
    {
        $coupon->increment('used_count');
    }
}
