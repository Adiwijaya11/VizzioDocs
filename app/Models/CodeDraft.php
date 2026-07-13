<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CodeDraft extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'content',
        'file_name',
        'language',
        'status',
        'last_saved_at',
    ];

    protected $casts = [
        'last_saved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
