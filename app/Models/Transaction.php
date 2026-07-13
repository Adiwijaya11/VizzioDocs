<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'description',
        'amount',
        'coupon_code',
        'duration_label',
        'duration_days',
        'premium_expires_at',
        'status',
    ];

    protected $casts = [
        'premium_expires_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    /**
     * Get the user that owns the transaction.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
