<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class PosController extends Controller
{
    // 1. Show POS Screen (Products + Cart)
    public function index()
    {
        // Only get products that have stock
        $products = Product::where('stock', '>', 0)->get();
        
        // Get current cart from session
        $cart = session()->get('cart', []);
        
        return view('pos.index', compact('products', 'cart'));
    }

    // 2. Add Item to Cart
    public function addCart($id)
    {
        $product = Product::findOrFail($id);

        $cart = session()->get('cart', []);

        // If item exists, increment quantity
        if(isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            // Add new item
            $cart[$id] = [
                "name" => $product->name,
                "price" => $product->price,
                "quantity" => 1,
                "image" => $product->image
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Product added to cart!');
    }

    // 3. Remove Item from Cart
    public function removeCart($id)
    {
        $cart = session()->get('cart', []);
        if(isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        return redirect()->back()->with('success', 'Product removed!');
    }

    // 4. Process Order (Checkout)
    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);
        
        if(count($cart) == 0) {
            return redirect()->back()->with('error', 'Cart is empty!');
        }

        $total = 0;
        foreach($cart as $id => $details) {
            $total += $details['price'] * $details['quantity'];
        }

        // Create Order Header
        $order = Order::create([
            'user_id' => auth()->id(),
            'total' => $total,
            'customer_name' => $request->customer_name ?? 'Walk-in Customer',
            'payment_method' => 'cash'
        ]);

        // Create Order Items & Subtract Stock
        foreach($cart as $id => $details) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $id,
                'quantity' => $details['quantity'],
                'price' => $details['price']
            ]);

            // Subtract Stock from Database
            $product = Product::find($id);
            $product->stock -= $details['quantity'];
            $product->save();
        }

        // Clear Cart Session
        session()->forget('cart');
        

    return redirect()->route('pos.index')->with('success', 'Order Placed!')->with('order_id', $order->id);
    }
    
}