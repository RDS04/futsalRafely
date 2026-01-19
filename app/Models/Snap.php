<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Midtrans\Snap as MidtransSnap;
use Midtrans\Config;
use Illuminate\Support\Facades\Log;

class Snap extends Model
{
    /**
     * Generate Snap token dari Midtrans
     *
     * @param array $params
     * @return string
     * @throws \Exception
     */
    public static function getSnapToken(array $params): string
    {
        try {
            // Debug: Cek credentials
            $serverKey = config('midtrans.server_key');
            $clientKey = config('midtrans.client_key');
            
            if (!$serverKey || !$clientKey) {
                Log::error('Midtrans Config Missing', [
                    'server_key_set' => !empty($serverKey),
                    'client_key_set' => !empty($clientKey),
                    'env_server_key' => env('MIDTRANS_SERVER_KEY'),
                    'env_client_key' => env('MIDTRANS_CLIENT_KEY'),
                ]);
                throw new \Exception('Midtrans Server Key atau Client Key tidak dikonfigurasi. Periksa .env file.');
            }

            // Configure Midtrans
            Config::$serverKey = $serverKey;
            Config::$clientKey = $clientKey;
            Config::$isProduction = config('midtrans.is_production', false);
            Config::$isSanitized = config('midtrans.is_sanitized', true);
            Config::$is3ds = config('midtrans.is_3ds', true);

            Log::info('Midtrans Config Set', [
                'is_production' => Config::$isProduction,
                'is_sanitized' => Config::$isSanitized,
                'is_3ds' => Config::$is3ds,
            ]);

            // Get Snap Token from Midtrans
            $snapToken = MidtransSnap::getSnapToken($params);

            if (!$snapToken) {
                throw new \Exception('Midtrans API tidak mengembalikan token');
            }

            Log::info('Snap Token Generated Successfully', [
                'order_id' => $params['transaction_details']['order_id'] ?? 'unknown',
                'token_length' => strlen($snapToken),
            ]);

            return $snapToken;
        } catch (\Exception $e) {
            Log::error('Midtrans Error: ' . $e->getMessage(), [
                'params' => $params,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw new \Exception('Gagal membuat token pembayaran: ' . $e->getMessage());
        }
    }
}
