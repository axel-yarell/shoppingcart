<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SliderController;

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

// Category controller

Route::post('/admin/savecategory', [CategoryController::class, 'saveCategory']);
Route::delete('/admin/deletecategory/{id}', [CategoryController::class, 'deleteCategory']);
Route::get('/admin/editcategory/{id}', [CategoryController::class, 'editCategory']);
Route::put('/admin/updatecategory/{id}', [CategoryController::class, 'updateCategory']);

// Slider Routes

Route::post('/admin/saveslider', [SliderController::class, 'saveSlider']);
Route::delete('/admin/deleteslider/{id}', [SliderController::class, 'deleteSlider']);
Route::get('/admin/editslider/{id}', [SliderController::class, 'editSlider']);
Route::put('/admin/updateslider/{id}', [SliderController::class, 'updateSlider']);