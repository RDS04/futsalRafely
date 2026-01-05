# 📊 Database Schema Documentation

## Master Admin Setup

### 1. Database Tables

#### **admins Table**
```sql
CREATE TABLE admins (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL (hashed dengan Hash::make()),
    region VARCHAR(50) NOT NULL (default: 'padang'),
    role ENUM('master', 'regional') DEFAULT 'regional',
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

-- Indexes
CREATE INDEX idx_role ON admins(role);
CREATE INDEX idx_region ON admins(region);
CREATE INDEX idx_is_active ON admins(is_active);
CREATE UNIQUE INDEX idx_email ON admins(email);
```

#### **sliders Table**
```sql
CREATE TABLE sliders (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    gambar VARCHAR(255) NULLABLE,
    region VARCHAR(50) NULLABLE,
    admin_id BIGINT UNSIGNED NULLABLE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (admin_id) REFERENCES admins(id) ON DELETE SET NULL
);
```

#### **lapangans Table**
```sql
CREATE TABLE lapangans (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    namaLapangan VARCHAR(255) NOT NULL,
    jenisLapangan VARCHAR(100),
    harga DECIMAL(10, 2),
    deskripsi TEXT,
    gambar VARCHAR(255),
    status ENUM('aktif', 'tidak_aktif') DEFAULT 'aktif',
    region VARCHAR(50) NOT NULL,
    admin_id BIGINT UNSIGNED,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (admin_id) REFERENCES admins(id) ON DELETE CASCADE
);
```

#### **events Table**
```sql
CREATE TABLE events (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    judul VARCHAR(255) NOT NULL,
    deskripsi TEXT,
    gambar VARCHAR(255),
    tanggal_mulai DATE NOT NULL,
    tanggal_selesai DATE,
    status ENUM('akan_datang', 'berlangsung', 'selesai') DEFAULT 'akan_datang',
    region VARCHAR(50) NOT NULL,
    admin_id BIGINT UNSIGNED,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (admin_id) REFERENCES admins(id) ON DELETE CASCADE
);
```

---

## 🚀 Master Admin Features

### 1. **Role & Permissions**
- **Master Admin**: Akses penuh ke semua region (Padang, Sijunjung, Bukit Tinggi)
- **Regional Admin**: Akses terbatas hanya ke region mereka

### 2. **Key Fields**

| Field | Type | Description |
|-------|------|-------------|
| `name` | VARCHAR(255) | Nama Master Admin |
| `email` | VARCHAR(255) | Email unik untuk login |
| `password` | VARCHAR(255) | Password hashed dengan Hash::make() |
| `region` | VARCHAR(50) | Default 'padang' (master bisa akses semua) |
| `role` | ENUM | 'master' atau 'regional' |
| `is_active` | BOOLEAN | Status admin aktif/tidak aktif |

### 3. **Creating Master Admin**

#### **Method 1: Seeder (Database)**
```bash
php artisan db:seed --class=AdminSeeder
```

#### **Method 2: Artisan Command**
```bash
php artisan admin:create-master
```

#### **Method 3: Web Form**
1. Buka: `http://localhost:8000/admin/register-master`
2. Isi form dengan:
   - Nama Master Admin
   - Email (harus unik)
   - Password (min 8 karakter)
   - Konfirmasi Password
3. Klik "Buat Master Admin"

#### **Method 4: Manual Database Insert**
```php
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

Admin::create([
    'name' => 'Master Admin',
    'email' => 'admin@master.com',
    'password' => Hash::make('password123'), // Harus di-hash!
    'region' => 'padang',
    'role' => 'master',
    'is_active' => true,
]);
```

---

## 🔐 Master Admin Login

### Login Details
- **URL**: `http://localhost:8000/admin/login`
- **Credential**: Email atau Nama Admin
- **Password**: Password yang didaftarkan

### After Login
- Master Admin akan diredirect ke `/admin/dashboard`
- Dapat melihat semua region dalam satu dashboard
- Dapat mengelola lapangan, slider, dan event di semua region

---

## 📋 Controller Methods

### **AuthController**

#### `showRegisterMaster()`
- **Route**: `GET /admin/register-master`
- **Purpose**: Menampilkan form register Master Admin
- **View**: `auth.registerMaster`

#### `storeMasterAdmin(Request $request)`
- **Route**: `POST /admin/register-master`
- **Purpose**: Menyimpan data Master Admin baru
- **Validation**:
  - `name`: required, unique, min 3, max 255
  - `email`: required, unique, valid email
  - `password`: required, min 8, confirmed
- **Returns**: Redirect ke `/admin/dashboard` dengan auto-login

---

## 🔄 Migrations

### Migration Files
```
database/migrations/
├── 2025_12_08_220432_create_admins_table.php
│   └── Membuat tabel admins dasar
├── 2025_01_04_000000_add_role_and_email_to_admins_table.php
│   └── Menambah kolom role, email, is_active
├── 2025_12_19_065233_create_sliders_table.php
│   └── Membuat tabel sliders dengan region & admin_id
├── 2025_12_16_073044_create_lapangans_table.php
│   └── Membuat tabel lapangans
└── 2025_12_19_065713_create_events_table.php
    └── Membuat tabel events
```

