<?php

use App\Http\Controllers\ServiceDemoController;

/*
|--------------------------------------------------------------------------
| DEMO Routes - No Database Required!
|--------------------------------------------------------------------------
| These routes work without migrations or database setup.
| Perfect for testing functionality before your senior confirms the schema.
*/

// Demo info
Route::get('/api/demo/info', [ServiceDemoController::class, 'getDemoInfo']);

// Main service endpoints (same as your real ones will be)
Route::get('/api/demo/services', [ServiceDemoController::class, 'getServices']);
Route::get('/api/demo/services/{id}', [ServiceDemoController::class, 'getServiceById']);
Route::get('/api/demo/services/category', [ServiceDemoController::class, 'getServicesByCategory']);

// Additional demo endpoints
Route::get('/api/demo/categories', [ServiceDemoController::class, 'getCategories']);
Route::get('/api/demo/statistics', [ServiceDemoController::class, 'getStatistics']);
Route::get('/api/demo/advanced-search', [ServiceDemoController::class, 'advancedSearch']);

/*
USAGE EXAMPLES:

1. Get demo info:
   GET /api/demo/info

2. Get all enabled services:
   GET /api/demo/services

3. Search services:
   GET /api/demo/services?search=web

4. Get service by ID:
   GET /api/demo/services/1

5. Filter by category:
   GET /api/demo/services/category?category=Development

6. Get all categories:
   GET /api/demo/categories

7. Get statistics:
   GET /api/demo/statistics

8. Advanced search:
   GET /api/demo/advanced-search?name=web&category=Development&min_price=100&max_price=500

WHEN READY TO SWITCH TO REAL MODEL:
1. Replace ServiceDemo with Service in controller
2. Change routes from /api/demo/* to /api/*
3. Remove demo_mode flags from responses
4. Done! 🚀
*/