# Troubleshooting Guide - Futsal Booking System

## 🔴 Error: "Ada yang eror" (Vague Error Message)

Untuk mendiagnosis error, ikuti langkah berikut:

### 1. **Cek Browser Console (F12)**
- Buka halaman yang error
- Tekan `F12` → buka tab "Console"
- Cari pesan error (warna merah)
- Copy-paste error message lengkap

### 2. **Cek Server Logs**
```bash
# Terminal di folder project
tail -100 storage/logs/laravel.log
```

### 3. **Cek Network Tab (F12)**
- Buka tab "Network" di DevTools
- Coba aksi yang error
- Cari request yang gagal (status 4xx atau 5xx)
- Klik request → lihat "Response" untuk error detail

---

## Common Errors & Solutions

### ❌ "Data pemesanan tidak valid. Silakan lakukan pemesanan ulang."
**Penyebab:** Session `order_id` tidak tersimpan
**Solusi:**
1. Pastikan `BokingController::store()` menyimpan `order_id` ke session
2. Check apakah session sudah ter-clear sebelum redirect ke payment

### ❌ "Gagal membuat token pembayaran"
**Penyebab:** Midtrans API error
**Solusi:**
1. Buka browser console → lihat error detail
2. Pastikan Midtrans credentials di `.env` benar
3. Jalankan: `php artisan config:clear`

### ❌ "Total harga tidak valid"
**Penyebab:** `total_harga` kosong atau 0
**Solusi:**
1. Pastikan di booking form, `durasi` dan `harga_per_jam` ter-set
2. Check: `total_harga = durasi × harga_per_jam`

### ❌ "HTTP error! status: 422"
**Penyebab:** Validation error di API
**Solusi:**
1. Cek Network tab → Response untuk detail error
2. Pastikan semua field required sudah ada

### ❌ "Unknown column 'harga_per_jam' in 'field list'"
**Penyebab:** Database belum punya kolom tersebut
**Solusi:**
```bash
php artisan migrate
```

---

## Quick Debug Mode

Tambahkan ini di atas script di `payment/index.blade.php`:

```javascript
// Uncomment untuk debug mode
console.log('=== DEBUG MODE ===');
console.log('Order ID:', orderId);
console.log('Booking Data:', bookingDataRaw);
console.log('Total Harga:', parseInt('{{ intval($bookingData["total_harga"] ?? 0) }}'));
```

---

## Testing Checklist

- [ ] Session `order_id` ter-generate dengan format `BOOKING-*`
- [ ] Database booking ter-save dengan status `pending`
- [ ] Payment page bisa akses & tampilkan info booking
- [ ] Tombol "Lanjut ke Pembayaran" tidak error
- [ ] Midtrans Snap pop-up bisa terbuka
- [ ] Pembayaran sukses → redirect ke `/payment/success`

---

Jika masih ada error, bagikan:
1. Error message lengkap dari console
2. Network tab response
3. Server logs (tail storage/logs/laravel.log)
