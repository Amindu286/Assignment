<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'products');
        $categories = Category::all();

        $products   = Product::with('category')->orderBy('created_at', 'desc')->paginate(5);
        return view('dashboard', compact('tab', 'categories', 'products'));
    }
}
