<?php
// PRT362S — Eben Supply | Group KN3

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    private function getCartQuery()
    {
        if (auth()->check()) {
            return CartItem::where('user_id', auth()->id());
        }
        return CartItem::where('session_id', session()->getId());
    }

    public function index()
    {
        $cartItems = $this->getCartQuery()->with('product')->get();
        $subtotal  = $cartItems->sum(fn($item) => $item->quantity * $item->product->price);

        return view('cart.index', compact('cartItems', 'subtotal'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'size'       => 'nullable|string',
            'quantity'   => 'required|integer|min:1|max:20',
        ]);

        $product = Product::findOrFail($request->product_id);

        $attributes = [
            'product_id' => $product->id,
            'size'       => $request->size,
        ];

        if (auth()->check()) {
            $attributes['user_id'] = auth()->id();
        } else {
            $attributes['session_id'] = session()->getId();
        }

        $existing = CartItem::where($attributes)->first();

        if ($existing) {
            $existing->increment('quantity', $request->quantity);
        } else {
            CartItem::create(array_merge($attributes, ['quantity' => $request->quantity]));
        }

        return redirect()->back()->with('success', 'Item added to cart!');
    }

    public function update(Request $request)
    {
        $request->validate([
            'cart_item_id' => 'required|exists:cart_items,id',
            'quantity'     => 'required|integer|min:1|max:20',
        ]);

        $item = CartItem::findOrFail($request->cart_item_id);
        $this->authorizeCartItem($item);
        $item->update(['quantity' => $request->quantity]);

        return redirect()->route('cart.index')->with('success', 'Cart updated.');
    }

    public function remove(Request $request)
    {
        $request->validate(['cart_item_id' => 'required|exists:cart_items,id']);

        $item = CartItem::findOrFail($request->cart_item_id);
        $this->authorizeCartItem($item);
        $item->delete();

        return redirect()->route('cart.index')->with('success', 'Item removed.');
    }

    private function authorizeCartItem(CartItem $item): void
    {
        if (auth()->check()) {
            abort_unless($item->user_id === auth()->id(), 403);
        } else {
            abort_unless($item->session_id === session()->getId(), 403);
        }
    }

    public static function getCount(): int
    {
        if (auth()->check()) {
            return CartItem::where('user_id', auth()->id())->sum('quantity');
        }
        return CartItem::where('session_id', session()->getId())->sum('quantity');
    }
}