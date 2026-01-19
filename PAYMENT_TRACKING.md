# Payment Tracking & Auditing System

## Overview
Sistem untuk tracking dan auditing semua transaksi pembayaran dari Midtrans dengan detail lengkap.

## Database Schema

### Table: `payments`

| Column | Type | Description |
|--------|------|-------------|
| id | bigint (PK) | Primary key |
| booking_id | bigint (FK) | Reference ke booking yang dibayar |
| order_id | varchar (unique) | Order ID dari Midtrans |
| transaction_id | varchar (unique) | Transaction ID dari Midtrans |
| payment_status | varchar | Status pembayaran (pending, settlement, capture, deny, cancel, expire, etc) |
| amount | decimal(15,2) | Jumlah pembayaran |
| payment_method | varchar | Metode pembayaran (credit_card, bank_transfer, e-wallet, dll) |
| payment_reference | varchar | Reference number dari payment gateway |
| midtrans_response | json | Full response payload dari Midtrans API |
| signature_key | varchar | Signature key untuk verifikasi |
| payment_at | timestamp | Waktu pembayaran berhasil |
| created_at | timestamp | Waktu record dibuat |
| updated_at | timestamp | Waktu record terakhir diupdate |

### Indexes
- `order_id` (unique) - untuk fast lookup by order
- `transaction_id` (unique) - untuk fast lookup by transaction
- `booking_id` - untuk mencari payments by booking
- `payment_status` - untuk filtering berdasarkan status

### Foreign Keys
- `booking_id` → `bokings.id` (cascade delete)

## Model: Payment

```php
namespace App\Models;

class Payment extends Model
{
    protected $fillable = [
        'booking_id',
        'order_id',
        'transaction_id',
        'payment_status',
        'amount',
        'payment_method',
        'payment_reference',
        'midtrans_response',
        'signature_key',
        'payment_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'midtrans_response' => 'array', // Auto-cast JSON to array
        'payment_at' => 'datetime',
    ];

    public function booking()
    {
        return $this->belongsTo(Boking::class);
    }
}
```

## Relasi Models

### Boking Model
```php
public function payment(): HasOne
{
    return $this->hasOne(Payment::class);
}
```

Usage:
```php
$booking = Boking::find(1);
$payment = $booking->payment; // Get payment record
```

## Webhook Integration

Ketika Midtrans mengirim webhook notification:

1. **Verifikasi signature** untuk memastikan webhook valid
2. **Update booking status** (pending → paid / canceled)
3. **Create/Update payment record** dengan detail dari Midtrans:
   ```php
   Payment::updateOrCreate(
       ['order_id' => $orderId],
       [
           'booking_id' => $booking->id,
           'transaction_id' => $payload['transaction_id'],
           'payment_status' => $transactionStatus,
           'amount' => $payload['gross_amount'],
           'payment_method' => $payload['payment_type'],
           'payment_reference' => $payload['reference_id'],
           'midtrans_response' => $payload, // Store full response
           'payment_at' => ($transactionStatus === 'settlement') ? now() : null,
       ]
   );
   ```

## Status Mapping

| Midtrans Status | Payment Status | Booking Status |
|---|---|---|
| settlement | settlement | paid |
| capture | capture | paid |
| pending | pending | pending |
| deny | deny | canceled |
| cancel | cancel | canceled |
| expire | expire | canceled |

## Querying Payments

### Get payment by order ID
```php
$payment = Payment::where('order_id', 'BOOKING-1-1705708800-12345')->first();
```

### Get payments by booking
```php
$booking = Boking::find(1);
$payment = $booking->payment;
```

### Get pending payments
```php
$pendingPayments = Payment::where('payment_status', 'pending')->get();
```

### Get successful payments
```php
$paidPayments = Payment::whereIn('payment_status', ['settlement', 'capture'])->get();
```

### Get failed payments
```php
$failedPayments = Payment::whereIn('payment_status', ['deny', 'cancel', 'expire'])->get();
```

## Auditing & Compliance

Semua payment details tersimpan di database termasuk:
- Full Midtrans response (JSON)
- Signature key untuk verifikasi
- Payment method yang digunakan
- Timestamp pembayaran
- Reference dari payment gateway

Berguna untuk:
- Audit trail
- Dispute resolution
- Financial reporting
- Debugging transaksi

## Benefits

1. **Persistent Record** - Semua payment info tersimpan permanen
2. **Full Traceability** - Dapat track setiap payment dari awal
3. **Flexibility** - Query payment data untuk reporting dan analytics
4. **Decoupling** - Payment logic terpisah dari booking logic
5. **Scalability** - Mudah untuk analisis dan reporting di masa depan

## Future Enhancements

- Refund tracking (create refund records saat ada refund)
- Payment history/timeline UI
- Financial dashboard dengan payment analytics
- Automated reconciliation reports
- Payment gateway comparison (jika ada multiple gateways)
