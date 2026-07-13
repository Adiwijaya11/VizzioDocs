<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;

class PaymentService
{
    private $couponService;

    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }

    /**
     * Create a new pending payment record.
     */
    public function createPendingPayment(User $user, string $plan, ?string $couponCode = null): Payment
    {
        $originalPrice = $this->getPlanPrice($plan);
        $discount = 0;
        $coupon = null;

        if ($couponCode) {
            $coupon = $this->couponService->findValidCoupon($couponCode, $originalPrice);
            if ($coupon) {
                $discount = $this->couponService->calculateDiscount($originalPrice, $coupon);
            }
        }

        $finalPrice = $originalPrice - $discount;
        if ($finalPrice < 0) {
            $finalPrice = 0;
        }

        $payment = Payment::create([
            'user_id' => $user->id,
            'invoice' => 'INV-' . Str::random(10) . '-' . time(),
            'plan' => $plan,
            'original_price' => $originalPrice,
            'discount' => $discount,
            'final_price' => $finalPrice,
            'transaction_status' => 'pending',
            'coupon_id' => $coupon ? $coupon->id : null,
            'expired_at' => Carbon::now()->addMinutes(15), // Midtrans default expiry is 15 minutes
        ]);

        if ($coupon) {
            $this->couponService->markCouponUsed($coupon);
        }

        return $payment;
    }

    /**
     * Update payment status and activate user premium.
     */
    public function processPaymentSuccess(Payment $payment, string $midtransTransactionId, string $paymentMethod): void
    {
        $payment->update([
            'midtrans_transaction_id' => $midtransTransactionId,
            'payment_method' => $paymentMethod,
            'transaction_status' => 'paid',
            'paid_at' => Carbon::now(),
        ]);

        $this->activatePremiumUser($payment->user, $payment->plan);
    }

    /**
     * Activate premium status for a user.
     */
    public function activatePremiumUser(User $user, string $plan): void
    {
        $startDate = Carbon::now();
        $endDate = match ($plan) {
            'harian' => $startDate->copy()->addDay(),
            'mingguan' => $startDate->copy()->addWeek(),
            'bulanan' => $startDate->copy()->addMonth(),
            default => $startDate->copy()->addDay(),
        };

        // Update or create subscription
        $user->subscriptions()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'plan' => $plan,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => 'active',
            ]
        );

        // Update user's premium status and expiry
        $user->update([
            'plan' => 'premium',
            'premium_expires_at' => $endDate,
        ]);
    }

    /**
     * Update payment status to expired.
     */
    public function markPaymentExpired(Payment $payment): void
    {
        $payment->update(['transaction_status' => 'expired']);
    }

    /**
     * Update payment status to failed.
     */
    public function markPaymentFailed(Payment $payment): void
    {
        $payment->update(['transaction_status' => 'failed']);
    }

    /**
     * Get price for a given plan.
     */
    public function getPlanPrice(string $plan): float
    {
        return match ($plan) {
            'harian' => 5000,
            'mingguan' => 20000,
            'bulanan' => 35000,
            default => 0,
        };
    }

    /**
     * Get human-readable plan name.
     */
    public function getPlanName(string $plan): string
    {
        return match ($plan) {
            'harian' => 'Harian',
            'mingguan' => 'Mingguan',
            'bulanan' => 'Bulanan',
            default => 'Unknown',
        };
    }
}
