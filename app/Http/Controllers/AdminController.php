<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Models\Category;
use App\Models\Slider;


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
        $categories = Category::get();
        return view('admin.categories')->with('categories', $categories);
    }

    public function addSlider()
    {
        return view('admin.addslider');
    }

    public function sliders()
    {
        $sliders = Slider::get();
        return view('admin.sliders')->with('sliders', $sliders);
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
