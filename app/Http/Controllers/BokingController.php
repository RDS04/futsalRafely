<?php

namespace App\Http\Controllers;

use App\Models\Boking;
use Illuminate\Http\Request;

class BokingController extends Controller
{
    public function bookingForm()
    {
        return view('boking.boking');
    }
    public function store(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required|string|max:10',
            'jam_selesai' => 'required|string|max:10',
            'lapangan' => 'required|string|max:100',
            'catatan' => 'nullable|string',
        ]);

        // Simpan data booking ke database
        Boking::create($validatedData);
        // Redirect atau berikan respon sesuai kebutuhan
        return redirect()->route('show.payment')->with('success', 'Booking berhasil disimpan!');
    }

    public function payment()
    {
        return view('payment.index');
    }
}
