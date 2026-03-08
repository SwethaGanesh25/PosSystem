<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Total Sales Today
        $totalSalesToday = Order::whereDate('created_at', today())->sum('total');

        // 2. Total Orders Today
        $totalOrdersToday = Order::whereDate('created_at', today())->count();

        // 3. Total Products in Stock
        $totalProducts = Product::count();

        // 4. Recent 5 Orders
        $recentOrders = Order::with('user')->latest()->take(5)->get();

        // 5. Data for Chart (Last 7 Days Sales)
        $salesData = Order::selectRaw('DATE(created_at) as date, SUM(total) as total')
                            ->where('created_at', '>=', now()->subDays(7))
                            ->groupBy('date')
                            ->orderBy('date', 'asc')
                            ->get();

        // Prepare arrays for Chart.js
        $chartLabels = $salesData->pluck('date')->map(function($date) {
            return \Carbon\Carbon::parse($date)->format('M d'); // e.g., "Mar 06"
        });
        
        $chartValues = $salesData->pluck('total');

        return view('dashboard', compact(
            'totalSalesToday', 
            'totalOrdersToday', 
            'totalProducts', 
            'recentOrders',
            'chartLabels',
            'chartValues'
        ));
    }
}