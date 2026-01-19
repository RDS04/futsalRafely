# Midtrans Webhook Configuration Guide

## Webhook URL yang Harus Dikonfigurasi di Midtrans Dashboard

Webhook endpoint untuk menerima notifikasi pembayaran dari Midtrans:

```
POST http://localhost:8001/midtrans/notification
```

Atau untuk production:
```
POST https://yourdomain.com/midtrans/notification
```

## Cara Setup di Midtrans Dashboard

1. **Login ke Midtrans Dashboard** (sandbox.midtrans.com)
2. Pergi ke **Settings → Notification URL**
3. Masukkan **Notification URL** untuk payment status updates:
   - **Notification URL**: `https://yourdomain.com/midtrans/notification`
4. Pilih metode: **HTTP POST**
5. Simpan (Save)

## Testing Webhook Locally

Untuk testing local tanpa SSL, gunakan tunneling tool seperti:
- ngrok: `ngrok http 8001`
- LocalTunnel: `npx localtunnel --port 8001`

Kemudian:
1. Copy URL yang digenerate (misal: `https://xxxxx.ngrok.io`)
2. Configure di Midtrans Dashboard: `https://xxxxx.ngrok.io/midtrans/notification`
3. Test pembayaran di Midtrans Sandbox

## Webhook Response

Endpoint harus mengembalikan HTTP 200 agar Midtrans tahu webhook diterima:
```php
return response('OK', 200);
```

## Status Mapping

Webhook menerima `transaction_status` dari Midtrans dan mengubah status booking di database:

| Midtrans Status | Booking Status | Meaning |
|---|---|---|
| settlement, capture | paid | Pembayaran berhasil |
| pending | pending | Menunggu pembayaran |
| deny, cancel, expire | canceled | Pembayaran ditolak/dibatalkan |

## Debugging Webhook

Cek Laravel logs untuk webhook events:
```bash
tail -f storage/logs/laravel.log | grep "Booking Status Updated"
```

## Current Implementation

File: `app/Http/Controllers/MidtransController.php`
- Method: `notification(Request $request)`
- Mencari booking berdasarkan `order_id` dari Midtrans
- Update status booking ke database
- Log semua aktivitas webhook

## Verificati

Midtrans mengirim `signature_key` yang dihitung dari:
```
SHA512(order_id + status_code + gross_amount + server_key)
```

Server memverifikasi signature sebelum update database untuk keamanan.
