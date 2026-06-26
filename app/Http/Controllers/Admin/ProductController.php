<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        $perPage = $request->per_page ?: 10;
        $products = $perPage === 'all' ? $query->ordered()->get() : $query->ordered()->paginate((int)$perPage)->withQueryString();
        $categories = Category::active()->ordered()->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::active()->ordered()->get();
        return view('admin.products.form', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'nullable|required_without:has_variants|integer|min:0',
            'has_variants' => 'boolean',
            'price_hot' => 'required_if:has_variants,1|nullable|integer|min:0',
            'price_cold' => 'required_if:has_variants,1|nullable|integer|min:0',
            'image' => 'nullable|file|mimes:jpg,jpeg,png,webp,gif|max:5120',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'is_new' => 'boolean',
            'is_promo' => 'boolean',
            'promo_price' => 'nullable|integer|min:0',
            'is_seasonal' => 'boolean',
            'seasonal_label' => 'nullable|string|max:50',
        ]);

        $data = $request->only(['name', 'category_id', 'description', 'price', 'seasonal_label']);
        $data['has_variants'] = $request->boolean('has_variants');
        $data['is_active'] = $request->boolean('is_active', true);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_new'] = $request->boolean('is_new');
        $data['is_promo'] = $request->boolean('is_promo');
        $data['is_seasonal'] = $request->boolean('is_seasonal');
        $data['promo_price'] = $request->is_promo ? $request->promo_price : null;

        if ($data['has_variants']) {
            $data['price_hot'] = (int) $request->price_hot;
            $data['price_cold'] = (int) $request->price_cold;
            $data['price'] = min($data['price_hot'], $data['price_cold']);
        } else {
            $data['price'] = (int) $request->price;
            $data['price_hot'] = null;
            $data['price_cold'] = null;
        }

        if ($request->hasFile('image')) {
            $data['image'] = $this->storeProductImage($request->file('image'));
        }

        Product::create($data);
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        $categories = Category::active()->ordered()->get();
        return view('admin.products.form', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'nullable|required_without:has_variants|integer|min:0',
            'has_variants' => 'boolean',
            'price_hot' => 'required_if:has_variants,1|nullable|integer|min:0',
            'price_cold' => 'required_if:has_variants,1|nullable|integer|min:0',
            'image' => 'nullable|file|mimes:jpg,jpeg,png,webp,gif|max:5120',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'is_new' => 'boolean',
            'is_promo' => 'boolean',
            'promo_price' => 'nullable|integer|min:0',
            'is_seasonal' => 'boolean',
            'seasonal_label' => 'nullable|string|max:50',
        ]);

        $data = $request->only(['name', 'category_id', 'description', 'price', 'seasonal_label']);
        $data['has_variants'] = $request->boolean('has_variants');
        $data['is_active'] = $request->boolean('is_active', true);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_new'] = $request->boolean('is_new');
        $data['is_promo'] = $request->boolean('is_promo');
        $data['is_seasonal'] = $request->boolean('is_seasonal');
        $data['promo_price'] = $request->is_promo ? $request->promo_price : null;

        if ($data['has_variants']) {
            $data['price_hot'] = (int) $request->price_hot;
            $data['price_cold'] = (int) $request->price_cold;
            $data['price'] = min($data['price_hot'], $data['price_cold']);
        } else {
            $data['price'] = (int) $request->price;
            $data['price_hot'] = null;
            $data['price_cold'] = null;
        }

        if ($request->hasFile('image')) {
            $this->deleteProductImage($product->image);
            $data['image'] = $this->storeProductImage($request->file('image'));
        }

        if ($request->boolean('remove_image') && $product->image) {
            $this->deleteProductImage($product->image);
            $data['image'] = null;
        }

        $product->update($data);
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $this->deleteProductImage($product->image);
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus.');
    }

    private function storeProductImage(UploadedFile $file): string
    {
        $targetDirectory = public_path('images/products');

        if (!is_dir($targetDirectory)) {
            mkdir($targetDirectory, 0755, true);
        }

        $filename = uniqid('prod_', true) . '.' . strtolower($file->getClientOriginalExtension());
        $file->move($targetDirectory, $filename);

        return 'products/' . $filename;
    }

    private function deleteProductImage(?string $path): void
    {
        if (!$path) {
            return;
        }

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }

        $publicImagePath = public_path('images/' . $path);
        if (file_exists($publicImagePath)) {
            @unlink($publicImagePath);
        }
    }
}
