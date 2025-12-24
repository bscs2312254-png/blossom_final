<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class ProductApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();
        
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        if ($request->has('category') && !empty($request->category)) {
            $query->where('category', $request->category);
        }
        
        if ($request->has('featured') && $request->featured == '1') {
            $query->where('featured', true);
        }
        
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'name':
                    $query->orderBy('name');
                    break;
                case 'price_asc':
                    $query->orderBy('price');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'featured':
                    $query->orderBy('featured', 'desc')->orderBy('name');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }
        
        $perPage = $request->get('per_page', 10);
        $products = $query->with('productCategory')->paginate($perPage);
        
        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string',
            'stock' => 'required|integer|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'featured' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $imageName = time() . '_' . Str::slug($request->name) . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/products'), $imageName);

            $category = Category::where('slug', $request->category)->first();
            
            $product = Product::create([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'image' => $imageName,
                'category' => $request->category,
                'category_id' => $category->id ?? null,
                'stock' => $request->stock ?? 100,
                'featured' => $request->has('featured') ? $request->featured : false
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Product created successfully.',
                'data' => $product->load('productCategory')
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create product.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $product = Product::with('productCategory')->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $product
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found.'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'featured' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $product = Product::findOrFail($id);
            
            $data = [
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'category' => $request->category,
                'category_id' => Category::where('slug', $request->category)->first()->id ?? null,
                'stock' => $request->stock ?? $product->stock,
                'featured' => $request->has('featured') ? $request->featured : $product->featured
            ];

            if ($request->hasFile('image')) {
                $oldImagePath = public_path('uploads/products/' . $product->image);
                if ($product->image && file_exists($oldImagePath) && !str_contains($product->image, '/')) {
                    unlink($oldImagePath);
                }
                
                $imageName = time() . '_' . Str::slug($request->name) . '.' . $request->image->extension();
                $request->image->move(public_path('uploads/products'), $imageName);
                $data['image'] = $imageName;
            }

            $product->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully.',
                'data' => $product->load('productCategory')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update product.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            
            $imagePath = public_path('uploads/products/' . $product->image);
            if ($product->image && file_exists($imagePath) && !str_contains($product->image, '/')) {
                unlink($imagePath);
            }
            
            $product->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete product.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

