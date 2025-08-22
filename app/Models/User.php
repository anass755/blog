<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'age',
        'city',
        'created_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'age' => 'integer',
    ];

    /**
     * Scope for sorting
     */
    public function scopeSortBy($query, $column, $direction = 'asc')
    {
        if (in_array($column, $this->fillable)) {
            return $query->orderBy($column, $direction);
        }
        return $query;
    }

    /**
     * Scope for filtering
     */
    public function scopeFilter($query, $filters)
    {
        if (isset($filters['search']) && !empty($filters['search'])) {
            $query->where(function($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('email', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('city', 'like', '%' . $filters['search'] . '%');
            });
        }

        if (isset($filters['age_min']) && !empty($filters['age_min'])) {
            $query->where('age', '>=', $filters['age_min']);
        }

        if (isset($filters['age_max']) && !empty($filters['age_max'])) {
            $query->where('age', '<=', $filters['age_max']);
        }

        if (isset($filters['city']) && !empty($filters['city'])) {
            $query->where('city', $filters['city']);
        }

        return $query;
    }
}