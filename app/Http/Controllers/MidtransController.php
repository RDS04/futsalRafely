<?php

namespace App\Http\Controllers;

use App\Models\Snap;
use App\Models\Boking;
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

        // Cari booking dari session payment_data
        $paymentData = session('payment_data');
        
        if ($paymentData && isset($paymentData['booking_data'])) {
            $bookingData = $paymentData['booking_data'];
            $bookingId = session('booking_id');
            
            if ($bookingId) {
                // Update existing booking
                $booking = Boking::find($bookingId);
                if ($booking) {
                    $booking->update(['status' => $bokingStatus]);

                    Log::info('Booking Status Updated', [
                        'booking_id' => $bookingId,
                        'order_id' => $orderId,
                        'transaction_status' => $transactionStatus,
                        'booking_status' => $bokingStatus,
                    ]);
                }
            }
        }

        // Clear session data
        session()->forget('booking_data');
        session()->forget('payment_data');
        session()->forget('booking_id');

        // Midtrans minta endpoint balas HTTP 200 agar dianggap sukses menerima notifikasi
        return response('OK', 200);
    }
}
