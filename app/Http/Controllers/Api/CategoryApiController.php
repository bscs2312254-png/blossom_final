<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class CategoryApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::query();
        
        if ($request->has('active') && $request->active == '1') {
            $query->where('is_active', true);
        }
        
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        $query->orderBy('sort_order');
        
        $perPage = $request->get('per_page', 10);
        $categories = $query->paginate($perPage);
        
        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = [
                'name' => $request->name,
                'description' => $request->description,
                'sort_order' => $request->sort_order ?? 0,
                'is_active' => $request->has('is_active') ? $request->is_active : true
            ];
            
            if ($request->hasFile('image')) {
                $imageName = time() . '_' . Str::slug($request->name) . '.' . $request->image->extension();
                $request->image->move(public_path('uploads/categories'), $imageName);
                $data['image'] = $imageName;
            }
            
            $category = Category::create($data);
            
            return response()->json([
                'success' => true,
                'message' => 'Category created successfully.',
                'data' => $category
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create category.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $category = Category::with('products')->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $category
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found.'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $category = Category::findOrFail($id);
            
            $data = [
                'name' => $request->name,
                'description' => $request->description,
                'sort_order' => $request->sort_order ?? $category->sort_order,
                'is_active' => $request->has('is_active') ? $request->is_active : $category->is_active
            ];
            
            if ($request->hasFile('image')) {
                $oldImagePath = public_path('uploads/categories/' . $category->image);
                if ($category->image && file_exists($oldImagePath) && !str_contains($category->image, '/')) {
                    unlink($oldImagePath);
                }
                
                $imageName = time() . '_' . Str::slug($request->name) . '.' . $request->image->extension();
                $request->image->move(public_path('uploads/categories'), $imageName);
                $data['image'] = $imageName;
            }
            
            $category->update($data);
            
            return response()->json([
                'success' => true,
                'message' => 'Category updated successfully.',
                'data' => $category
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update category.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);
            
            if ($category->products()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete category with existing products.'
                ], 422);
            }
            
            $imagePath = public_path('uploads/categories/' . $category->image);
            if ($category->image && file_exists($imagePath) && !str_contains($category->image, '/')) {
                unlink($imagePath);
            }

            $category->delete();

            return response()->json([
                'success' => true,
                'message' => 'Category deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete category.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

