<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServiceWithRelations extends Model
{
    use HasFactory;

    protected $table = 'services';

    protected $fillable = [
        'name',
        'description',
        'country_id',
        'category_id',
        'user_id',
        'status'
    ];

    // Define relationships
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * BASIC SEARCH - Only current table columns
     */
    public function scopeBasicSearch($query, $term)
    {
        if (empty($term)) return $query;

        return $query->where(function ($q) use ($term) {
            $q->where('name', 'LIKE', '%' . $term . '%')
              ->orWhere('description', 'LIKE', '%' . $term . '%');
        });
    }

    /**
     * SEARCH WITH JOINS - Search related table data
     */
    public function scopeSearchWithRelations($query, $term)
    {
        if (empty($term)) return $query;

        return $query->leftJoin('countries', 'services.country_id', '=', 'countries.id')
                    ->leftJoin('categories', 'services.category_id', '=', 'categories.id')
                    ->leftJoin('users', 'services.user_id', '=', 'users.id')
                    ->where(function ($q) use ($term) {
                        $q->where('services.name', 'LIKE', '%' . $term . '%')
                          ->orWhere('services.description', 'LIKE', '%' . $term . '%')
                          ->orWhere('countries.name', 'LIKE', '%' . $term . '%')
                          ->orWhere('categories.name', 'LIKE', '%' . $term . '%')
                          ->orWhere('users.name', 'LIKE', '%' . $term . '%');
                    })
                    ->select('services.*'); // Only select service columns
    }

    /**
     * SEARCH WITH WHERE HAS - Using Eloquent relationships
     */
    public function scopeSearchWithWhereHas($query, $term)
    {
        if (empty($term)) return $query;

        return $query->where(function ($q) use ($term) {
            // Search in current table
            $q->where('name', 'LIKE', '%' . $term . '%')
              ->orWhere('description', 'LIKE', '%' . $term . '%')
              
              // Search in country table
              ->orWhereHas('country', function ($countryQuery) use ($term) {
                  $countryQuery->where('name', 'LIKE', '%' . $term . '%');
              })
              
              // Search in category table
              ->orWhereHas('category', function ($categoryQuery) use ($term) {
                  $categoryQuery->where('name', 'LIKE', '%' . $term . '%');
              })
              
              // Search in user table
              ->orWhereHas('user', function ($userQuery) use ($term) {
                  $userQuery->where('name', 'LIKE', '%' . $term . '%')
                           ->orWhere('email', 'LIKE', '%' . $term . '%');
              });
        });
    }

    /**
     * ADVANCED SEARCH - Search specific foreign key fields
     */
    public function scopeAdvancedSearchWithForeignKeys($query, $filters)
    {
        // Search by service name
        if (!empty($filters['service_name'])) {
            $query->where('name', 'LIKE', '%' . $filters['service_name'] . '%');
        }

        // Search by country name (foreign key)
        if (!empty($filters['country_name'])) {
            $query->whereHas('country', function ($q) use ($filters) {
                $q->where('name', 'LIKE', '%' . $filters['country_name'] . '%');
            });
        }

        // Search by country ID (exact match)
        if (!empty($filters['country_id'])) {
            $query->where('country_id', $filters['country_id']);
        }

        // Search by category name (foreign key)
        if (!empty($filters['category_name'])) {
            $query->whereHas('category', function ($q) use ($filters) {
                $q->where('name', 'LIKE', '%' . $filters['category_name'] . '%');
            });
        }

        // Search by user email (foreign key)
        if (!empty($filters['user_email'])) {
            $query->whereHas('user', function ($q) use ($filters) {
                $q->where('email', 'LIKE', '%' . $filters['user_email'] . '%');
            });
        }

        return $query;
    }

    /**
     * OPTIMIZED SEARCH - Using subqueries for better performance
     */
    public function scopeOptimizedSearch($query, $term)
    {
        if (empty($term)) return $query;

        return $query->where(function ($q) use ($term) {
            $q->where('name', 'LIKE', '%' . $term . '%')
              ->orWhere('description', 'LIKE', '%' . $term . '%')
              
              // Search country using subquery
              ->orWhereIn('country_id', function ($subQuery) use ($term) {
                  $subQuery->select('id')
                           ->from('countries')
                           ->where('name', 'LIKE', '%' . $term . '%');
              })
              
              // Search category using subquery
              ->orWhereIn('category_id', function ($subQuery) use ($term) {
                  $subQuery->select('id')
                           ->from('categories')
                           ->where('name', 'LIKE', '%' . $term . '%');
              });
        });
    }

    /**
     * FULL TEXT SEARCH - Including related tables
     */
    public function scopeFullTextSearchWithRelations($query, $term)
    {
        if (empty($term)) return $query;

        return $query->leftJoin('countries', 'services.country_id', '=', 'countries.id')
                    ->leftJoin('categories', 'services.category_id', '=', 'categories.id')
                    ->whereRaw("
                        MATCH(services.name, services.description) AGAINST(? IN BOOLEAN MODE)
                        OR MATCH(countries.name) AGAINST(? IN BOOLEAN MODE) 
                        OR MATCH(categories.name) AGAINST(? IN BOOLEAN MODE)
                    ", [$term, $term, $term])
                    ->select('services.*');
    }
}