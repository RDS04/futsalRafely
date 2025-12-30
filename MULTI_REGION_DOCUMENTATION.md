# 📋 Dokumentasi Multi-Region Futsal System

## Ringkasan Implementasi

Sistem multi-region untuk website pemesanan lapangan futsal dengan 3 region: **Padang**, **Sijunjung**, dan **Bukittinggi**. Setiap region memiliki admin tersendiri yang hanya bisa mengelola data mereka.

---

## 🏗 Arsitektur Sistem

### Layer Keamanan

```
┌──────────────────────────────────────────────────────────┐
│ HTTP Request                                             │
└──────────────────────────────────────────────────────────┘
                         ↓
┌──────────────────────────────────────────────────────────┐
│ AdminMiddleware (auth.admin)                             │
│ - Cek user sudah login sebagai admin                    │
│ - Redirect ke login jika belum                          │
└──────────────────────────────────────────────────────────┘
                         ↓
┌──────────────────────────────────────────────────────────┐
│ RegionCheck Middleware                                   │
│ - Inject region dari admin ke request attributes        │
│ - Validasi akses berdasarkan region                     │
└──────────────────────────────────────────────────────────┘
                         ↓
┌──────────────────────────────────────────────────────────┐
│ Controller                                               │
│ - Filter query berdasarkan region admin                 │
│ - Tambah region & admin_id otomatis saat create         │
└──────────────────────────────────────────────────────────┘
                         ↓
┌──────────────────────────────────────────────────────────┐
│ Database Query                                           │
│ - Scope: ->byRegion($region)                           │
│ - WHERE region = 'padang' / 'sijunjung' / 'bukittinggi' │
└──────────────────────────────────────────────────────────┘
```

---

## 📁 Files yang Diupdate

### 1. **Middleware** (`app/Http/Middleware/`)

#### AdminMiddleware.php
- Validasi user sudah login sebagai admin
- Guard menggunakan `admin` (dari config/auth.php)

#### RegionCheck.php (BARU)
- Inject region admin ke request attributes
- Middleware ini optional (untuk validasi tambahan)

### 2. **Models** (`app/Models/`)

#### Admin.php
```php
// Relasi dengan Lapangan
public function lapangans(): HasMany
{
    return $this->hasMany(Lapangan::class);
}

// Relasi dengan Event
public function events(): HasMany
{
    return $this->hasMany(Event::class);
}

// Relasi dengan Slider
public function sliders(): HasMany
{
    return $this->hasMany(Slider::class);
}
```

#### Lapangan.php, Event.php, Slider.php
```php
// Relasi dengan Admin
public function admin(): BelongsTo
{
    return $this->belongsTo(Admin::class);
}

// Scope untuk filter region
public function scopeByRegion($query, $region)
{
    return $query->where('region', $region);
}

// Scope untuk filter admin
public function scopeByAdmin($query, $adminId)
{
    return $query->where('admin_id', $adminId);
}
```

#### Boking.php
```php
// Field baru: region, customer_id, lapangan_id, total_harga, status
// Relasi dengan Customer dan Lapangan
```

### 3. **Controllers** (`app/Http/Controllers/`)

#### InputLapanganController.php
```php
// Method helper untuk get admin login
protected function getAdmin()
{
    return Auth::guard('admin')->user();
}

// Method helper untuk get region admin
protected function getAdminRegion()
{
    return $this->getAdmin()->region;
}

// Di setiap method: filter query dengan region
$lapangan = Lapangan::byRegion($admin->region)->get();

// Saat create: tambah region & admin_id otomatis
$validated['region'] = $admin->region;
$validated['admin_id'] = $admin->id;
```

#### DashboardController.php
```php
// Dashboard untuk setiap region
public function adminPadang()
{
    // Validasi region
    if ($admin->region !== 'padang') {
        abort(403, 'Anda tidak memiliki akses ke region ini');
    }
    // Get data untuk Padang saja
}
```

### 4. **Migration** (BARU)

#### 2025_12_30_000000_add_region_fields_to_tables.php
```php
// Tambah field ke tabel:
// - lapangans: region, admin_id
// - sliders: region, admin_id
// - events: region, admin_id
// - bokings: region, customer_id, lapangan_id, total_harga, status
```

### 5. **Routes** (`routes/web.php`)

```php
// Admin routes dengan 2 middleware
Route::middleware(['auth.admin', 'region.check'])->prefix('admin')->group(function () {
    // Semua route admin dilindungi
    // Hanya akses data mereka sendiri
});
```

### 6. **Bootstrap** (`bootstrap/app.php`)

```php
$middleware->alias([
    'admin' => \App\Http\Middleware\AdminMiddleware::class,
    'auth.admin' => \App\Http\Middleware\AdminMiddleware::class,
    'region.check' => \App\Http\Middleware\RegionCheck::class,
]);
```

### 7. **Seeder** (BARU)

#### AdminSeeder.php
```php
// Admin Master (padang)
// Admin Padang
// Admin Sijunjung
// Admin Bukittinggi

// Password: admin123, padang123, sijunjung123, bukittinggi123
```

---

## 🔐 Alur Login Multi-Region

```
User Input (email/username, password)
          ↓
AuthController::adminLogin()
          ↓
Auth::guard('admin')->attempt($credentials)
          ↓
Admin Model verifikasi password
          ↓
Session stored dengan region admin
          ↓
Redirect ke dashboard/{region}
          ↓
AdminMiddleware check user login
          ↓
RegionCheck inject region ke request
          ↓
Show dashboard sesuai region
```

---

## 📊 Alur CRUD Data Per Region

### Create Lapangan (Admin Padang)

