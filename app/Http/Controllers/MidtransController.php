<?php

namespace App\Http\Controllers;

use App\Models\Snap;
use App\Models\Boking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MidtransController extends Controller
{
    public function checkout($orderId)
    {
        // ambil order dari DB
        // return view('checkout', compact('order'));
        return view('checkout', ['orderId' => $orderId]);
    }

    /**
     * Debug endpoint - check if Midtrans config is loaded
     */
    public function debugConfig()
    {
        return response()->json([
            'server_key_set' => !empty(config('midtrans.server_key')),
            'server_key' => config('midtrans.server_key'),
            'client_key' => config('midtrans.client_key'),
            'is_production' => config('midtrans.is_production'),
            'environment' => app()->environment(),
            'app_debug' => config('app.debug'),
        ]);
    }

    public function token(Request $request, $orderId = null)
    {
        try {
            // Jika orderId tidak diberikan di URL, ambil dari request body
            $orderId = $orderId ?? $request->input('order_id');
            $grossAmount = $request->input('gross_amount');
            $bookingData = $request->input('booking_data');

            // Defensive: ensure booking data is an array to avoid warnings if missing
            $bookingData = is_array($bookingData) ? $bookingData : [];

            if (!$orderId || !$grossAmount) {
                return response()->json([
                    'error' => 'order_id dan gross_amount diperlukan'
                ], 400);
            }

            // Siapkan parameter untuk Midtrans
            $params = [
                'transaction_details' => [
                    'order_id' => (string) $orderId,
                    'gross_amount' => intval($grossAmount),
                ],
                'customer_details' => [
                    'first_name' => $bookingData['nama'] ?? 'Customer',
                    'email'      => Auth::check() ? Auth::user()->email : 'customer@futsal.com',
                    'phone'      => Auth::check() ? (Auth::user()->phone ?? '08000000000') : '08000000000',
                ],
                'item_details' => [
                    [
                        'id' => (string)($bookingData['lapangan_id'] ?? 'item-1'),
                        'price' => intval($grossAmount),
                        'quantity' => 1,
                        'name' => ($bookingData['lapangan'] ?? 'Booking Futsal') . ' - ' . 
                                 ($bookingData['jam_mulai'] ?? '') . ' s/d ' . 
                                 ($bookingData['jam_selesai'] ?? '')
                    ]
                ],
                'credit_card' => [
                    'secure' => true,
                ],
            ];

            // Generate Snap Token
            $snapToken = Snap::getSnapToken($params);

            if (!$snapToken) {
                return response()->json([
                    'error' => 'Gagal membuat token pembayaran dari Midtrans'
                ], 500);
            }

            // Simpan data temporary untuk verifikasi nanti
            session(['payment_data' => [
                'order_id' => $orderId,
                'booking_data' => $bookingData,
                'gross_amount' => $grossAmount,
                'timestamp' => now()
            ]]);

            return response()->json([
                'token' => $snapToken,
                'client_key' => config('midtrans.client_key'),
            ]);
        } catch (\Exception $e) {
            Log::error('Midtrans Token Error: ' . $e->getMessage(), [
                'order_id' => $orderId ?? null,
                'gross_amount' => $grossAmount ?? null,
            ]);

            return response()->json([
                'error' => 'Gagal membuat token pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }

    public function notification(Request $request)
    {
        $payload = $request->all();

        // Wajib verifikasi signature_key:
        // SHA512(order_id + status_code + gross_amount + ServerKey)
        // Cocokkan hasilnya dengan $payload['signature_key']
        $expected = hash(
            'sha512',
            ($payload['order_id'] ?? '') .
            ($payload['status_code'] ?? '') .
            ($payload['gross_amount'] ?? '') .
            config('midtrans.server_key')
        );

        if (($payload['signature_key'] ?? '') !== $expected) {
            return response('invalid signature', 401);
        }

        $orderId = $payload['order_id'];
        $transactionStatus = $payload['transaction_status'];

        // Status mapping Midtrans ke Boking status:
        // settlement/capture = paid (pembayaran berhasil)
        // pending = pending (menunggu)
        // deny/cancel/expire = canceled (dibatalkan)
        $bokingStatus = 'pending';
        
        if (in_array($transactionStatus, ['settlement', 'capture'])) {
            $bokingStatus = 'paid';
        } elseif ($transactionStatus === 'pending') {
            $bokingStatus = 'pending';
        } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire'])) {
            $bokingStatus = 'canceled';
        }

        // Cari booking dari database berdasarkan order_id (lebih reliable untuk webhook)
        $booking = Boking::where('order_id', $orderId)->first();
        
        if ($booking) {
            // Update booking status
            $booking->update(['status' => $bokingStatus]);

            // Simpan payment record untuk tracking dan auditing
            $paymentAt = null;
            if (in_array($transactionStatus, ['settlement', 'capture'])) {
                $paymentAt = now();
            }

            // Create or update payment record
            $paymentData = [
                'booking_id' => $booking->id,
                'order_id' => $orderId,
                'transaction_id' => $payload['transaction_id'] ?? null,
                'payment_status' => $transactionStatus,
                'amount' => $payload['gross_amount'] ?? $booking->total_harga,
                'payment_method' => $payload['payment_type'] ?? null,
                'payment_reference' => $payload['reference_id'] ?? null,
                'midtrans_response' => $payload,
                'signature_key' => $payload['signature_key'] ?? null,
                'payment_at' => $paymentAt,
            ];

            Payment::updateOrCreate(
                ['order_id' => $orderId],
                $paymentData
            );

            Log::info('Booking Status Updated via Webhook', [
                'booking_id' => $booking->id,
                'order_id' => $orderId,
                'transaction_status' => $transactionStatus,
                'booking_status' => $bokingStatus,
            ]);
        } else {
            Log::warning('Booking not found for order_id', [
                'order_id' => $orderId,
                'transaction_status' => $transactionStatus,
            ]);
        }

        // Midtrans minta endpoint balas HTTP 200 agar dianggap sukses menerima notifikasi
        return response('OK', 200);
    }

    /**
     * Manual webhook trigger untuk testing (development only)
     * Test dengan: GET /midtrans/test-webhook?order_id=BOOKING-...
     */
    public function testWebhook(Request $request)
    {
        if (app()->environment('production')) {
            return response('Forbidden in production', 403);
        }

        $orderId = $request->input('order_id');
        if (!$orderId) {
            return response()->json([
                'error' => 'order_id parameter diperlukan',
                'example' => '/midtrans/test-webhook?order_id=BOOKING-1-1705708800-12345'
            ], 400);
        }

        // Create manual webhook payload
        $mockPayload = [
            'order_id' => $orderId,
            'status_code' => '200',
            'transaction_status' => 'settlement', // Simulate successful payment
            'transaction_id' => 'mid-' . uniqid(),
            'gross_amount' => 50000,
            'payment_type' => 'credit_card',
            'signature_key' => hash(
                'sha512',
                $orderId . '200' . 50000 . config('midtrans.server_key')
            ),
        ];

        // Call notification handler directly
        $mockRequest = new Request($mockPayload);
        $response = $this->notification($mockRequest);

        return response()->json([
            'message' => 'Webhook test executed',
            'order_id' => $orderId,
            'status_code' => $response->getStatusCode(),
            'instruction' => 'Check database to verify booking status changed to "paid"'
        ]);
    }

    /**
     * Show manual payment confirmation page (for testing without ngrok)
     * GET /midtrans/manual-confirm?order_id=BOOKING-...
     */
    public function manualConfirm(Request $request)
    {
        if (app()->environment('production')) {
            abort(403, 'Not available in production');
        }

        $orderId = $request->input('order_id');
        if (!$orderId) {
            return response()->json(['error' => 'order_id required'], 400);
        }

        $booking = Boking::where('order_id', $orderId)->first();
        if (!$booking) {
            return response()->json(['error' => 'Booking not found'], 404);
        }

        return view('payment.manual-confirm', compact('booking', 'orderId'));
    }
}
