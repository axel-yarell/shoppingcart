<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\AdminController;

// Route::get('/', function () {
//     return view('welcome');
// });

// Client Routes
Route::get('/', [ClientController::class, 'home']);
Route::get('/shop', [ClientController::class, 'shop']);
Route::get('/cart', [ClientController::class, 'cart']);
Route::get('/checkout', [ClientController::class, 'checkout']);
Route::get('/register', [ClientController::class, 'register']);
Route::get('/signin', [ClientController::class, 'signin']);

// Admin Routes
Route::get('/admin', [AdminController::class, 'home']);
Route::get('/addcategory', [AdminController::class, 'addCategory']);
Route::get('/categories', [AdminController::class, 'categories']);
Route::get('/addslider', [AdminController::class, 'addSlider']);
Route::get('/sliders', [AdminController::class, 'sliders']);