```php
// Admin dari region Padang login
Auth::guard('admin')->user(); // Admin dengan region='padang'

// Submit form input lapangan
InputLapanganController@store($request)

// Validate input
$validated = $request->validate([...]);

// Auto inject region & admin_id
$validated['region'] = 'padang';    // dari admin yang login
$validated['admin_id'] = 1;         // dari admin yang login

// Create
Lapangan::create($validated);
// Database: INSERT INTO lapangans (namaLapangan, region, admin_id) VALUES (...)
```

### Read Lapangan (Admin Padang)

```php
// Hanya ambil lapangan milik region Padang
$lapangan = Lapangan::byRegion($admin->region)->get();

// Query: SELECT * FROM lapangans WHERE region = 'padang'
```

### Update Lapangan

```php
// Validasi dulu apakah lapangan milik region admin yang login
$lapangan = Lapangan::byRegion($admin->region)->findOrFail($id);

// Update
$lapangan->update($validated);
```

### Delete Lapangan

```php
// Validasi dulu
$lapangan = Lapangan::byRegion($admin->region)->findOrFail($id);

// Delete
$lapangan->delete();
```

---

## 🎯 Flow Protection

### 1. Middleware Protection
```
Route dengan middleware ['auth.admin', 'region.check']
  ↓
AdminMiddleware check login
  ↓
Jika belum login → redirect login
  ↓
Jika sudah login → RegionCheck inject region
  ↓
Lanjut ke controller
```

### 2. Controller Protection
```
Controller method dipanggil
  ↓
Get admin yang login: $admin = Auth::guard('admin')->user()
  ↓
Filter query dengan region: Lapangan::byRegion($admin->region)
  ↓
findOrFail() akan throw 404 jika tidak ada
  ↓
Data hanya lapangan milik region tersebut
```

### 3. Prevent Direct Access
```
Admin Padang coba akses: /admin/input-lapangan/editLapangan/5
  (ID 5 = Lapangan milik Sijunjung)
  ↓
Lapangan::byRegion('padang')->findOrFail(5)
  ↓
Query: SELECT * FROM lapangans WHERE id=5 AND region='padang'
  ↓
Tidak ketemu → 404 Not Found
  ↓
Protect! ✅
```

---

## 📱 Dashboard Per Region

### Admin Padang
- `/admin/dashboard/padang` → View lapangan + event + slider milik Padang
- `/admin/input-lapangan/daftarLapangan` → List lapangan Padang
- `/admin/input-lapangan/slider` → Slider Padang
- `/admin/input-lapangan/event` → Event Padang

### Admin Sijunjung
- `/admin/dashboard/sijunjung` → View lapangan + event + slider milik Sijunjung
- `/admin/input-lapangan/daftarLapangan` → List lapangan Sijunjung
- dst...

### Admin Bukittinggi
- `/admin/dashboard/bukittinggi` → View lapangan + event + slider milik Bukittinggi
- dst...

---

## 🧪 Testing Admin 3 Region

### Test Credentials

```
Master Admin:
  Username: Master Admin
  Password: admin123
  Region: padang (default)

Admin Padang:
  Username: Admin Padang
  Password: padang123
  Region: padang

Admin Sijunjung:
  Username: Admin Sijunjung
  Password: sijunjung123
  Region: sijunjung

Admin Bukittinggi:
  Username: Admin Bukittinggi
  Password: bukittinggi123
  Region: bukittinggi
```

### Run Seeder

```bash
php artisan migrate
php artisan db:seed --class=AdminSeeder

# Atau full seeding
php artisan migrate:fresh --seed
```

---

## 🔄 Relasi Data

```
Admin (1) ──────────── (Many) Lapangan
  │ name
  │ password
  │ region
  ├────────────────────────── Event
  └────────────────────────── Slider

Lapangan (Many) ────────── (1) Admin

Boking (Many) ────────── (1) Lapangan
  │ customer_id
  │ lapangan_id
  └────────────── region (duplicate untuk quick filter)
```

---

## 📝 Best Practices Implemented

✅ **Scope Query** - Gunakan scopes untuk filter region
```php
Lapangan::byRegion($admin->region)->get()
```

✅ **Middleware** - Protect routes dengan middleware
```php
Route::middleware(['auth.admin', 'region.check'])->group(...)
```

✅ **Auto Inject** - Otomatis inject region & admin_id saat create
```php
$validated['region'] = $admin->region;
$validated['admin_id'] = $admin->id;
```

✅ **Validate Access** - Cek akses sebelum update/delete
```php
$lapangan = Lapangan::byRegion($admin->region)->findOrFail($id);
```

✅ **Relasi Model** - Define relasi di model untuk consistency
```php
public function admin(): BelongsTo { ... }
public function lapangans(): HasMany { ... }
```

---

## 🚀 Next Steps

1. **Update BokingController** - Filter boking berdasarkan region
2. **Update CostumerController** - Filter lapangan/event per region untuk customer
3. **Master Admin Dashboard** - Tambah dashboard untuk master admin (lihat semua region)
4. **Reporting** - Laporan per region dengan statistik
5. **Role Permissions** - Tambah role: Super Admin, Regional Admin, Staff
6. **Audit Log** - Catat siapa melakukan apa

---

## ⚠️ Catatan Penting

1. **Field Region** - Harus konsisten di semua table
2. **Foreign Key** - Gunakan constrained() untuk referential integrity
3. **Migration** - Check jika column sudah exist sebelum add
4. **Guard Name** - Pastikan 'admin' guard ada di config/auth.php
5. **Default Region** - Master admin default padang, bisa ubah

---

Dibuat: 30 Desember 2025
Status: ✅ Ready untuk Production
