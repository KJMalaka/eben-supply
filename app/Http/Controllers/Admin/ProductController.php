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
<<<<<<< HEAD
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::withCount('sizes')->latest()->paginate(15);
=======
use Illuminate\Support\Str;

class ProductController extends Controller
{
    private const SIZES = ['S', 'M', 'L', 'XL', 'XXL'];

    public function index()
    {
        $products = Product::with('sizes')->latest()->paginate(15);

>>>>>>> d8fa16a0a306b03d560b177d8046fb3e993b37bf
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
<<<<<<< HEAD
        $data = $request->validate([
            'name'        => 'required|string|max:150',
            'description' => 'nullable|string',
            'category'    => 'required|in:tshirt,cap,tote_bag',
            'price'       => 'required|numeric|min:0',
            'is_featured' => 'boolean',
            'image'       => 'nullable|image|max:2048',
            'sizes'       => 'nullable|array',
            'sizes.*'     => 'integer|min:0',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $imagePath = 'storage/' . $imagePath;
        }

        $product = Product::create([
            'name'           => $data['name'],
            'description'    => $data['description'] ?? null,
            'category'       => $data['category'],
            'price'          => $data['price'],
            'is_featured'    => $request->boolean('is_featured'),
            'image_path'     => $imagePath,
            'stock_quantity' => array_sum($data['sizes'] ?? []),
        ]);

        foreach ($data['sizes'] ?? [] as $size => $qty) {
            ProductSize::create([
                'product_id'     => $product->id,
                'size'           => $size,
                'stock_quantity' => $qty,
            ]);
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
=======
        $validated = $this->validateProduct($request);

        $product = Product::create([
            'name'        => $validated['name'],
            'category'    => $validated['category'],
            'description' => $validated['description'] ?? null,
            'price'       => $validated['price'],
            'is_featured' => $request->boolean('is_featured'),
            'image_path'  => $this->storeImage($request) ?? null,
            'stock_quantity' => 0,
        ]);

        $product->update([
            'stock_quantity' => $this->syncSizes($product, $request->input('sizes', [])),
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Product created.');
>>>>>>> d8fa16a0a306b03d560b177d8046fb3e993b37bf
    }

    public function edit(Product $product)
    {
        $product->load('sizes');
<<<<<<< HEAD
        $allSizes = ['S', 'M', 'L', 'XL', 'XXL'];
=======
        $allSizes = self::SIZES;

>>>>>>> d8fa16a0a306b03d560b177d8046fb3e993b37bf
        return view('admin.products.edit', compact('product', 'allSizes'));
    }

    public function update(Request $request, Product $product)
    {
<<<<<<< HEAD
        $data = $request->validate([
            'name'        => 'required|string|max:150',
            'description' => 'nullable|string',
            'category'    => 'required|in:tshirt,cap,tote_bag',
            'price'       => 'required|numeric|min:0',
            'is_featured' => 'boolean',
            'image'       => 'nullable|image|max:2048',
            'sizes'       => 'nullable|array',
            'sizes.*'     => 'integer|min:0',
        ]);

        $imagePath = $product->image_path;
        if ($request->hasFile('image')) {
            $imagePath = 'storage/' . $request->file('image')->store('products', 'public');
        }

        $product->update([
            'name'           => $data['name'],
            'description'    => $data['description'] ?? null,
            'category'       => $data['category'],
            'price'          => $data['price'],
            'is_featured'    => $request->boolean('is_featured'),
            'image_path'     => $imagePath,
            'stock_quantity' => array_sum($data['sizes'] ?? []),
        ]);

        foreach ($data['sizes'] ?? [] as $size => $qty) {
            ProductSize::updateOrCreate(
                ['product_id' => $product->id, 'size' => $size],
                ['stock_quantity' => $qty]
            );
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated.');
=======
        $validated = $this->validateProduct($request, $product);

        $product->name        = $validated['name'];
        $product->category    = $validated['category'];
        $product->description = $validated['description'] ?? null;
        $product->price       = $validated['price'];
        $product->is_featured = $request->boolean('is_featured');

        if ($newImagePath = $this->storeImage($request)) {
            $this->deleteImage($product->image_path);
            $product->image_path = $newImagePath;
        }

        $product->save();

        $product->update([
            'stock_quantity' => $this->syncSizes($product, $request->input('sizes', [])),
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Product updated.');
>>>>>>> d8fa16a0a306b03d560b177d8046fb3e993b37bf
    }

    public function destroy(Product $product)
    {
        $product->delete();
<<<<<<< HEAD
        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted.');
=======

        return redirect()->route('admin.products.index')->with('success', 'Product deleted.');
>>>>>>> d8fa16a0a306b03d560b177d8046fb3e993b37bf
    }

    public function toggleFeatured(Product $product)
    {
<<<<<<< HEAD
        $product->update(['is_featured' => !$product->is_featured]);
        return redirect()->back()->with('success', 'Featured status updated.');
=======
        $product->update(['is_featured' => ! $product->is_featured]);

        return redirect()->back();
    }

    private function validateProduct(Request $request, ?Product $product = null): array
    {
        return $request->validate([
            'name'        => 'required|string|max:150',
            'category'    => 'required|in:tshirt,cap,tote_bag',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'image'       => 'nullable|image|max:4096',
            'is_featured' => 'nullable|boolean',
            'sizes'       => 'nullable|array',
            'sizes.*'     => 'nullable|integer|min:0',
        ]);
    }

    /**
     * Move an uploaded image into public/images/products and return its
     * relative path, matching the existing asset('images/products/...') convention.
     */
    private function storeImage(Request $request): ?string
    {
        if (! $request->hasFile('image')) {
            return null;
        }

        $file = $request->file('image');
        $filename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
            . '-' . uniqid() . '.' . $file->getClientOriginalExtension();

        $file->move(public_path('images/products'), $filename);

        return 'images/products/' . $filename;
    }

    private function deleteImage(?string $imagePath): void
    {
        if ($imagePath && file_exists(public_path($imagePath))) {
            @unlink(public_path($imagePath));
        }
    }

    /**
     * Create/update/remove ProductSize rows from a ['S' => qty, ...] array
     * and return the total quantity across all sizes (becomes the product's
     * overall stock_quantity). Sizes left at 0 are removed rather than stored,
     * so products without size variants (caps, tote bags) don't accumulate
     * empty rows.
     */
    private function syncSizes(Product $product, array $sizesInput): int
    {
        $total = 0;

        foreach (self::SIZES as $size) {
            $quantity = (int) ($sizesInput[$size] ?? 0);

            if ($quantity > 0) {
                ProductSize::updateOrCreate(
                    ['product_id' => $product->id, 'size' => $size],
                    ['stock_quantity' => $quantity]
                );
                $total += $quantity;
            } else {
                ProductSize::where('product_id', $product->id)->where('size', $size)->delete();
            }
        }

        return $total;
>>>>>>> d8fa16a0a306b03d560b177d8046fb3e993b37bf
    }
}
