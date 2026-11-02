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
Route::get('/admin/addcategory', [AdminController::class, 'addCategory']);
Route::get('/admin/categories', [AdminController::class, 'categories']);
Route::get('/admin/addslider', [AdminController::class, 'addSlider']);
Route::get('/admin/sliders', [AdminController::class, 'sliders']);
Route::get('/admin/addproduct', [AdminController::class, 'addProduct']);
Route::get('/admin/products', [AdminController::class, 'products']);
Route::get('/admin/orders', [AdminController::class, 'orders']);