# 🔧 Solusi Error "Unknown column 'email' in 'where clause'"

## 📋 Ringkasan Masalah & Solusi

### **Masalah yang Terjadi:**
```
SQLSTATE[42S22]: Column not found: 1054 Unknown column 'email' in 'where clause'
Connection: mysql
SQL: select count(*) as aggregate from `admins` where `email` = admin1@gmail.com
```

### **Penyebab:**
1. ❌ Migration `add_role_and_email_to_admins_table` memiliki **timestamp 2025_01_04**
2. ❌ Migration `create_admins_table` memiliki **timestamp 2025_12_08**
3. ❌ Laravel menjalankan migration berdasarkan timestamp, jadi `add_role_and_email` berjalan **SEBELUM** `create_admins_table`
4. ❌ Saat itu tabel `admins` belum ada, sehingga kolom `email`, `role`, `is_active` tidak ditambahkan

### **Solusi yang Diterapkan:**

#### **1. Buat Migration Baru dengan Timestamp Benar**
```
OLD: database/migrations/2025_01_04_000000_add_role_and_email_to_admins_table.php
NEW: database/migrations/2025_12_08_220433_add_email_role_to_admins_table.php
```

Timestamp `2025_12_08_220433` **SETELAH** `2025_12_08_220432_create_admins_table`, sehingga urutan eksekusi benar.

#### **2. Perbaiki Migration Code**
- Hapus penggunaan index drop di method `down()` yang bisa error
- Tetap gunakan check `hasColumn()` untuk safety
- Perbaiki drop unique constraint sebelum drop column

#### **3. Update AdminSeeder**
- Ubah password dari **plain text** menjadi **hashed** menggunakan `Hash::make()`
- Tambah import `Illuminate\Support\Facades\Hash`

#### **4. Reset Database**
```bash
php artisan migrate:fresh --seed
```

---

## ✅ Hasil Akhir

### **Database Schema - Admins Table**
```sql
CREATE TABLE admins (
    id BIGINT UNSIGNED PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL (hashed),
    region ENUM('padang', 'bukittinggi', 'sijunjung') DEFAULT 'padang',
    role ENUM('master', 'regional') DEFAULT 'regional',
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### **Data Default (dari Seeder)**

| Email | Name | Password | Role | Region | 
|-------|------|----------|------|--------|
| admin@master.com | Master Admin | master123 (hashed) | master | padang |
| admin@padang.com | Admin Padang | padang123 (hashed) | regional | padang |
| admin@sijunjung.com | Admin Sijunjung | sijunjung123 (hashed) | regional | sijunjung |
| admin@bukittinggi.com | Admin Bukittinggi | bukittinggi123 (hashed) | regional | bukittinggi |

---

## 🔐 Login Admin

### **Master Admin**
- **Email**: `admin@master.com`
- **Password**: `master123`
- **Akses**: Semua region

### **Regional Admin - Padang**
- **Email**: `admin@padang.com`
- **Password**: `padang123`
- **Akses**: Hanya Padang

### **Regional Admin - Sijunjung**
- **Email**: `admin@sijunjung.com`
- **Password**: `sijunjung123`
- **Akses**: Hanya Sijunjung

### **Regional Admin - Bukittinggi**
- **Email**: `admin@bukittinggi.com`
- **Password**: `bukittinggi123`
- **Akses**: Hanya Bukittinggi

---

## 📝 Cara Test

### **1. Buat Admin Baru via Web Form**
```
http://localhost:8000/admin/register
```

### **2. Verifikasi di Database**
```bash
php artisan tinker
use App\Models\Admin;
Admin::all();
```

### **3. Test Login**
```
http://localhost:8000/admin/login
```

---

## 🚀 Command yang Dijalankan

```bash
# 1. Reset database dan jalankan semua migration + seeder
php artisan migrate:fresh --seed

# 2. Cek status migration
php artisan migrate:status

# 3. Jalankan seeder saja
php artisan db:seed --class=AdminSeeder

# 4. Rollback migration tertentu
php artisan migrate:rollback --step=1
```

---

## 📂 File yang Diubah/Dibuat

### **Created:**
- `/database/migrations/2025_12_08_220433_add_email_role_to_admins_table.php` ✅

### **Modified:**
- `/database/seeders/AdminSeeder.php` ✅
- `/app/Http/Controllers/AuthController.php` ✅ (password sekarang di-hash)

### **Deleted:**
- `/database/migrations/2025_01_04_000000_add_role_and_email_to_admins_table.php` ✅ (urutan salah)

---

## 🔑 Key Takeaways

1. **Migration Naming**: Gunakan timestamp yang tepat agar urutan eksekusi sesuai kebutuhan
2. **Password Security**: SELALU hash password menggunakan `Hash::make()`, jangan simpan plain text
3. **Seeder & Controller**: Harus konsisten dalam hal password (keduanya harus hash)
4. **Check Column**: Gunakan `Schema::hasColumn()` untuk safety saat alter table
5. **migrate:fresh**: Gunakan saat development untuk reset database dengan data baru

---

Generated: January 5, 2026
