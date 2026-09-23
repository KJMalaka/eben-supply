<?php
<<<<<<< HEAD
// 230443370 Katlego Malaka | Group KN3 
=======
>>>>>>> d8fa16a0a306b03d560b177d8046fb3e993b37bf

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
<<<<<<< HEAD
use App\Models\ProductSize;
use App\Models\User;
=======
>>>>>>> d8fa16a0a306b03d560b177d8046fb3e993b37bf

class DashboardController extends Controller
{
    public function index()
    {
<<<<<<< HEAD
        $totalOrders    = Order::count();
        $pendingOrders  = Order::where('status', 'pending')->count();
        $totalProducts  = Product::count();
        $lowStockCount  = Product::where('stock_quantity', '<', 5)->count();
        $recentOrders   = Order::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalOrders', 'pendingOrders', 'totalProducts',
            'lowStockCount', 'recentOrders'
=======
        $totalOrders   = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $totalProducts = Product::count();
        $lowStockCount = Product::where('stock_quantity', '<', 5)->count();
        $recentOrders  = Order::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalOrders', 'pendingOrders', 'totalProducts', 'lowStockCount', 'recentOrders'
>>>>>>> d8fa16a0a306b03d560b177d8046fb3e993b37bf
        ));
    }
}
