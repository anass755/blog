<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServiceAdvanced extends Model
{
    use HasFactory;

    protected $table = 'services';

    protected $fillable = [
        'name',
        'description',
        'category',
        'price',
        'duration',
        'status'
    ];

    protected $casts = [
        'status' => 'integer',
        'price' => 'decimal:2'
    ];

    // Status constants
    const STATUS_DISABLED = 0;
    const STATUS_ENABLED = 1;

    /**
     * Basic enabled scope
     */
    public function scopeEnabled($query)
    {
        return $query->where('status', self::STATUS_ENABLED);
    }

    /**
     * BASIC SEARCH - Single term across multiple columns
     */
    public function scopeSearch($query, $term)
    {
        if (empty($term)) return $query;

        return $query->where(function ($q) use ($term) {
            $q->where('name', 'LIKE', '%' . $term . '%')
              ->orWhere('description', 'LIKE', '%' . $term . '%')
              ->orWhere('category', 'LIKE', '%' . $term . '%');
        });
    }

    /**
     * ADVANCED SEARCH - Multiple columns with specific values
     */
    public function scopeAdvancedSearch($query, $filters)
    {
        // Search by name
        if (!empty($filters['name'])) {
            $query->where('name', 'LIKE', '%' . $filters['name'] . '%');
        }

        // Search by description
        if (!empty($filters['description'])) {
            $query->where('description', 'LIKE', '%' . $filters['description'] . '%');
        }

        // Search by category (exact match)
        if (!empty($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        // Search by price range
        if (!empty($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }
        if (!empty($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }

        // Search by duration
        if (!empty($filters['duration'])) {
            $query->where('duration', $filters['duration']);
        }

        return $query;
    }

    /**
     * MULTI-TERM SEARCH - Search multiple words across columns
     */
    public function scopeMultiTermSearch($query, $searchTerms)
    {
        if (empty($searchTerms)) return $query;

        // Split search terms by space
        $terms = explode(' ', trim($searchTerms));

        return $query->where(function ($q) use ($terms) {
            foreach ($terms as $term) {
                $q->where(function ($subQuery) use ($term) {
                    $subQuery->where('name', 'LIKE', '%' . $term . '%')
                            ->orWhere('description', 'LIKE', '%' . $term . '%')
                            ->orWhere('category', 'LIKE', '%' . $term . '%');
                });
            }
        });
    }

    /**
     * FLEXIBLE SEARCH - Search specific columns
     */
    public function scopeSearchInColumns($query, $term, $columns = ['name', 'description'])
    {
        if (empty($term) || empty($columns)) return $query;

        return $query->where(function ($q) use ($term, $columns) {
            foreach ($columns as $column) {
                $q->orWhere($column, 'LIKE', '%' . $term . '%');
            }
        });
    }

    /**
     * WEIGHTED SEARCH - Prioritize certain columns
     */
    public function scopeWeightedSearch($query, $term)
    {
        if (empty($term)) return $query;

        return $query->selectRaw('*, 
            CASE 
                WHEN name LIKE ? THEN 3
                WHEN description LIKE ? THEN 2
                WHEN category LIKE ? THEN 1
                ELSE 0
            END as relevance_score', [
                '%' . $term . '%',
                '%' . $term . '%', 
                '%' . $term . '%'
            ])
            ->where(function ($q) use ($term) {
                $q->where('name', 'LIKE', '%' . $term . '%')
                  ->orWhere('description', 'LIKE', '%' . $term . '%')
                  ->orWhere('category', 'LIKE', '%' . $term . '%');
            })
            ->orderBy('relevance_score', 'DESC');
    }

    /**
     * FULL TEXT SEARCH (MySQL only)
     */
    public function scopeFullTextSearch($query, $term)
    {
        return $query->whereRaw(
            "MATCH(name, description) AGAINST(? IN BOOLEAN MODE)", 
            [$term]
        );
    }
}