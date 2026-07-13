<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ToolLock extends Model
{
    protected $fillable = [
        'tool_slug',
        'tool_name',
        'tool_route',
        'is_locked',
    ];

    protected $casts = [
        'is_locked' => 'boolean',
    ];
}
