<?php

namespace App\Http\Controllers\API;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user('sanctum');

        // Pastikan pengguna sudah terautentikasi
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Ambil data produk dengan relasi ke user
        $products = Product::with('user:id,email', 'category')->get();

        // Format respons untuk menampilkan email pada modified_by
        $formattedProducts = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
                'image' => $product->image,
                'category_id' => $product->category->name,
                'expired_date' => $product->expired_date,
                'modified_by' => $product->user ? $product->user->email : null,
                'created_at' => $product->created_at,
                'updated_at' => $product->updated_at,
            ];
        });

        return response()->json(['products' => $formattedProducts], 200);
    }


    public function store(Request $request)
    {
        $user = $request->user('sanctum');

        // Pastikan pengguna sudah terautentikasi
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Validasi inputan
        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'image' => 'required|string',
            'category_id' => 'required|integer',
        ]);

        try {
            // Validasi category_id: pastikan kategori dengan ID tersebut ada di database
            $category = Category::findOrFail($request->category_id);

            // Membuat produk baru
            $product = Product::create([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'image' => $request->image,
                'category_id' => $request->category_id,
            ]);

            return response()->json(['message' => 'Product added successfully', 'product' => $product], 200);
        } catch (ModelNotFoundException $e) {
            // Jika kategori not found, kembalikan error 404
            return response()->json(['message' => 'Category not found'], 404);
        }
    }


    public function update(Request $request, $id)
    {
        $user = $request->user('sanctum');

        // Pastikan pengguna sudah terautentikasi
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Validasi inputan
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|integer|min:0',
            'image' => 'required|url',
            'category_id' => 'required|integer',
            'expired_date' => 'required|date',
        ]);

        try {
            // Cari produk berdasarkan ID atau lempar pengecualian jika not found
            $product = Product::findOrFail($id);

            // Cari kategori berdasarkan category_id, lempar pengecualian jika not found
            $category = Category::findOrFail($request->category_id);

            // Update data produk
            $product->update([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'image' => $request->image,
                'category_id' => $request->category_id,
                'expired_date' => $request->expired_date,
                'modified_by' => $user->id,
            ]);

            return response()->json([
                'message' => 'Product updated successfully',
                'product' => $product
            ], 200);
        } catch (ModelNotFoundException $e) {
            // Jika produk atau kategori not found, kembalikan error 404
            return response()->json(['message' => 'Product or Category not found'], 404);
        }
    }


    public function destroy(Request $request, $id)
    {
        $user = $request->user('sanctum');

        // Pastikan pengguna sudah terautentikasi
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        try {
            // Cari produk berdasarkan ID atau lempar pengecualian jika not found
            $product = Product::findOrFail($id);

            // Hapus produk
            $product->delete();

            return response()->json(['message' => 'Product deleted successfully'], 200);
        } catch (ModelNotFoundException $e) {
            // Jika produk not found, kembalikan error 404
            return response()->json(['message' => 'Product not found'], 404);
        }
    }
}
