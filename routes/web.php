<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Category; 
use Illuminate\Http\Request;
use Illuminate\Support\Str;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';





// Frontend Routes
Route::get('/', function () {
    $products = Product::where('featured', true)->limit(4)->get();
    $categories = Category::where('is_active', true)->get();
    return view('home', compact('products', 'categories'));
})->name('home');

// Updated Products Route with Search & Sorting
Route::get('/products', function (Request $request) {
    $query = Product::query();
    
    // Search functionality
    if ($request->has('search') && !empty($request->search)) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }
    
    // Category filter
    if ($request->has('category') && !empty($request->category)) {
        $query->where('category', $request->category);
    }
    
    // Sorting
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
    
    $products = $query->paginate(12)->withQueryString();
    
    return view('products', compact('products'));
})->name('products');

Route::get('/product/{id}', function ($id) {
    $product = Product::findOrFail($id);
    return view('product-detail', compact('product'));
})->name('product.detail');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::get('/cart', function () {
    $cart = session()->get('cart', []);
    return view('cart', compact('cart'));
})->name('cart');

// Ajax Search Route
Route::get('/search/products', function (Request $request) {
    $query = $request->get('q');

    if (!$query || strlen($query) < 2) {
        return response()->json([]);
    }

    $products = Product::where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->orWhereHas('categoryRelation', function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->with('categoryRelation')
            ->limit(10)
            ->get();

    $results = $products->map(function($product) {
        $imageUrl = $product->image ? 
            (file_exists(public_path('uploads/products/' . $product->image)) ? 
                asset('uploads/products/' . $product->image) : 
                asset('images/' . $product->image)) : 
            asset('images/default.jpg');

        return [
            'id' => $product->id,
            'name' => $product->name,
            'price' => number_format($product->price, 2),
            'category' => $product->categoryRelation ? $product->categoryRelation->name : $product->category,
            'image' => $imageUrl,
            'url' => route('product.detail', $product->id)
        ];
    });

    return response()->json($results);
})->name('search.products');

// Cart Routes
Route::post('/cart/add', function (Request $request) {
    $cart = session()->get('cart', []);
    $cart[$request->id] = [
        'id' => $request->id,
        'name' => $request->name,
        'price' => $request->price,
        'qty' => isset($cart[$request->id]) ? $cart[$request->id]['qty'] + 1 : 1,
        'image' => $request->image
    ];
    session()->put('cart', $cart);
    return redirect()->back()->with('success', 'Product added to cart!');
})->name('cart.add');

Route::post('/cart/update', function (Request $request) {
    $cart = session()->get('cart', []);
    foreach ($request->qty as $id => $quantity) {
        if ($quantity > 0) {
            $cart[$id]['qty'] = $quantity;
        } else {
            unset($cart[$id]);
        }
    }
    session()->put('cart', $cart);
    return redirect()->route('cart')->with('success', 'Cart updated!');
})->name('cart.update');

Route::get('/cart/clear', function () {
    session()->forget('cart');
    return redirect()->route('cart')->with('success', 'Cart cleared!');
})->name('cart.clear');

Route::get('/checkout', function () {
    $cart = session()->get('cart', []);
    if (empty($cart)) {
        return redirect()->route('cart')->with('error', 'Your cart is empty!');
    }
    return view('checkout', compact('cart'));
})->name('checkout');

Route::post('/checkout', function () {
    session()->forget('cart');
    return view('success');
})->name('checkout.process');

Route::get('/success', function () {
    return view('success');
})->name('success');

// Simple Login/Logout
Route::get('/login', function() {
    return view('auth.login');
})->name('login');



Route::get('/logout', function() {
    session()->forget('admin');
    return redirect('/');
})->name('logout');

// Admin Routes - ALL INSIDE THIS PREFIX GROUP
Route::middleware('auth')->prefix('admin')->group(function () {
    // Admin Dashboard
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
    
    // Product CRUD Routes
    Route::get('/products', function () {
        $products = Product::paginate(10);
        return view('admin.products.index', compact('products'));
    })->name('admin.products.index');
    
    // UPDATED: Product Create with Categories
    Route::get('/products/create', function () {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    })->name('admin.products.create');
    
    // UPDATED: Product Store with Image Upload
    Route::post('/products', function (Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string',
            'stock' => 'required|integer|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'featured' => 'boolean'
        ]);

        // Handle image upload
        $imageName = time() . '_' . Str::slug($request->name) . '.' . $request->image->extension();
        $request->image->move(public_path('uploads/products'), $imageName);

        Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'image' => $imageName,
            'category' => $request->category,
            'category_id' => Category::where('slug', $request->category)->first()->id ?? null,
            'stock' => $request->stock ?? 100,
            'featured' => $request->has('featured')
        ]);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    })->name('admin.products.store');
    
    // UPDATED: Product Edit with Categories
    Route::get('/products/{id}/edit', function ($id) {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    })->name('admin.products.edit');
    
    // UPDATED: Product Update with Image Upload
    Route::put('/products/{id}', function (Request $request, $id) {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'featured' => 'boolean'
        ]);

        $product = Product::findOrFail($id);
        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'category' => $request->category,
            'category_id' => Category::where('slug', $request->category)->first()->id ?? null,
            'stock' => $request->stock ?? $product->stock,
            'featured' => $request->has('featured')
        ];

        // Handle image upload if new image provided
        if ($request->hasFile('image')) {
            // Delete old image if exists in uploads folder
            $oldImagePath = public_path('uploads/products/' . $product->image);
            if ($product->image && file_exists($oldImagePath) && !str_contains($product->image, '/')) {
                unlink($oldImagePath);
            }
            
            $imageName = time() . '_' . Str::slug($request->name) . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/products'), $imageName);
            $data['image'] = $imageName;
        }

        $product->update($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    })->name('admin.products.update');
    
    Route::delete('/products/{id}', function ($id) {
        $product = Product::findOrFail($id);
        
        // Delete product image if exists
        $imagePath = public_path('uploads/products/' . $product->image);
        if ($product->image && file_exists($imagePath) && !str_contains($product->image, '/')) {
            unlink($imagePath);
        }
        
        $product->delete();
        
        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    })->name('admin.products.destroy');
    
    // Category Routes
    Route::get('/categories', function () {
        $categories = Category::orderBy('sort_order')->paginate(10);
        return view('admin.categories.index', compact('categories'));
    })->name('admin.categories.index');
    
    Route::get('/categories/create', function () {
        return view('admin.categories.create');
    })->name('admin.categories.create');
    
    Route::post('/categories', function (Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'sort_order' => 'nullable|integer',
        ]);
        
        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active')
        ];
        
        // Handle image upload if provided
        if ($request->hasFile('image')) {
            $imageName = time() . '_' . Str::slug($request->name) . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/categories'), $imageName);
            $data['image'] = $imageName;
        }
        
        Category::create($data);
        
        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    })->name('admin.categories.store');
    
    Route::get('/categories/{id}/edit', function ($id) {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    })->name('admin.categories.edit');
    
    Route::put('/categories/{id}', function (Request $request, $id) {
        $category = Category::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'sort_order' => 'nullable|integer',
        ]);
        
        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'sort_order' => $request->sort_order ?? $category->sort_order,
            'is_active' => $request->has('is_active')
        ];
        
        // Handle image upload if new image provided
        if ($request->hasFile('image')) {
            // Delete old image if exists in uploads folder
            $oldImagePath = public_path('uploads/categories/' . $category->image);
            if ($category->image && file_exists($oldImagePath) && !str_contains($category->image, '/')) {
                unlink($oldImagePath);
            }
            
            $imageName = time() . '_' . Str::slug($request->name) . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/categories'), $imageName);
            $data['image'] = $imageName;
        }
        
        $category->update($data);
        
        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    })->name('admin.categories.update');
    
    Route::delete('/categories/{id}', function ($id) {
        $category = Category::findOrFail($id);
        
        // Check if category has products
        if ($category->products()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Cannot delete category with existing products.');
        }
        
        // Delete category image if exists
        $imagePath = public_path('uploads/categories/' . $category->image);
        if ($category->image && file_exists($imagePath) && !str_contains($category->image, '/')) {
            unlink($imagePath);
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    })->name('admin.categories.destroy');
});