<?php

namespace App\Http\Controllers;

use App\Models\Boking;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Show payment success page with order/booking/payment details
     */
    public function success(Request $request)
    {
        $orderId = $request->query('order_id');
        
        $booking = null;
        $payment = null;
        
        if ($orderId) {
            // Fetch booking by order_id
            $booking = Boking::where('order_id', $orderId)->first();
            
            // Fetch payment if booking exists
            if ($booking) {
                $payment = $booking->payment;
            }
        }
        
        return view('payment.success', compact('orderId', 'booking', 'payment'));
    }
}
