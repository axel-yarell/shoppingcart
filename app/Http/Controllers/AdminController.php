<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Models\Category;
use App\Models\Slider;
use App\Models\Product;
use App\Models\Order;

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
        $categories = Category::get();
        return view('admin.addproduct')->with('categories', $categories);
    }

    public function products()
    {
        $products = Product::get();
        return view('admin.products')->with('products',$products);
    }

    public function orders()
    {
        $orders = Order::all();
        $orders->transform(function ($order, $key) {
            $order->cart = unserialize($order->cart);
            return $order;
        });
        return view('admin.orders')->with('orders', $orders);
    }
}
