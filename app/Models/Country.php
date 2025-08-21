<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'status'
    ];

    // Relationship with services
    public function services()
    {
        return $this->hasMany(Service::class);
    }

    // Scope for active countries
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}