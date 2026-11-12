<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;


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


    public function deleteproduct($id)
    {
        $product = Product::find($id);
        if ($product) {
            // Supprimer le fichier image du dossier public/product_images
            $imagePath = public_path('product_images/' . $product->product_image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }

            $product->delete();
            return redirect()->back()->with('status', 'Product deleted successfully!');
        } 
            else {
                return redirect()->back()->with('status', 'Product not found!');
                }
    }

    public function editproduct($id)
    {
        $product = Product::find($id);
           if ($product) {
            // On récupère toutes les catégories pour le menu déroulant
            $categories = Category::where('category_name', '!=', $product->product_category)->get();
            // $categories = Category::all();
            
            // On envoie le produit ET les catégories à la vue
            return view('admin.editproduct', compact('product', 'categories'));
        } else {
            return redirect()->back()->with('status', 'Product not found!');
        }
    }
    public function updateproduct(Request $request, $id)
    {
       
        $product = Product::find($id);
        if ($product) {
            $product->product_name = $request->input('product_name');
            $product->product_price = $request->input('product_price');
            $product->product_category = $request->input('product_category');

            if ($request->hasFile('product_image')) {
                // Supprimer l'ancienne image
                $imagePath = public_path('product_images/' . $product->product_image);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }

                // Enregistrer la nouvelle image
                $file = $request->file('product_image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('product_images'), $filename);
                $product->product_image = $filename;
            }

            $product->update();
            return redirect()->back()->with('status', 'product updated successfully!');
        } else {
            return redirect()->back()->with('status', 'product not found!');
        }

    }

    public function unactivateproduct(Request $request, $id)
    {
        $product = Product::find($id);
        if ($product) {
            $product->status = 0; // Assuming 0 means inactive
            $product->update();
            return redirect()->back()->with('status', 'Product status updated successfully!');
        } else {
            return redirect()->back()->with('status', 'Product not found!');
        }
    }

    public function activateproduct(Request $request, $id)
    {
        $product = Product::find($id);
        if ($product) {
            $product->status = 1; // Assuming 1 means active
            $product->update();
            return redirect()->back()->with('status', 'Product status updated successfully!');
        } else {
            return redirect()->back()->with('status', 'Product not found!');
        }
    }


    








}
