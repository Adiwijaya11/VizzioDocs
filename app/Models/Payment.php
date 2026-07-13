<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'invoice',
        'plan',
        'original_price',
        'discount',
        'final_price',
        'payment_method',
        'midtrans_transaction_id',
        'transaction_status',
        'coupon_id',
        'expired_at',
        'paid_at',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
        'paid_at' => 'datetime',
        'original_price' => 'float',
        'discount' => 'float',
        'final_price' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    /**
     * Get human-readable plan name from internal plan key.
     */
    public function getPlanNameAttribute(): string
    {
        $names = [
            'harian' => 'Harian',
            'mingguan' => 'Mingguan',
            'bulanan' => 'Bulanan',
            'daily' => 'Harian',
            'weekly' => 'Mingguan',
            'monthly' => 'Bulanan',
        ];

        return $names[$this->plan] ?? ucfirst($this->plan);
    }
}
