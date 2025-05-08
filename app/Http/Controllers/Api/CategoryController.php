<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user('sanctum');

        // Pastikan pengguna sudah terautentikasi
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Periksa peran pengguna
        if ($user->role !== 'admin') {
            return response()->json(['message' => 'Forbidden - You are not admin'], 403);
        }

        // Ambil data kategori jika pengguna adalah admin
        $categories = Category::all();
        return response()->json(['categories' => $categories], 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = $request->user('sanctum');

        // Pastikan pengguna sudah terautentikasi
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Periksa peran pengguna
        if ($user->role !== 'admin') {
            return response()->json(['message' => 'Forbidden - You are not admin'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = Category::create([
            'name' => $request->name,
        ]);

        return response()->json(['message' => 'Category added successfully', 'category' => $category], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = $request->user('sanctum');

        // Pastikan pengguna sudah terautentikasi
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Periksa peran pengguna
        if ($user->role !== 'admin') {
            return response()->json(['message' => 'Forbidden - You are not admin'], 403);
        }

        try {
            $category = Category::findOrFail($id);

            // Validasi inputan
            $request->validate([
                'name' => 'required|string|max:255',
            ]);

            // Update kategori
            $category->update([
                'name' => $request->name,
            ]);

            return response()->json(['message' => 'Category updated successfully', 'category' => $category], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Category not found'], 404);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id, Request $request)
    {
        $user = $request->user('sanctum');

        // Pastikan pengguna sudah terautentikasi
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Periksa peran pengguna
        if ($user->role !== 'admin') {
            return response()->json(['message' => 'Forbidden - You are not admin'], 403);
        }

        try {
            // Mencari kategori berdasarkan ID
            $category = Category::findOrFail($id);

            // Menghapus kategori
            $category->delete();

            return response()->json(['message' => 'Category deleted successfully'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Category not found'], 404);
        }
    }
}
