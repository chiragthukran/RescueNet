<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calamity extends Model
{
    use HasFactory;

    protected $fillable = [
        'reported_by',
        'title',
        'type',
        'custom_type',
        'description',
        'severity',
        'latitude',
        'longitude',
        'radius_km',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'radius_km' => 'decimal:2',
        ];
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function alerts()
    {
        return $this->hasMany(Alert::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function getDisplayTypeAttribute(): string
    {
        return $this->type === 'other' && $this->custom_type
            ? $this->custom_type
            : ucfirst($this->type);
    }
}