### Running Migrations
```bash
# Run semua migrations
php artisan migrate

# Run refresh (drop dan recreate)
php artisan migrate:refresh

# Run dengan seeding
php artisan migrate:seed
```

---

## 🗃️ Model Relationships

### **Admin Model**
```php
// One-to-Many: Admin -> Lapangan
public function lapangans(): HasMany
public function events(): HasMany
public function sliders(): HasMany
```

### **Lapangan Model**
```php
// Belong-to: Lapangan -> Admin
public function admin(): BelongsTo
```

### **Event Model**
```php
// Belong-to: Event -> Admin
public function admin(): BelongsTo
```

### **Slider Model**
```php
// Belong-to: Slider -> Admin
public function admin(): BelongsTo
```

---

## 🛡️ Security Notes

### Password Security
- ✅ Passwords HARUS di-hash menggunakan `Hash::make()`
- ✅ Gunakan `Hash::check()` untuk verifikasi password
- ❌ JANGAN simpan password plain text di database

### Example:
```php
// ✅ Correct
$admin = Admin::create([
    'password' => Hash::make('my-password')
]);

// ❌ Wrong
$admin = Admin::create([
    'password' => 'my-password' // Plain text!
]);
```

### Validating Login
```php
// ✅ Correct
if (Hash::check($inputPassword, $admin->password)) {
    // Password matches
}

// ❌ Wrong
if ($inputPassword === $admin->password) {
    // This will always be false!
}
```

---

## 📱 API Endpoints

### Admin Dashboard
- `GET /admin/dashboard` - Master dashboard (all regions)
- `GET /admin/dashboard/{region}` - Regional dashboard

### Lapangan Management
- `GET /admin/input-lapangan/inputLapangan` - Add lapangan form
- `POST /admin/input-lapangan/inputLapangan` - Store lapangan
- `GET /admin/input-lapangan/daftarLapangan` - List lapangan
- `GET /admin/input-lapangan/editLapangan/{id}` - Edit form
- `PUT /admin/input-lapangan/updateLapangan/{id}` - Update lapangan
- `DELETE /admin/input-lapangan/deleteLapangan/{id}` - Delete lapangan

### Slider Management
- `GET /admin/input-lapangan/slider` - Slider management
- `POST /admin/input-lapangan/slider` - Store slider
- `PUT /admin/input-lapangan/slider/{id}` - Update slider
- `DELETE /admin/input-lapangan/slider/{id}` - Delete slider

### Event Management
- `GET /admin/input-lapangan/event` - Event management
- `POST /admin/input-lapangan/event` - Store event
- `PUT /admin/input-lapangan/event/{id}` - Update event
- `DELETE /admin/input-lapangan/event/{id}` - Delete event

---

## 🧪 Testing Master Admin Creation

### Test via Artisan Command
```bash
php artisan admin:create-master
# Follow interactive prompts
```

### Test via Web Form
```bash
# 1. Start server
php artisan serve

# 2. Open browser
http://localhost:8000/admin/register-master

# 3. Fill form and submit
```

### Test via Database
```bash
php artisan tinker

# Create master admin
>>> use App\Models\Admin;
>>> use Illuminate\Support\Facades\Hash;
>>> Admin::create([
>>>     'name' => 'Test Master',
>>>     'email' => 'test@master.com',
>>>     'password' => Hash::make('test1234'),
>>>     'region' => 'padang',
>>>     'role' => 'master',
>>>     'is_active' => true
>>> ]);

# Verify
>>> Admin::where('role', 'master')->get();
```

---

## 🐛 Troubleshooting

### Issue: Column 'region' not found in sliders table
**Solution**: Run migration to add region column
```bash
php artisan migrate
```

### Issue: Unknown column 'role' or 'email'
**Solution**: Run the add_role_and_email migration
```bash
php artisan migrate --path=database/migrations/2025_01_04_000000_add_role_and_email_to_admins_table.php
```

### Issue: Password mismatch on login
**Solution**: Ensure password is hashed during creation
```php
// Use Hash::make() when creating
$admin->password = Hash::make('newpassword');
$admin->save();
```

### Issue: Master Admin can't access all regions
**Solution**: Check role column value
```bash
php artisan tinker
>>> Admin::find(1)->role; // Should be 'master'
```

---

## 📚 Related Files

- **Controller**: `/app/Http/Controllers/AuthController.php`
- **Model**: `/app/Models/Admin.php`
- **View**: `/resources/views/auth/registerMaster.blade.php`
- **Routes**: `/routes/web.php`
- **Migration**: `/database/migrations/2025_01_04_000000_add_role_and_email_to_admins_table.php`

---

Generated: January 5, 2026
