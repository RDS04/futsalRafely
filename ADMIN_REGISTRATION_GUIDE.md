# 📝 Panduan Membuat Akun Admin

## Perubahan Terbaru
- ✅ Admin dapat dibuat **TANPA perlu izin Master Admin**
- ✅ Admin dapat dibuat **TANPA perlu login** terlebih dahulu
- ✅ Password **AKAN di-hash** sebelum disimpan ke database

---

## 🚀 Cara Membuat Akun Admin

### **Langkah 1: Buka Halaman Register Admin**
```
http://localhost:8000/admin/register
```

### **Langkah 2: Isi Form dengan Data Admin**

| Field | Deskripsi |
|-------|-----------|
| **Nama Admin** | Nama lengkap admin (minimal 3 karakter) |
| **Email Admin** | Email unik untuk login |
| **Password** | Password minimal 8 karakter |
| **Konfirmasi Password** | Harus sama dengan password di atas |
| **Region** | Region yang akan dikelola (padang, sijunjung, bukittinggi) |

### **Langkah 3: Pilih Role Admin**

- **Master Admin** 👑
  - Akses ke semua region
  - Bisa mengelola lapangan, slider, event di semua region
  - Default region: Padang
  
- **Regional Admin** 📍
  - Akses hanya ke satu region yang dipilih
  - Bisa mengelola lapangan, slider, event di region mereka saja

### **Langkah 4: Klik "Daftar Admin"**

---

## ✅ Validasi Form

### **Validasi Nama**
- ❌ Wajib diisi
- ❌ Minimal 3 karakter
- ❌ Maksimal 255 karakter
- ❌ Tidak boleh sama dengan admin lain

### **Validasi Email**
- ❌ Wajib diisi
- ❌ Harus format email yang valid (contoh: admin@futsal.com)
- ❌ Tidak boleh sama dengan email admin lain

### **Validasi Password**
- ❌ Wajib diisi
- ❌ Minimal 8 karakter
- ❌ Harus sama dengan konfirmasi password

### **Validasi Region**
- ❌ Wajib dipilih jika role adalah Regional Admin
- ❌ Hanya boleh: padang, sijunjung, bukittinggi

---

## 📋 Contoh Data Admin

### **Master Admin**
```
Nama: Master Admin Futsal
Email: admin@futsal.com
Password: Master@123456
Konfirmasi: Master@123456
Role: Master Admin
Region: Padang (default)
```

### **Regional Admin - Padang**
```
Nama: Admin Padang
Email: admin.padang@futsal.com
Password: Padang@123456
Konfirmasi: Padang@123456
Role: Regional Admin
Region: Padang
```

### **Regional Admin - Sijunjung**
```
Nama: Admin Sijunjung
Email: admin.sijunjung@futsal.com
Password: Sijunjung@123456
Konfirmasi: Sijunjung@123456
Role: Regional Admin
Region: Sijunjung
```

### **Regional Admin - Bukit Tinggi**
```
Nama: Admin Bukittinggi
Email: admin.bukittinggi@futsal.com
Password: Bukittinggi@123456
Konfirmasi: Bukittinggi@123456
Role: Regional Admin
Region: Bukittinggi
```

---

## 🔐 Keamanan Password

### **Penting: Password Di-Hash**
- Password **TIDAK** disimpan dalam plain text
- Password di-hash menggunakan `Hash::make()` sebelum disimpan
- Hash menggunakan algoritmo Bcrypt (aman)

### **Contoh:**
```php
// Input password: "Master@123456"
// Password di-hash menjadi: 
// $2y$10$abcdefghijklmnopqrstuvwxyz...

// Saat login, password input diverifikasi dengan Hash::check()
```

---

## 📱 Login Admin

Setelah berhasil membuat akun admin:

### **Langkah 1: Buka Halaman Login Admin**
```
http://localhost:8000/admin/login
```

### **Langkah 2: Isi Credential**
- **Nama atau Email**: Sesuai data saat registrasi
- **Password**: Password saat registrasi

### **Langkah 3: Klik "Login"**

---

## 🗂️ Role & Permissions

### **Master Admin** 👑
```
├── Dashboard (lihat semua region)
├── Input Lapangan
│   ├── Padang
│   ├── Sijunjung
│   └── Bukittinggi
├── Input Slider
│   ├── Padang
│   ├── Sijunjung
│   └── Bukittinggi
├── Input Event
│   ├── Padang
│   ├── Sijunjung
│   └── Bukittinggi
└── Manajemen Admin (jika ada)
```

### **Regional Admin** 📍
```
├── Dashboard (hanya region mereka)
├── Input Lapangan (hanya region mereka)
├── Input Slider (hanya region mereka)
└── Input Event (hanya region mereka)
```

---

## 🐛 Troubleshooting

### **Masalah: Email sudah terdaftar**
**Solusi**: Gunakan email yang belum terdaftar. Setiap admin harus memiliki email unik.

### **Masalah: Nama sudah terdaftar**
**Solusi**: Gunakan nama admin yang belum terdaftar di sistem.

### **Masalah: Region wajib dipilih untuk regional admin**
**Solusi**: Jika memilih role "Regional Admin", pastikan region juga dipilih.

### **Masalah: Password tidak cocok**
**Solusi**: Pastikan password dan konfirmasi password sama persis (case-sensitive).

### **Masalah: Email tidak valid**
**Solusi**: Gunakan format email yang benar (contoh: admin@futsal.com).

---

## 💾 Database Schema

### Admin Table
```sql
CREATE TABLE admins (
    id BIGINT UNSIGNED PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL (hashed),
    region VARCHAR(50) NOT NULL,
    role ENUM('master', 'regional'),
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

---

## 📚 Related Files

- **Controller**: `/app/Http/Controllers/AuthController.php`
- **View**: `/resources/views/auth/RegisterAdmin.blade.php`
- **Model**: `/app/Models/Admin.php`
- **Route**: `/routes/web.php` (route `admin.register.show` dan `admin.register.store`)

---

Generated: January 5, 2026
