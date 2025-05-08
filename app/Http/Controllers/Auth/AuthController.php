<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function index(Request $request)
    {
        $roles = $request->user('sanctum');

        // Pastikan pengguna sudah terautentikasi
        if (!$roles) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Periksa peran pengguna
        if ($roles->role !== 'admin') {
            return response()->json(['message' => 'Forbidden - You are not admin'], 403);
        }

        // Ambil semua data pengguna
        $users = User::all();

        return response()->json([
            'users' => $users
        ], 200);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Durasi expired 1 jam (60 menit)
            $expirationMinutes = 60;
            $expirationSeconds = $expirationMinutes * 60;

            // Buat token dengan expired 1 jam
            $token = $user->createToken('API Token', [], now()->addSeconds($expirationSeconds))->plainTextToken;

            return response()->json([
                'message' => 'Login successfully',
                'user' => $user,
                'token' => $token,
                'expires_in_seconds' => $expirationSeconds
            ], 200);
        }

        return response()->json(['message' => 'Email or password wrong'], 401);
    }

    // Register User (Admin Only)
    public function register(Request $request)
    {
        $roles = $request->user('sanctum');

        // Pastikan pengguna sudah terautentikasi
        if (!$roles) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Periksa peran pengguna
        if ($roles->role !== 'admin') {
            return response()->json(['message' => 'Forbidden - You are not admin'], 403);
        }

        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        try {
            // Coba buat user baru
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'user',
            ]);

            return response()->json([
                'message' => 'User successfully didaftarkan',
                'user' => $user
            ], 200);
        } catch (QueryException $e) {
            // Tangani error duplicate email
            if ($e->errorInfo[1] == 1062) { // 1062 adalah kode error duplicate entry di MySQL
                return response()->json([
                    'message' => 'Email has already been taken. Please use another email.'
                ], 400);
            }

            // Tangani error lain
            return response()->json([
                'message' => 'Terjadi kesalahan saat mendaftarkan user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            if (!$request->user()) {
                return response()->json(['message' => 'Tidak ada pengguna yang terautentikasi'], 401);
            }

            // Hapus semua token user saat ini
            $request->user()->tokens()->delete();

            return response()->json([
                'message' => 'Logout successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Logout gagal',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id, Request $request)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $roles = $request->user('sanctum');

        // Pastikan pengguna sudah terautentikasi
        if (!$roles) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Periksa peran pengguna
        if ($roles->role !== 'admin') {
            return response()->json(['message' => 'Forbidden - You are not admin'], 403);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}
