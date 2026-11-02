<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\AdminController;

class AdminController extends Controller
{
    //
    public function home()
    {
        return view('admin.home');
    }
    
    public function addCategory()
    {
        return view('admin.addcategory');
    }

    public function categories()
    {
        return view('admin.categories');
    }

    public function addSlider()
    {
        return view('admin.addslider');
    }

    public function sliders()
    {
        return view('admin.sliders');
    }

    public function addProduct()
    {
        return view('admin.addproduct');
    }

    public function products()
    {
        return view('admin.products');
    }

    public function orders()
    {
        return view('admin.orders');
    }
}
