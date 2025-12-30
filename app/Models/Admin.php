<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $table = 'admins';

    protected $fillable = [
        'name',
        'password',
        'region',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    /**
     * Relasi Admin dengan Lapangan
     */
    public function lapangans(): HasMany
    {
        return $this->hasMany(Lapangan::class);
    }

    /**
     * Relasi Admin dengan Event
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    /**
     * Relasi Admin dengan Slider
     */
    public function sliders(): HasMany
    {
        return $this->hasMany(Slider::class);
    }

    /**
     * Get nama region dengan format yang lebih bagus
     */
    public function getRegionLabelAttribute()
    {
        $labels = [
            'padang' => 'Padang',
            'bukittinggi' => 'Bukittinggi',
            'sijunjung' => 'Sijunjung',
        ];
        return $labels[$this->region] ?? $this->region;
    }
}
