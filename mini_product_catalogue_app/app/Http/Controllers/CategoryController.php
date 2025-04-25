<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function create()
    {
        $categories = Category::all();
        return view('pages.category', compact('categories'));
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:50|unique:categories,name',
        ]);
        Category::create($data);
        return back()->with('success', 'Category added successfully!');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return back()->with('success', 'Category deleted successfully!');
    }
}
