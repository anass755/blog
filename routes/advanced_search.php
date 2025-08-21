<?php

use App\Http\Controllers\ServiceAdvancedController;

// Basic search routes
Route::get('/api/services/search', [ServiceAdvancedController::class, 'basicSearch']);
Route::get('/api/services/advanced-search', [ServiceAdvancedController::class, 'advancedSearch']);
Route::get('/api/services/multi-search', [ServiceAdvancedController::class, 'multiTermSearch']);
Route::get('/api/services/flexible-search', [ServiceAdvancedController::class, 'flexibleSearch']);
Route::get('/api/services/weighted-search', [ServiceAdvancedController::class, 'weightedSearch']);
Route::get('/api/services/combined-search', [ServiceAdvancedController::class, 'combinedSearch']);
Route::get('/api/services/debug-search', [ServiceAdvancedController::class, 'debugSearch']);

/*
USAGE EXAMPLES:

1. Basic Search:
   GET /api/services/search?search=web

2. Advanced Search:
   GET /api/services/advanced-search?name=web&category=development&min_price=100

3. Multi-term Search:
   GET /api/services/multi-search?search=web development mobile

4. Flexible Search:
   GET /api/services/flexible-search?search=web&columns[]=name&columns[]=description

5. Weighted Search:
   GET /api/services/weighted-search?search=web

6. Combined Search:
   GET /api/services/combined-search?search=web&category=development&min_price=100&max_price=500

7. Debug Search:
   GET /api/services/debug-search?search=web
*/