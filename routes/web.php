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