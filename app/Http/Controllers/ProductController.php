<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Home page
    public function home()
    {
        $products = Product::active()->take(3)->get();
        return view('home', compact('products'));
    }

    // Products page with filtering
    public function index(Request $request)
    {
        $category = $request->get('category');
        $products = Product::active()->category($category)->get();
        
        return view('products', compact('products', 'category'));
    }

    // Product details page - FIXED VIEW NAME
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('product-details', compact('product'));
    }

    // Admin - Product list
    public function adminIndex()
    {
        $products = Product::all();
        return view('admin.products.index', compact('products'));
    }

    // Admin - Create form
    public function create()
    {
        return view('admin.products.create');
    }

    // Admin - Store product
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string',
            'type' => 'required|string',
            'image' => 'required|string',
            'stock' => 'required|integer|min:0',
        ]);

        Product::create($request->all());

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully!');
    }

    // Admin - Edit form
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.products.edit', compact('product'));
    }

    // Admin - Update product
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string',
            'type' => 'required|string',
            'image' => 'required|string',
            'stock' => 'required|integer|min:0',
        ]);

        $product = Product::findOrFail($id);
        $product->update($request->all());

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully!');
    }

    // Admin - Delete product
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully!');
    }
}