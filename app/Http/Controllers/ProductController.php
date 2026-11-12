<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
class ProductController extends Controller
{
    //
    public function saveproduct(Request $request)
    {
        $request->validate([
            'product_name' => 'required',
            'product_category' => 'required',
            'product_price' => 'required|numeric',
            'product_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $product = new \App\Models\Product();
        $product->product_name = $request->input('product_name');
        $product->product_category = $request->input('product_category');
        $product->product_price = $request->input('product_price');

        if ($request->hasFile('product_image')) {
            $file = $request->file('product_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            
            // On déplace le fichier physique dans le dossier public/product_images
            $file->move(public_path('product_images'), $filename);
            
            // On enregistre UNIQUEMENT le nom du fichier (ex: 1793809189_product-01.jpg)
            $product->product_image = $filename; 
        }

        $product->save();

        return redirect()->back()->with('status', 'Product added successfully!');
    }
}
