<?php

namespace App\Http\Controllers;

use App\Models\Lapangan;
use App\Models\Event;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class InputLapanganController extends Controller
{
    /**
     * Get admin yang sedang login
     */
    protected function getAdmin()
    {
        return Auth::guard('admin')->user();
    }

    /**
     * Get region admin yang sedang login
     */
    protected function getAdminRegion()
    {
        return $this->getAdmin()->region;
    }

    /**
     * Get folder admin berdasarkan region
     */
    private function getAdminFolder()
    {
        $region = $this->getAdminRegion();
        $folderMap = [
            'padang' => 'admSatu',
            'sijunjung' => 'admDua',
            'bukittinggi' => 'admTiga',
        ];
        return $folderMap[$region] ?? 'admSatu';
    }

    public function inputLapangan()
    {
        $admin = $this->getAdmin();
        $lapangan = Lapangan::byRegion($admin->region)->get();
        $adminFolder = $this->getAdminFolder();
        return view("dashboardAdm.{$adminFolder}.inputLapangan", compact('lapangan'));
    }

    public function daftarLapangan()
    {
        $admin = $this->getAdmin();
        $lapangan = Lapangan::byRegion($admin->region)->get();
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
            $path = $file->store('lapangan_images', 'public');
            $validated['gambar'] = $path;
        }

        // Tambah region dan admin_id otomatis
        $admin = $this->getAdmin();
        $validated['region'] = $admin->region;
        $validated['admin_id'] = $admin->id;

        Lapangan::create($validated);
        return redirect()
            ->route('inputLapangan.Lapangan')
            ->with('success', 'Lapangan berhasil ditambahkan.');
    }

    public function viewLapangan()
    {
        $admin = $this->getAdmin();
        $lapangan = Lapangan::byRegion($admin->region)->get();
        $adminFolder = $this->getAdminFolder();
        return view("dashboardAdm.{$adminFolder}.view", compact('lapangan'));
    }

    public function editLapangan($id)
    {
        $admin = $this->getAdmin();
        // Cek apakah lapangan milik admin yang login
        $lapangan = Lapangan::byRegion($admin->region)->findOrFail($id);
        $adminFolder = $this->getAdminFolder();
        return view("dashboardAdm.{$adminFolder}.edit", compact('lapangan'));
    }
    public function destroy($id)
    {
        $admin = $this->getAdmin();
        // Cek apakah lapangan milik admin yang login
        $lapangan = Lapangan::byRegion($admin->region)->findOrFail($id);
        
        // Hapus gambar jika ada
        if ($lapangan->gambar) {
            Storage::disk('public')->delete($lapangan->gambar);
        }
        
        $lapangan->delete();

        return redirect()
            ->route('lapangan.view')
            ->with('success', 'Lapangan berhasil dihapus.');
    }
    public function update(Request $request, $id)
    {
        $admin = $this->getAdmin();
        // Cek apakah lapangan milik admin yang login
        $lapangan = Lapangan::byRegion($admin->region)->findOrFail($id);
        
        $validated = $request->validate([
            'namaLapangan' => 'required|string',
            'jenisLapangan' => 'required|string',
            'harga' => 'required|numeric',
            'status' => 'required|string',
            'deskripsi' => 'required|string',
        ]);
        $lapangan->update($validated);
        return redirect()
            ->route('lapangan.view')
            ->with('success', 'Lapangan berhasil diperbarui.');
    }

    public function slider()
    {
        $admin = $this->getAdmin();
        $slider = Slider::byRegion($admin->region)->get();
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
            $path = $file->store('lapangan_images', 'public');
            $validated['gambar'] = $path;
        }
        
        // Tambah region dan admin_id otomatis
        $admin = $this->getAdmin();
        $validated['region'] = $admin->region;
        $validated['admin_id'] = $admin->id;
        
        Slider::create($validated);
        return redirect()
            ->route('lapangan.slider')
            ->with('success', 'Slider berhasil ditambahkan.');
    }

    public function editSlider($id)
    {
        $admin = $this->getAdmin();
        $slider = Slider::byRegion($admin->region)->findOrFail($id);
        $adminFolder = $this->getAdminFolder();
        return view("dashboardAdm.{$adminFolder}.editSlider", compact('slider'));
    }

    public function updateSlider(Request $request, $id)
    {
        $admin = $this->getAdmin();
        $slider = Slider::byRegion($admin->region)->findOrFail($id);
        
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
        $admin = $this->getAdmin();
        $slider = Slider::byRegion($admin->region)->findOrFail($id);
        
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
        $admin = $this->getAdmin();
        $events = Event::byRegion($admin->region)->get();
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
            $path = $file->store('event', 'public');
            $validated['gambar'] = $path;
        }
        
        // Tambah region dan admin_id otomatis
        $admin = $this->getAdmin();
        $validated['region'] = $admin->region;
        $validated['admin_id'] = $admin->id;
        $validated['status'] = 'active';
        
        Event::create($validated);
        return redirect()
            ->route('lapangan.event')
            ->with('success', 'Event berhasil ditambahkan.');
    }

    public function editEvent($id)
    {
        $admin = $this->getAdmin();
        $event = Event::byRegion($admin->region)->findOrFail($id);
        $adminFolder = $this->getAdminFolder();
        return view("dashboardAdm.{$adminFolder}.editEvent", compact('event'));
    }

    public function updateEvent(Request $request, $id)
    {
        $admin = $this->getAdmin();
        $event = Event::byRegion($admin->region)->findOrFail($id);
        
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
        $admin = $this->getAdmin();
        $event = Event::byRegion($admin->region)->findOrFail($id);
        
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
