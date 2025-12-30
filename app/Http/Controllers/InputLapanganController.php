<?php

namespace App\Http\Controllers;

use App\Models\Lapangan;
use App\Models\Event;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InputLapanganController extends Controller
{
    /**
     * Menentukan folder admin berdasarkan user admin yang login
     */
    private function getAdminFolder()
    {
        // Jika ingin membedakan per admin, bisa menggunakan auth()->guard('admin')->user()->region
        // Untuk sekarang, gunakan admSatu sebagai default atau bisa dari session
        $adminFolder = session('admin_folder', 'admSatu');
        return $adminFolder;
    }

    public function inputLapangan()
    {
        $lapangan = Lapangan::all();
        $adminFolder = $this->getAdminFolder();
        return view("dashboardAdm.{$adminFolder}.inputLapangan", compact('lapangan'));
    }

    public function daftarLapangan()
    {
        $lapangan = Lapangan::all();
        $adminFolder = $this->getAdminFolder();
        return view("dashboardAdm.{$adminFolder}.daftarLapangan", compact('lapangan'));
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
            // contoh: lapangan_images/Lapangan/xxxxx.jpg
            $path = $file->store('lapangan_images', 'public');
            $validated['gambar'] = $path;
        }

        Lapangan::create($validated);
        return redirect()
            ->route('inputLapangan.Lapangan')
            ->with('success', 'Lapangan berhasil ditambahkan.');
    }

    public function viewLapangan()
    {
        $lapangan = Lapangan::all();
        $adminFolder = $this->getAdminFolder();
        return view("dashboardAdm.{$adminFolder}.view", compact('lapangan'));
    }

    public function editLapangan($id)
    {
        $lapangan = Lapangan::findOrFail($id);
        $adminFolder = $this->getAdminFolder();
        return view("dashboardAdm.{$adminFolder}.edit", compact('lapangan'));
    }
    public function destroy($id)
    {
        $lapangan = Lapangan::findOrFail($id);
        $lapangan->delete();

        return redirect()
            ->route('lapangan.view')
            ->with('success', 'Lapangan berhasil dihapus.');
    }
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'namaLapangan' => 'required|string',
            'jenisLapangan' => 'required|string',
            'harga' => 'required|numeric',
            'status' => 'required|string',
            'deskripsi' => 'required|string',
        ]);
        Lapangan::whereId($id)->update($validated);
        return redirect()
            ->route('lapangan.view')
            ->with('success', 'Lapangan berhasil diperbarui.');
    }

    public function slider()
    {
        $slider = Slider::all();
        $adminFolder = $this->getAdminFolder();
        return view("dashboardAdm.{$adminFolder}.slider", compact('slider'));
    }

    public function storeSlider(Request $request)
    {
        $validated = $request->validate([
            'gambar' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            // contoh: lapangan_images/Lapangan/xxxxx.jpg
            $path = $file->store('lapangan_images', 'public');
            $validated['gambar'] = $path;
        }
        Slider::create($validated);
        return redirect()
            ->route('lapangan.slider')
            ->with('success', 'Slider berhasil ditambahkan.');
    }

    public function editSlider($id)
    {
        $slider = Slider::findOrFail($id);
        $adminFolder = $this->getAdminFolder();
        return view("dashboardAdm.{$adminFolder}.editSlider", compact('slider'));
    }

    public function updateSlider(Request $request, $id)
    {
        $slider = Slider::findOrFail($id);
        
        $validated = $request->validate([
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama
            if ($slider->gambar) {
                Storage::disk('public')->delete($slider->gambar);
            }
            
            $file = $request->file('gambar');
            $path = $file->store('lapangan_images', 'public');
            $validated['gambar'] = $path;
        }

        $slider->update($validated);
        return redirect()
            ->route('lapangan.slider')
            ->with('success', 'Slider berhasil diperbarui.');
    }

    public function destroySlider($id)
    {
        $slider = Slider::findOrFail($id);
        
        // Hapus gambar dari storage
        if ($slider->gambar) {
            Storage::disk('public')->delete($slider->gambar);
        }
        
        $slider->delete();
        return redirect()
            ->route('lapangan.slider')
            ->with('success', 'Slider berhasil dihapus.');
    }

    public function event()
    {
        $events = Event::all();
        $adminFolder = $this->getAdminFolder();
        return view("dashboardAdm.{$adminFolder}.event", compact('events'));
    }
    public function storeEvent(Request $request)
    {
        
        $validated = $request->validate([
            'judul' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date',
            'deskripsi' => 'required|string',
            'gambar' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            // contoh: lapangan_images/Lapangan/xxxxx.jpg
            $path = $file->store('event', 'public');
            $validated['gambar'] = $path;
        }
        Event::create($validated);
        return redirect()
            ->route('lapangan.event')
            ->with('success', 'Event berhasil ditambahkan.');
    }

    public function editEvent($id)
    {
        $event = Event::findOrFail($id);
        $adminFolder = $this->getAdminFolder();
        return view("dashboardAdm.{$adminFolder}.editEvent", compact('event'));
    }

    public function updateEvent(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        
        $validated = $request->validate([
            'judul' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date',
            'deskripsi' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama
            if ($event->gambar) {
                Storage::disk('public')->delete($event->gambar);
            }
            
            $file = $request->file('gambar');
            $path = $file->store('event', 'public');
            $validated['gambar'] = $path;
        }

        $event->update($validated);
        return redirect()
            ->route('lapangan.event')
            ->with('success', 'Event berhasil diperbarui.');
    }

    public function destroyEvent($id)
    {
        $event = Event::findOrFail($id);
        
        // Hapus gambar dari storage
        if ($event->gambar) {
            Storage::disk('public')->delete($event->gambar);
        }
        
        $event->delete();
        return redirect()
            ->route('lapangan.event')
            ->with('success', 'Event berhasil dihapus.');
    }
}
