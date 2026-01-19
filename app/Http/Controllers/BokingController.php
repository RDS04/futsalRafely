<?php

namespace App\Http\Controllers;

use App\Models\Boking;
use App\Models\Lapangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BokingController extends Controller
{
    public function bookingForm()
    {
        // Get customer's region
        $region = Auth::check() ? Auth::user()->region : null;
        
        // Get available lapangan for customer's region
        $lapangan = Lapangan::where('region', $region)
            ->where('status', 'tersedia')
            ->select('id', 'namaLapangan', 'harga', 'deskripsi')
            ->get();

        return view('boking.boking', compact('lapangan', 'region'));
    }

    public function store(Request $request)
    {
        
        // Validasi input
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i',
            'region' => 'required|string|max:100',
            'lapangan_id' => 'required|integer|exists:lapangans,id',
            'lapangan' => 'required|string|max:100',
            'catatan' => 'nullable|string',
        ]);

        // Validasi jam selesai > jam mulai
        if ($validatedData['jam_selesai'] <= $validatedData['jam_mulai']) {
            return redirect()->back()
                ->withErrors(['jam_selesai' => 'Jam selesai harus lebih besar dari jam mulai'])
                ->withInput();
        }

        // Check availability - apakah lapangan sudah terboking pada jam tersebut
        // Exclude canceled bookings (hanya cek pending dan paid)
        $existingBooking = Boking::where('lapangan_id', $validatedData['lapangan_id'])
            ->where('tanggal', $validatedData['tanggal'])
            ->whereIn('status', ['pending', 'paid'])  // Hanya pending dan paid yang block
            ->where(function ($query) use ($validatedData) {
                // Cek overlap dengan booking yang ada
                $query->whereBetween('jam_mulai', [$validatedData['jam_mulai'], $validatedData['jam_selesai']])
                    ->orWhereBetween('jam_selesai', [$validatedData['jam_mulai'], $validatedData['jam_selesai']])
                    ->orWhere(function ($q) use ($validatedData) {
                        $q->where('jam_mulai', '<=', $validatedData['jam_mulai'])
                            ->where('jam_selesai', '>=', $validatedData['jam_selesai']);
                    });
            })
            ->first();

        if ($existingBooking) {
            return redirect()->back()
                ->withErrors(['lapangan' => 'Lapangan sudah terboking pada jam tersebut'])
                ->withInput();
        }

        // Get lapangan price
        $lapangan = Lapangan::find($validatedData['lapangan_id']);
        $jamMulai = strtotime($validatedData['jam_mulai']);
        $jamSelesai = strtotime($validatedData['jam_selesai']);
        $durasi = ($jamSelesai - $jamMulai) / 3600; // durasi dalam jam
        $totalHarga = $lapangan->harga * $durasi;

        // Generate unique order ID for Midtrans
        $orderId = 'BOOKING-' . $validatedData['lapangan_id'] . '-' . time() . '-' . random_int(10000, 99999);

        // Simpan data booking ke database dengan status pending
        $validatedData['customer_id'] = Auth::check() ? Auth::id() : null;
        $validatedData['total_harga'] = $totalHarga;
        $validatedData['harga_per_jam'] = $lapangan->harga;  // Tambahkan harga per jam
        $validatedData['durasi'] = $durasi;  // Tambahkan durasi
        $validatedData['order_id'] = $orderId;  // Tambahkan order ID
        $validatedData['status'] = 'pending';  // Status awal: pending

        $booking = Boking::create($validatedData);

        // Simpan juga ke session untuk ditampilkan di payment page
        session(['booking_data' => $validatedData]);
        session(['booking_id' => $booking->id]);  // Simpan ID untuk tracking
        session(['order_id' => $orderId]);  // Simpan order_id untuk tracking

        return redirect()->route('show.payment')->with('success', 'Lanjut ke pembayaran!');
    }

    public function payment()
    {
        // Ambil data booking dari session
        $bookingData = session('booking_data');
        
        if (!$bookingData) {
            return redirect()->route('boking.form')
                ->withErrors(['error' => 'Data booking tidak ditemukan. Silakan lakukan booking ulang.']);
        }

        return view('payment.index', compact('bookingData'));
    }
}
