<?php
<<<<<<< HEAD
// 230443370 Katlego Malaka | Group KN3
=======
>>>>>>> d8fa16a0a306b03d560b177d8046fb3e993b37bf

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
<<<<<<< HEAD
    public function index(Request $request)
    {
        $query = Order::with('user')->latest();

=======
    private const STATUSES = ['pending', 'confirmed', 'ready', 'collected'];

    public function index(Request $request)
    {
        $statuses = self::STATUSES;

        $query = Order::query();
>>>>>>> d8fa16a0a306b03d560b177d8046fb3e993b37bf
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

<<<<<<< HEAD
        $orders   = $query->paginate(20)->withQueryString();
        $statuses = ['pending', 'confirmed', 'ready', 'collected'];
=======
        $orders = $query->latest()->paginate(15)->withQueryString();
>>>>>>> d8fa16a0a306b03d560b177d8046fb3e993b37bf

        return view('admin.orders.index', compact('orders', 'statuses'));
    }

    public function show(Order $order)
    {
<<<<<<< HEAD
        $order->load('items.product', 'user');
=======
        $order->load('items.product');

>>>>>>> d8fa16a0a306b03d560b177d8046fb3e993b37bf
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
<<<<<<< HEAD
            'status' => 'required|in:pending,confirmed,ready,collected',
=======
            'status' => 'required|in:' . implode(',', self::STATUSES),
>>>>>>> d8fa16a0a306b03d560b177d8046fb3e993b37bf
        ]);

        $order->update(['status' => $request->status]);

<<<<<<< HEAD
        return redirect()->back()->with('success', 'Order status updated.');
=======
        return redirect()->route('admin.orders.show', $order)->with('success', 'Order status updated.');
>>>>>>> d8fa16a0a306b03d560b177d8046fb3e993b37bf
    }
}
