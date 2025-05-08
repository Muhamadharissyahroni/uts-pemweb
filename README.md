# Dokumentasi API

**Nama:** Muhammad Haris Syahroni  
**Kelas:** IF22E  
**NIM:** 22416255201193

---

## 🔐 Autentikasi

Semua endpoint API (kecuali login & register) membutuhkan token otorisasi.

Sertakan header berikut di setiap request:
Authorization | Bearer <token>


---

## 📌 Daftar Endpoint API

### 🧑 Auth (Login & Register)

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| POST | [`/api/login`](http://127.0.0.1:8000/api/login) | Login user |
| POST | [`/api/logout`](http://127.0.0.1:8000/api/logout) | Logout user |
| POST | [`/api/register`](http://127.0.0.1:8000/api/register) | Register user |

---

### 👥 Users

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | [`/api/users`](http://127.0.0.1:8000/api/users) | Ambil semua user |
| DELETE | [`/api/users/{id}`](http://127.0.0.1:8000/api/users/id) | Hapus user berdasarkan ID |

---

### 🗂️ Categories

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | [`/api/categories`](http://127.0.0.1:8000/api/categories) | Ambil semua kategori |
| POST | [`/api/categories`](http://127.0.0.1:8000/api/categories) | Tambah kategori |
| PUT | [`/api/categories/{id}`](http://127.0.0.1:8000/api/categories/id) | Edit kategori berdasarkan ID |
| DELETE | [`/api/categories/{id}`](http://127.0.0.1:8000/api/categories/id) | Hapus kategori berdasarkan ID |

---

### 📦 Products

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | [`/api/products`](http://127.0.0.1:8000/api/products) | Ambil semua produk |
| POST | [`/api/products`](http://127.0.0.1:8000/api/products) | Tambah produk |
| PUT | [`/api/products/{id}`](http://127.0.0.1:8000/api/products/id) | Edit produk berdasarkan ID |
| DELETE | [`/api/products/{id}`](http://127.0.0.1:8000/api/products/id) | Hapus produk berdasarkan ID |

---

> 📌 *Catatan:* Ganti `{id}` pada endpoint dengan ID sebenarnya dari data yang ingin diubah atau dihapus.
