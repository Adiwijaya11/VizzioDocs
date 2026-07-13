<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuestToolUsage extends Model
{
    protected $fillable = [
        'ip_address',
        'tool_name',
        'usage_date',
        'last_used_at',
        'usage_count',
    ];

    protected $casts = [
        'usage_date' => 'date',
        'last_used_at' => 'datetime',
    ];
}
