<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\ServiceDemo; // Use demo model instead of real one

class ServiceDemoController extends Controller
{
    /**
     * Get all services for the modal (DEMO VERSION)
     * This works without database/migration
     */
    public function getServices(Request $request): JsonResponse
    {
        // Get search term
        $searchTerm = $request->input('search', '');
        
        // Use demo data instead of database queries
        if (!empty($searchTerm)) {
            $services = ServiceDemo::searchDemo($searchTerm);
        } else {
            $services = ServiceDemo::getEnabled();
        }

        // Convert to array for JSON response
        $servicesArray = $services->map(function ($service) {
            return $service->toArray();
        })->values()->all();

        return response()->json([
            'success' => true,
            'services' => $servicesArray,
            'demo_mode' => true,
            'search_term' => $searchTerm,
            'total_count' => count($servicesArray)
        ]);
    }

    /**
     * Get services by category (DEMO VERSION)
     */
    public function getServicesByCategory(Request $request): JsonResponse
    {
        $category = $request->input('category', '');
        
        if (empty($category)) {
            return response()->json([
                'success' => false,
                'message' => 'Category is required'
            ]);
        }

        $services = ServiceDemo::getByCategory($category);
        
        $servicesArray = $services->map(function ($service) {
            return $service->toArray();
        })->values()->all();

        return response()->json([
            'success' => true,
            'services' => $servicesArray,
            'category' => $category,
            'demo_mode' => true,
            'total_count' => count($servicesArray)
        ]);
    }

    /**
     * Get service by ID (DEMO VERSION)
     */
    public function getServiceById(Request $request, $id): JsonResponse
    {
        $allServices = collect(ServiceDemo::getDemoData());
        $service = $allServices->firstWhere('id', (int)$id);

        if (!$service) {
            return response()->json([
                'success' => false,
                'message' => 'Service not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'service' => $service,
            'demo_mode' => true
        ]);
    }

    /**
     * Get all categories (DEMO VERSION)
     */
    public function getCategories(): JsonResponse
    {
        $categories = collect(ServiceDemo::getDemoData())
            ->pluck('category')
            ->unique()
            ->values()
            ->all();

        return response()->json([
            'success' => true,
            'categories' => $categories,
            'demo_mode' => true
        ]);
    }

    /**
     * Get service statistics (DEMO VERSION)
     */
    public function getStatistics(): JsonResponse
    {
        $allServices = collect(ServiceDemo::getDemoData());
        
        $stats = [
            'total_services' => $allServices->count(),
            'enabled_services' => $allServices->where('status', 1)->count(),
            'disabled_services' => $allServices->where('status', 0)->count(),
            'categories' => $allServices->pluck('category')->unique()->count(),
            'average_price' => $allServices->where('status', 1)->avg('price'),
            'price_range' => [
                'min' => $allServices->where('status', 1)->min('price'),
                'max' => $allServices->where('status', 1)->max('price')
            ]
        ];

        return response()->json([
            'success' => true,
            'statistics' => $stats,
            'demo_mode' => true
        ]);
    }

    /**
     * Search with advanced filters (DEMO VERSION)
     */
    public function advancedSearch(Request $request): JsonResponse
    {
        $filters = $request->only(['name', 'category', 'min_price', 'max_price']);
        $allServices = collect(ServiceDemo::getDemoData())->where('status', 1);

        // Apply filters
        if (!empty($filters['name'])) {
            $allServices = $allServices->filter(function ($service) use ($filters) {
                return str_contains(strtolower($service['name']), strtolower($filters['name']));
            });
        }

        if (!empty($filters['category'])) {
            $allServices = $allServices->where('category', $filters['category']);
        }

        if (!empty($filters['min_price'])) {
            $allServices = $allServices->where('price', '>=', (float)$filters['min_price']);
        }

        if (!empty($filters['max_price'])) {
            $allServices = $allServices->where('price', '<=', (float)$filters['max_price']);
        }

        return response()->json([
            'success' => true,
            'services' => $allServices->values()->all(),
            'filters_applied' => $filters,
            'demo_mode' => true,
            'total_count' => $allServices->count()
        ]);
    }

    /**
     * Demo info endpoint - Shows what this demo provides
     */
    public function getDemoInfo(): JsonResponse
    {
        return response()->json([
            'demo_mode' => true,
            'message' => 'This is a DEMO controller - no database required!',
            'features' => [
                'Search services by name/description/category',
                'Filter by category',
                'Get service by ID',
                'Get categories list',
                'Get service statistics',
                'Advanced search with price filters'
            ],
            'available_endpoints' => [
                'GET /api/demo/services - Get all enabled services',
                'GET /api/demo/services?search=web - Search services',
                'GET /api/demo/services/category?category=Development - Filter by category',
                'GET /api/demo/services/{id} - Get service by ID',
                'GET /api/demo/categories - Get all categories',
                'GET /api/demo/statistics - Get service statistics',
                'GET /api/demo/advanced-search - Advanced search with filters'
            ],
            'sample_data_count' => count(ServiceDemo::getDemoData()),
            'when_ready' => 'Simply replace ServiceDemo with your real Service model!'
        ]);
    }
}