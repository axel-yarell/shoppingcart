<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    //

    public function saveCategory(Request $request)
    {
        $category = new Category();
        $category->category_name = $request->input('category_name');
        $category->save();

        return redirect()->back()->with('status', 'Category added successfully!');
    }

    public function deleteCategory($id)
    {
        $category = Category::find($id);
        if ($category) {
            $category->delete();
            return redirect()->back()->with('status', 'Category deleted successfully!');
        } else {
            return redirect()->back()->with('status', 'Category not found!');
        }
    }

    public function editCategory($id)
    {
        $category = Category::find($id);
        if ($category) {
            return view('admin.editcategory')->with('category', $category);
        } else {
            return redirect()->back()->with('status', 'Category not found!');
        }
    }

    public function updateCategory(Request $request, $id)
    {
        $category = Category::find($id);
        if ($category) {
            $category->category_name = $request->input('category_name');
            $category->update();
            return redirect('/admin/categories')->with('status', 'Category updated successfully!');
        } else {
            return redirect()->back()->with('status', 'Category not found!');
        }
    }
}
