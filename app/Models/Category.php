<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'status'
    ];

    // Relationship with services
    public function services()
    {
        return $this->hasMany(Service::class);
    }

    // Scope for active categories
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}