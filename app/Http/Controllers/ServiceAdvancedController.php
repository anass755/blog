<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Service;

class ServiceAdvancedController extends Controller
{
    /**
     * BASIC SEARCH - Single search term
     */
    public function basicSearch(Request $request): JsonResponse
    {
        $searchTerm = $request->input('search', '');
        
        $services = Service::enabled()
            ->search($searchTerm)
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'services' => $services,
            'query_type' => 'basic_search'
        ]);
    }

    /**
     * ADVANCED SEARCH - Multiple specific filters
     */
    public function advancedSearch(Request $request): JsonResponse
    {
        $filters = $request->only([
            'name', 'description', 'category', 
            'min_price', 'max_price', 'duration'
        ]);

        $services = Service::enabled()
            ->advancedSearch($filters)
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'services' => $services,
            'filters_applied' => $filters,
            'query_type' => 'advanced_search'
        ]);
    }

    /**
     * MULTI-TERM SEARCH - Multiple words (all must match)
     */
    public function multiTermSearch(Request $request): JsonResponse
    {
        $searchTerms = $request->input('search', '');
        
        $services = Service::enabled()
            ->multiTermSearch($searchTerms)
            ->orderBy('name')
            ->get();

        $terms = explode(' ', trim($searchTerms));

        return response()->json([
            'success' => true,
            'services' => $services,
            'search_terms' => $terms,
            'query_type' => 'multi_term_search'
        ]);
    }

    /**
     * FLEXIBLE SEARCH - Search in specific columns only
     */
    public function flexibleSearch(Request $request): JsonResponse
    {
        $searchTerm = $request->input('search', '');
        $columns = $request->input('columns', ['name', 'description']); // Default columns

        $services = Service::enabled()
            ->searchInColumns($searchTerm, $columns)
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'services' => $services,
            'searched_columns' => $columns,
            'query_type' => 'flexible_search'
        ]);
    }

    /**
     * WEIGHTED SEARCH - Results ranked by relevance
     */
    public function weightedSearch(Request $request): JsonResponse
    {
        $searchTerm = $request->input('search', '');
        
        $services = Service::enabled()
            ->weightedSearch($searchTerm)
            ->get();

        return response()->json([
            'success' => true,
            'services' => $services,
            'query_type' => 'weighted_search',
            'note' => 'Results ordered by relevance score'
        ]);
    }

    /**
     * COMBINED SEARCH - Mix of different search types
     */
    public function combinedSearch(Request $request): JsonResponse
    {
        $query = Service::enabled();

        // Basic search term
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Price range
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Execute query
        $services = $query->orderBy('name')->get();

        return response()->json([
            'success' => true,
            'services' => $services,
            'applied_filters' => $request->only(['search', 'category', 'min_price', 'max_price']),
            'query_type' => 'combined_search'
        ]);
    }

    /**
     * STEP-BY-STEP DEBUG - Shows each query step
     */
    public function debugSearch(Request $request): JsonResponse
    {
        $searchTerm = $request->input('search', 'web');
        
        // Step 1: Start with base query
        $step1 = Service::query();
        $sql1 = $step1->toSql();
        
        // Step 2: Add enabled scope
        $step2 = Service::enabled();
        $sql2 = $step2->toSql();
        
        // Step 3: Add search scope
        $step3 = Service::enabled()->search($searchTerm);
        $sql3 = $step3->toSql();
        
        // Step 4: Add ordering
        $step4 = Service::enabled()->search($searchTerm)->orderBy('name');
        $sql4 = $step4->toSql();
        
        // Final execution
        $services = $step4->get();

        return response()->json([
            'success' => true,
            'services' => $services,
            'debug_steps' => [
                'step_1_base_query' => $sql1,
                'step_2_with_enabled' => $sql2,
                'step_3_with_search' => $sql3,
                'step_4_with_ordering' => $sql4,
            ],
            'search_term' => $searchTerm
        ]);
    }
}