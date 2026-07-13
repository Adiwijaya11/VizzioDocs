<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'tab',
        'action',
        'details',
        'ip_address',
        'status',
        'latency',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
