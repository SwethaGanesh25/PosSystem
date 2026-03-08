<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->orderBy('created_at', 'desc')->get();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'category_id' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'image' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $imageName = null;
        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('images'), $imageName);
        }

        Product::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'category_id' => $request->category_id,
            'price' => $request->price,
            'stock' => $request->stock,
            'sku' => $request->sku,
            'description' => $request->description,
            'image' => $imageName,
        ]);

        return redirect()->route('products.index')->with('success', 'Product Created!');
    }
    // 1. Show the Edit Form
public function edit($id)
{
    $product = Product::findOrFail($id);
    $categories = Category::all();
    return view('products.edit', compact('product', 'categories'));
}

// 2. Update the Product
public function update(Request $request, $id)
{
    $product = Product::findOrFail($id);

    $request->validate([
        'name' => 'required',
        'category_id' => 'required',
        'price' => 'required|numeric',
        'stock' => 'required|integer',
    ]);

    $imageName = $product->image; // Keep old image by default

    if ($request->hasFile('image')) {
        // Delete old image if exists
        if ($product->image && file_exists(public_path('images/' . $product->image))) {
            unlink(public_path('images/' . $product->image));
        }
        
        $imageName = time() . '.' . $request->image->extension();
        $request->image->move(public_path('images'), $imageName);
    }

    $product->update([
        'name' => $request->name,
        'slug' => Str::slug($request->name),
        'category_id' => $request->category_id,
        'price' => $request->price,
        'stock' => $request->stock,
        'sku' => $request->sku,
        'description' => $request->description,
        'image' => $imageName,
    ]);

    return redirect()->route('products.index')->with('success', 'Product Updated!');
}

// 3. Delete the Product
public function destroy($id)
{
    $product = Product::findOrFail($id);

    
    if ($product->image && file_exists(public_path('images/' . $product->image))) {
        unlink(public_path('images/' . $product->image));
    }

    $product->delete();

    return redirect()->route('products.index')->with('success', 'Product Deleted!');
}



}