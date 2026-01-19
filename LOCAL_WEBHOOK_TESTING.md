# Local Payment Webhook Testing Guide

## Problem: Status Masih "pending" Padahal Sudah Bayar di Midtrans

Webhook dari Midtrans adalah **server-to-server communication** yang memerlukan **public/accessible URL**. 
Untuk development (localhost), webhook tidak akan diterima otomatis.

## Solution: Manual Webhook Testing (Development Only)

Ada 2 cara untuk test webhook locally:

---

### **Method 1: Manual Test Webhook Endpoint (RECOMMENDED)**

Paling mudah untuk testing tanpa setup ngrok.

#### Step-by-step:

1. **Buat booking dan lanjut ke payment**
   - Akses: `http://127.0.0.1:8001/boking/bokingForm`
   - Isi form dan submit
   - Tekan "Lanjut ke Pembayaran"

2. **Copy Order ID dari URL atau console**
   - Contoh: `BOOKING-1-1705708800-12345`

3. **Akses manual webhook test endpoint**
   ```
   http://127.0.0.1:8001/midtrans/test-webhook?order_id=BOOKING-1-1705708800-12345
   ```

4. **Verifikasi database**
   ```sql
   SELECT * FROM bokings WHERE order_id = 'BOOKING-1-1705708800-12345';
   SELECT * FROM payments WHERE order_id = 'BOOKING-1-1705708800-12345';
   ```
   - Status booking harus berubah dari `pending` → `paid` ✅
   - Payment record harus ada di payments table ✅

---

### **Method 2: Manual Confirmation UI (Development Only)**

Interactive UI untuk test pembayaran.

#### Step-by-step:

1. **Buat booking** (same as Method 1)

2. **Akses manual confirmation page**
   ```
   http://127.0.0.1:8001/midtrans/manual-confirm?order_id=BOOKING-1-1705708800-12345
   ```

3. **Klik button "✓ Confirm Payment (Trigger Webhook)"**

4. **Success message akan muncul** dengan:
   - Order ID
   - Status code (200 = success)
   - Instruction untuk verify database

5. **Verifikasi database** seperti di atas

---

### **Method 3: ngrok Tunneling (For Real Webhook Testing)**

Jika ingin test webhook yang **benar-benar dikirim dari Midtrans**.

#### Setup:

1. **Install ngrok**
   ```bash
   brew install ngrok
   ```

2. **Start ngrok tunnel ke port 8001**
   ```bash
   ngrok http 8001
   ```

3. **Copy ngrok URL** (misal: `https://xxxxx.ngrok.io`)

4. **Configure di Midtrans Dashboard**
   - Login: https://sandbox.midtrans.com
   - Go to: **Settings → Notification URL**
   - Set: `https://xxxxx.ngrok.io/midtrans/notification`
   - Save

5. **Test payment di Midtrans Snap**
   - Gunakan card sandbox: 4111 1111 1111 1111 (01/25 - 123)
   - Payment akan dikirim langsung dari Midtrans

6. **Verifikasi database**
   - Status harus update otomatis ke `paid` ✅

---

## Database Verification

### Check Booking Status
```sql
SELECT id, nama, lapangan, tanggal, status, order_id FROM bokings 
WHERE order_id = 'BOOKING-1-1705708800-12345';
```

### Check Payment Record
```sql
SELECT id, booking_id, order_id, transaction_id, payment_status, amount, payment_method, payment_at 
FROM payments 
WHERE order_id = 'BOOKING-1-1705708800-12345';
```

### Check Payment Response
```sql
SELECT midtrans_response FROM payments 
WHERE order_id = 'BOOKING-1-1705708800-12345' \G
```

---

## API Endpoints for Testing

### 1. Debug Config
```
GET /midtrans/debug
```
Response: Midtrans config status
```json
{
  "server_key_set": true,
  "client_key": "Mid-client-...",
  "is_production": false
}
```

### 2. Test Webhook (GET Request)
```
GET /midtrans/test-webhook?order_id=BOOKING-1-...
```
Response: Simulates Midtrans webhook
```json
{
  "message": "Webhook test executed",
  "order_id": "BOOKING-1-1705708800-12345",
  "status_code": 200,
  "instruction": "Check database to verify booking status changed to \"paid\""
}
```

### 3. Manual Confirmation UI
```
GET /midtrans/manual-confirm?order_id=BOOKING-1-...
```
Returns: Interactive HTML page untuk test

### 4. Real Webhook (POST from Midtrans)
```
POST /midtrans/notification
```
Body: Midtrans notification payload
Response: HTTP 200 + "OK"

---

## Status Mapping

Saat webhook diterima:

| Midtrans Status | Booking Status | Payment Status | Action |
|---|---|---|---|
| settlement | paid | settlement | Create payment record, set payment_at |
| capture | paid | capture | Create payment record, set payment_at |
| pending | pending | pending | Update payment record |
| deny | canceled | deny | Update payment record |
| cancel | canceled | cancel | Update payment record |
| expire | canceled | expire | Update payment record |

---

## Troubleshooting

### Q: Status masih pending setelah bayar?
**A:** 
- Webhook belum dikirim dari Midtrans (normal untuk localhost)
- Gunakan Method 1 atau 2 untuk manual test

### Q: "Booking not found" error?
**A:**
- Order ID salah atau booking belum ada di database
- Copy order ID langsung dari payment page

### Q: Webhook tidak diterima meskipun menggunakan ngrok?
**A:**
- Check Midtrans Dashboard notification URL is correct
- Ensure ngrok tunnel is still running
- Check Laravel logs: `tail -f storage/logs/laravel.log`

### Q: Payment record tidak ter-create?
**A:**
- Check if webhook handler executed: `grep "Booking Status Updated" storage/logs/laravel.log`
- Verify database migrations ran: `php artisan migrate:status`

---

## Production Deployment

Untuk production:

1. **Deploy aplikasi ke server** dengan HTTPS SSL
2. **Configure Midtrans Notification URL** ke: `https://yourdomain.com/midtrans/notification`
3. **Disable test endpoints** (they only work in development mode)
4. **Monitor webhooks** via Midtrans Dashboard & Laravel logs
5. **Setup error alerts** jika webhook gagal

---

## Important Notes

- ⚠️ Test endpoints HANYA tersedia di development mode
- ⚠️ Production environment akan return HTTP 403 jika diakses
- ✅ Semua payment details tersimpan di database untuk auditing
- ✅ Full Midtrans response disimpan di JSON column untuk debugging
