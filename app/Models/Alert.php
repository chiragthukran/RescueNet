<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    use HasFactory;

    protected $fillable = [
        'calamity_id',
        'created_by',
        'target_user_id',
        'title',
        'message',
        'priority',
        'is_broadcast',
        'acknowledged_at',
    ];

    protected function casts(): array
    {
        return [
            'is_broadcast' => 'boolean',
            'acknowledged_at' => 'datetime',
        ];
    }

    public function calamity()
    {
        return $this->belongsTo(Calamity::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function targetUser()
    {
        return $this->belongsTo(User::class, 'target_user_id');
    }
}
