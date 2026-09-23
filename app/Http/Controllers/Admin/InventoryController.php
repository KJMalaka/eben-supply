<?php
<<<<<<< HEAD
// 230443370 Katlego Malaka | Group KN3
=======
>>>>>>> d8fa16a0a306b03d560b177d8046fb3e993b37bf

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductSize;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
<<<<<<< HEAD
        $products = Product::with('sizes')->latest()->get();
=======
        $products = Product::with('sizes')->orderBy('name')->get();

>>>>>>> d8fa16a0a306b03d560b177d8046fb3e993b37bf
        return view('admin.inventory.index', compact('products'));
    }

    public function update(Request $request)
    {
        $request->validate([
<<<<<<< HEAD
            'stock'    => 'required|array',
            'stock.*'  => 'integer|min:0',
            'sizes'    => 'nullable|array',
            'sizes.*'  => 'integer|min:0',
        ]);

        // Update product-level stock
        foreach ($request->stock as $productId => $qty) {
            Product::where('id', $productId)->update(['stock_quantity' => $qty]);
        }

        // Update size-level stock
        foreach ($request->sizes ?? [] as $sizeId => $qty) {
            ProductSize::where('id', $sizeId)->update(['stock_quantity' => $qty]);
        }

        // Recalculate totals for products that have sizes
        $products = Product::with('sizes')->whereHas('sizes')->get();
        foreach ($products as $product) {
            $product->update(['stock_quantity' => $product->sizes->sum('stock_quantity')]);
        }

        return redirect()->route('admin.inventory')->with('success', 'Stock levels updated.');
=======
            'stock'    => 'array',
            'stock.*'  => 'integer|min:0',
            'sizes'    => 'array',
            'sizes.*'  => 'integer|min:0',
        ]);

        // Products without size variants — stock is edited directly.
        foreach ($request->input('stock', []) as $productId => $quantity) {
            Product::whereKey($productId)->update(['stock_quantity' => $quantity]);
        }

        // Products with size variants — update each size, then re-total the
        // parent product's stock_quantity so dashboard/low-stock badges stay accurate.
        $touchedProductIds = [];
        foreach ($request->input('sizes', []) as $sizeId => $quantity) {
            $size = ProductSize::find($sizeId);
            if (! $size) {
                continue;
            }
            $size->update(['stock_quantity' => $quantity]);
            $touchedProductIds[$size->product_id] = true;
        }

        foreach (array_keys($touchedProductIds) as $productId) {
            $total = ProductSize::where('product_id', $productId)->sum('stock_quantity');
            Product::whereKey($productId)->update(['stock_quantity' => $total]);
        }

        return redirect()->route('admin.inventory')->with('success', 'Inventory updated.');
>>>>>>> d8fa16a0a306b03d560b177d8046fb3e993b37bf
    }
}
