<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'status'
    ];

    protected $casts = [
        'status' => 'integer'
    ];

    // Status constants for better code readability
    const STATUS_DISABLED = 0;
    const STATUS_ENABLED = 1;

    /**
     * Scope to get only enabled services (status = 1)
     */
    public function scopeEnabled($query)
    {
        return $query->where('status', self::STATUS_ENABLED);
    }

    /**
     * Scope to get only disabled services (status = 0)
     */
    public function scopeDisabled($query)
    {
        return $query->where('status', self::STATUS_DISABLED);
    }

    /**
     * Scope to get services by specific status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Check if service is enabled
     */
    public function isEnabled()
    {
        return $this->status == self::STATUS_ENABLED;
    }

    /**
     * Check if service is disabled
     */
    public function isDisabled()
    {
        return $this->status == self::STATUS_DISABLED;
    }

    /**
     * Search services by name or description
     */
    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'LIKE', '%' . $term . '%')
              ->orWhere('description', 'LIKE', '%' . $term . '%');
        });
    }
}