<?php
// PRT362S — Eben Supply | Group KN3

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductSize;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    /** Uploaded images live under public/ so they work without a storage symlink. */
    private const UPLOAD_DIR = 'images/products/uploads';

    private const SIZES = ['S', 'M', 'L', 'XL', 'XXL'];

    public function index()
    {
        $products = Product::withCount('sizes')->latest()->paginate(15);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $allSizes = self::SIZES;
        return view('admin.products.create', compact('allSizes'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        $product = DB::transaction(function () use ($request, $data) {
            $product = Product::create($this->attributes($request, $data) + [
                'stock_quantity' => 0,
                'image_path'     => $request->hasFile('image') ? $this->storeImage($request->file('image')) : null,
            ]);
            $this->syncStock($product, $data);

            return $product;
        });

        return redirect()->route('admin.products.index')
            ->with('success', "Product \"{$product->name}\" created.");
    }

    public function edit(Product $product)
    {
        $product->load('sizes');
        $allSizes = self::SIZES;
        return view('admin.products.edit', compact('product', 'allSizes'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validated($request);

        DB::transaction(function () use ($request, $data, $product) {
            $attributes = $this->attributes($request, $data);

            if ($request->hasFile('image')) {
                $this->deleteUploadedImage($product->image_path);
                $attributes['image_path'] = $this->storeImage($request->file('image'));
            }

            $product->update($attributes);
            $this->syncStock($product, $data);
        });

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated.');
    }

    /**
     * Soft delete: past orders keep pointing at the product. It is also removed
     * from every shopper's cart so nobody can check out with it.
     */
    public function destroy(Product $product)
    {
        DB::transaction(function () use ($product) {
            DB::table('cart_items')->where('product_id', $product->id)->delete();
            $product->delete();
        });

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted.');
    }

    public function toggleFeatured(Product $product)
    {
        $product->update(['is_featured' => !$product->is_featured]);
        return redirect()->back()->with('success', 'Featured status updated.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name'           => 'required|string|max:150',
            'description'    => 'nullable|string',
            'category'       => 'required|in:tshirt,cap,tote_bag',
            'price'          => 'required|numeric|min:0.01|max:99999',
            'is_featured'    => 'boolean',
            'image'          => 'nullable|image|max:2048',
            // "sized" = stock per size, "one_size" = a single stock number.
            // If the form does not send it, the product is treated as sized.
            'sizing'         => 'nullable|in:sized,one_size',
            'stock_quantity' => 'required_if:sizing,one_size|nullable|integer|min:0',
            'sizes'          => 'nullable|array',
            'sizes.*'        => 'integer|min:0',
        ]);

        $data['sizing'] = $data['sizing'] ?? 'sized';

        if ($data['sizing'] === 'sized') {
            $unknown = array_diff(array_keys($data['sizes'] ?? []), self::SIZES);
            if ($unknown) {
                throw ValidationException::withMessages([
                    'sizes' => 'Not a valid size: ' . implode(', ', $unknown),
                ]);
            }
        }

        return $data;
    }

    private function attributes(Request $request, array $data): array
    {
        return [
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
            'category'    => $data['category'],
            'price'       => $data['price'],
            'is_featured' => $request->boolean('is_featured'),
        ];
    }

    /**
     * Sized products keep one row per size and a total that matches the sum.
     * One-size products (caps, totes) have no size rows and store their stock
     * on the product itself, so saving never wipes their stock to 0.
     */
    private function syncStock(Product $product, array $data): void
    {
        if ($data['sizing'] === 'one_size') {
            $product->sizes()->delete();
            $product->update(['stock_quantity' => (int) $data['stock_quantity']]);
            return;
        }

        $product->sizes()->whereNotIn('size', self::SIZES)->delete();

        foreach (self::SIZES as $size) {
            ProductSize::updateOrCreate(
                ['product_id' => $product->id, 'size' => $size],
                ['stock_quantity' => (int) ($data['sizes'][$size] ?? 0)]
            );
        }

        $product->update(['stock_quantity' => $product->sizes()->sum('stock_quantity')]);
    }

    private function storeImage(UploadedFile $file): string
    {
        File::ensureDirectoryExists(public_path(self::UPLOAD_DIR));
        $name = $file->hashName();
        $file->move(public_path(self::UPLOAD_DIR), $name);

        return self::UPLOAD_DIR . '/' . $name;
    }

    private function deleteUploadedImage(?string $path): void
    {
        // Only remove files this controller uploaded, never the seeded catalogue images.
        if ($path && str_starts_with($path, self::UPLOAD_DIR . '/')) {
            File::delete(public_path($path));
        }
    }
}
