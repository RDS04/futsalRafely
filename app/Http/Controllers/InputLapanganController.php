<?php

namespace App\Http\Controllers;

use App\Models\Lapangan;
use Illuminate\Http\Request;

class InputLapanganController extends Controller
{
    public function inputLapangan()
    {
        $lapangan = Lapangan::all();
        return view('dashboardAdm.admSatu.inputLapangan', compact('lapangan'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'namaLapangan' => 'required|string',
            'jenisLapangan' => 'required|string',
            'harga' => 'required|numeric',
            'status' => 'required|string',
            'deskripsi' => 'required|string',
            'gambar' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // =====================
        // UPLOAD GAMBAR
        // =====================
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            // contoh: lapangan_images/padang/xxxxx.jpg
            $path = $file->store('lapangan_images', 'public');
            $validated['gambar'] = $path;
        }

        Lapangan::create($validated);
        return redirect()
            ->route('inputLapangan.padang')
            ->with('success', 'Lapangan berhasil ditambahkan.');
    }

    public function edit() {}
    public function view() {}
}
