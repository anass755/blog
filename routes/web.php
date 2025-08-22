<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServiceController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Agency routes
Route::get('/agency', function () {
    return view('agency.index');
})->name('agency.index');

// Service routes for AJAX calls
Route::get('/api/services', [ServiceController::class, 'getServices'])->name('api.services');
Route::post('/api/services/selected', [ServiceController::class, 'getSelectedServices'])->name('api.services.selected');

// DEMO ROUTES - No database required!
use App\Http\Controllers\ServiceDemoController;

Route::get('/agency/demo', function () {
    return view('agency.demo');
})->name('agency.demo');

Route::get('/api/demo/info', [ServiceDemoController::class, 'getDemoInfo']);
Route::get('/api/demo/services', [ServiceDemoController::class, 'getServices']);
Route::get('/api/demo/services/{id}', [ServiceDemoController::class, 'getServiceById']);
Route::get('/api/demo/services/category', [ServiceDemoController::class, 'getServicesByCategory']);
Route::get('/api/demo/categories', [ServiceDemoController::class, 'getCategories']);
Route::get('/api/demo/statistics', [ServiceDemoController::class, 'getStatistics']);
Route::get('/api/demo/advanced-search', [ServiceDemoController::class, 'advancedSearch']);