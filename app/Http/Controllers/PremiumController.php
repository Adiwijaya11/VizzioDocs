<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Services\PaymentService;
use App\Services\CouponService;

class PremiumController extends Controller
{
    protected $paymentService;
    protected $couponService;

    public function __construct(PaymentService $paymentService, CouponService $couponService)
    {
        $this->paymentService = $paymentService;
        $this->couponService = $couponService;
    }

    /**
     * Show the premium upgrade page.
     */
    public function index()
    {
        return view('upgrade');
    }

    /**
     * Process a premium purchase — create pending payment and redirect to payment page.
     */
    public function purchase(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->isPremium()) {
            return redirect()->route('upgrade.index')->with('info', 'Kamu sudah menjadi pengguna Premium!');
        }

        // Validate plan — use harian/mingguan/bulanan internally
        $planMap = [
            'daily' => 'harian',
            'weekly' => 'mingguan',
            'monthly' => 'bulanan',
            'harian' => 'harian',
            'mingguan' => 'mingguan',
            'bulanan' => 'bulanan',
        ];

        $period = $request->input('period', 'daily');
        $plan = $planMap[$period] ?? null;

        if (!$plan) {
            return redirect()->route('upgrade.index')->with('error', 'Periode tidak valid.');
        }

        // Validate coupon if provided
        $couponCode = $request->input('coupon_code');
        $couponCode = !empty($couponCode) ? strtoupper(trim($couponCode)) : null;
        $coupon = null;
        $discount = 0;

        if ($couponCode) {
            $originalPrice = $this->paymentService->getPlanPrice($plan);
            $coupon = $this->couponService->findValidCoupon($couponCode, $originalPrice);
            if ($coupon) {
                $discount = $this->couponService->calculateDiscount($originalPrice, $coupon);
            } else {
                return redirect()->route('upgrade.index')->with('error', 'Kode kupon tidak valid atau sudah tidak berlaku.');
            }
        }

        // Create pending payment
        $payment = $this->paymentService->createPendingPayment($user, $plan, $couponCode);

        // Mark coupon as used (it's already marked in createPendingPayment)
        // Redirect to the payment page
        return redirect()->route('payment.show', ['invoice' => $payment->invoice]);
    }
}
