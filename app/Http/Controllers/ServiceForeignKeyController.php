<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\ServiceWithRelations as Service;

class ServiceForeignKeyController extends Controller
{
    /**
     * SEARCH METHOD 1: Using whereHas (Eloquent way)
     */
    public function searchWithWhereHas(Request $request): JsonResponse
    {
        $searchTerm = $request->input('search', '');
        
        $services = Service::enabled()
            ->searchWithWhereHas($searchTerm)
            ->with(['country', 'category', 'user']) // Load relationships
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'services' => $services,
            'method' => 'whereHas',
            'search_term' => $searchTerm
        ]);
    }

    /**
     * SEARCH METHOD 2: Using joins (SQL way)
     */
    public function searchWithJoins(Request $request): JsonResponse
    {
        $searchTerm = $request->input('search', '');
        
        $services = Service::enabled()
            ->searchWithRelations($searchTerm)
            ->orderBy('services.name')
            ->get();

        // Load relationships separately since we used joins
        $services->load(['country', 'category', 'user']);

        return response()->json([
            'success' => true,
            'services' => $services,
            'method' => 'joins',
            'search_term' => $searchTerm
        ]);
    }

    /**
     * SEARCH METHOD 3: Advanced search with specific foreign key filters
     */
    public function advancedSearchWithForeignKeys(Request $request): JsonResponse
    {
        $filters = $request->only([
            'service_name',
            'country_name', 
            'country_id',
            'category_name',
            'user_email'
        ]);

        $services = Service::enabled()
            ->advancedSearchWithForeignKeys($filters)
            ->with(['country', 'category', 'user'])
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'services' => $services,
            'filters_applied' => $filters,
            'method' => 'advanced_foreign_key_search'
        ]);
    }

    /**
     * SEARCH METHOD 4: Optimized search with subqueries
     */
    public function optimizedSearch(Request $request): JsonResponse
    {
        $searchTerm = $request->input('search', '');
        
        $services = Service::enabled()
            ->optimizedSearch($searchTerm)
            ->with(['country', 'category', 'user'])
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'services' => $services,
            'method' => 'optimized_subqueries',
            'search_term' => $searchTerm
        ]);
    }

    /**
     * COMPARISON: Show different query approaches
     */
    public function compareSearchMethods(Request $request): JsonResponse
    {
        $searchTerm = $request->input('search', 'web');
        
        // Method 1: whereHas
        $startTime = microtime(true);
        $whereHasResults = Service::enabled()
            ->searchWithWhereHas($searchTerm)
            ->count();
        $whereHasTime = microtime(true) - $startTime;

        // Method 2: Joins
        $startTime = microtime(true);
        $joinsResults = Service::enabled()
            ->searchWithRelations($searchTerm)
            ->count();
        $joinsTime = microtime(true) - $startTime;

        // Method 3: Subqueries
        $startTime = microtime(true);
        $subqueryResults = Service::enabled()
            ->optimizedSearch($searchTerm)
            ->count();
        $subqueryTime = microtime(true) - $startTime;

        return response()->json([
            'search_term' => $searchTerm,
            'results' => [
                'whereHas' => [
                    'count' => $whereHasResults,
                    'time' => round($whereHasTime * 1000, 2) . 'ms',
                    'description' => 'Uses Eloquent relationships'
                ],
                'joins' => [
                    'count' => $joinsResults,
                    'time' => round($joinsTime * 1000, 2) . 'ms',
                    'description' => 'Uses SQL joins'
                ],
                'subqueries' => [
                    'count' => $subqueryResults,
                    'time' => round($subqueryTime * 1000, 2) . 'ms',
                    'description' => 'Uses subqueries'
                ]
            ]
        ]);
    }

    /**
     * DEBUG: Show generated SQL queries
     */
    public function debugQueries(Request $request): JsonResponse
    {
        $searchTerm = $request->input('search', 'web');
        
        // Enable query logging
        \DB::enableQueryLog();

        // Method 1: whereHas
        $whereHasQuery = Service::enabled()->searchWithWhereHas($searchTerm);
        $whereHasSql = $whereHasQuery->toSql();
        $whereHasBindings = $whereHasQuery->getBindings();

        // Method 2: Joins
        $joinsQuery = Service::enabled()->searchWithRelations($searchTerm);
        $joinsSql = $joinsQuery->toSql();
        $joinsBindings = $joinsQuery->getBindings();

        // Method 3: Subqueries
        $subqueryQuery = Service::enabled()->optimizedSearch($searchTerm);
        $subquerySql = $subqueryQuery->toSql();
        $subqueryBindings = $subqueryQuery->getBindings();

        return response()->json([
            'search_term' => $searchTerm,
            'queries' => [
                'whereHas' => [
                    'sql' => $whereHasSql,
                    'bindings' => $whereHasBindings
                ],
                'joins' => [
                    'sql' => $joinsSql,
                    'bindings' => $joinsBindings
                ],
                'subqueries' => [
                    'sql' => $subquerySql,
                    'bindings' => $subqueryBindings
                ]
            ]
        ]);
    }
}