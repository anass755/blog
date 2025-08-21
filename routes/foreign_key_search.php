<?php

use App\Http\Controllers\ServiceForeignKeyController;

// Foreign key search routes
Route::get('/api/services/search-wherehas', [ServiceForeignKeyController::class, 'searchWithWhereHas']);
Route::get('/api/services/search-joins', [ServiceForeignKeyController::class, 'searchWithJoins']);
Route::get('/api/services/search-advanced-fk', [ServiceForeignKeyController::class, 'advancedSearchWithForeignKeys']);
Route::get('/api/services/search-optimized', [ServiceForeignKeyController::class, 'optimizedSearch']);
Route::get('/api/services/compare-methods', [ServiceForeignKeyController::class, 'compareSearchMethods']);
Route::get('/api/services/debug-queries', [ServiceForeignKeyController::class, 'debugQueries']);

/*
USAGE EXAMPLES:

1. Search with whereHas (finds services in countries with "USA" in name):
   GET /api/services/search-wherehas?search=USA

2. Search with joins:
   GET /api/services/search-joins?search=web

3. Advanced foreign key search:
   GET /api/services/search-advanced-fk?service_name=web&country_name=USA&category_name=development

4. Optimized search:
   GET /api/services/search-optimized?search=john@example.com

5. Compare performance:
   GET /api/services/compare-methods?search=web

6. Debug SQL queries:
   GET /api/services/debug-queries?search=web
*/