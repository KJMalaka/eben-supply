<?php
// PRT362S — Eben Supply | Group KN3

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalOrders    = Order::count();
        $pendingOrders  = Order::where('status', 'pending')->count();
        $totalProducts  = Product::count();

        // Low stock: the product total is under 5, or any single size is under 5.
        // This is the same rule the inventory page uses.
        $lowStockCount  = Product::where(function ($query) {
            $query->where('stock_quantity', '<', 5)
                  ->orWhereHas('sizes', function ($sizes) {
                      $sizes->where('stock_quantity', '<', 5);
                  });
        })->count();

        $recentOrders   = Order::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalOrders', 'pendingOrders', 'totalProducts',
            'lowStockCount', 'recentOrders'
        ));
    }
}