@extends('layouts.app')

@section('content')
<div class="content-header bg-gradient-to-r from-blue-600 to-blue-800 text-white py-6 shadow-lg">
  <div class="container-fluid px-4">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-3xl font-bold">
          <i class="fas fa-chart-line mr-3"></i>Dashboard Admin - {{ $regionLabel }}
        </h1>
      </div>
      <div class="col-sm-6 text-end">
        <ol class="breadcrumb float-sm-end bg-blue-700 px-3 py-2 rounded-lg">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-blue-100 hover:text-white">Home</a></li>
          <li class="breadcrumb-item text-blue-100">{{ $regionLabel }}</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<div class="content">
  <div class="container-fluid px-4">

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="fas fa-check-circle mr-2"></i>
      <strong>Sukses!</strong> {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    {{-- REGION SELECTOR UNTUK MASTER ADMIN --}}
    @if($admin->id === 1)
    <div class="row mb-4">
      <div class="col-md-12">
        <div class="card shadow-md border-0 rounded-lg overflow-hidden">
          <div class="card-body bg-gradient-to-r from-slate-50 to-white p-6">
            <div class="flex items-center justify-between mb-4">
              <label class="text-lg font-semibold text-slate-700 flex items-center">
                <i class="fas fa-map-location mr-3 text-blue-600"></i>Pilih Region
              </label>
              <span class="text-sm text-slate-500">Anda adalah Master Admin</span>
            </div>
            <div class="flex flex-wrap gap-3">
              <a href="{{ route('admin.dashboard') }}" class="btn btn-primary shadow-md hover:shadow-lg transition-all duration-300 {{ !isset($region) ? 'ring-2 ring-blue-400' : '' }}">
                <i class="fas fa-home mr-2"></i> Master Dashboard
              </a>
              <a href="{{ route('admin.dashboard.region', 'padang') }}" class="btn btn-outline-info hover:bg-info hover:text-white transition-all duration-300 {{ isset($region) && $region === 'padang' ? 'bg-info text-white ring-2 ring-info ring-opacity-50' : '' }}">
                <i class="fas fa-map-marker-alt mr-2"></i> Padang
              </a>
              <a href="{{ route('admin.dashboard.region', 'sijunjung') }}" class="btn btn-outline-success hover:bg-success hover:text-white transition-all duration-300 {{ isset($region) && $region === 'sijunjung' ? 'bg-success text-white ring-2 ring-success ring-opacity-50' : '' }}">
                <i class="fas fa-map-marker-alt mr-2"></i> Sijunjung
              </a>
              <a href="{{ route('admin.dashboard.region', 'bukittinggi') }}" class="btn btn-outline-warning hover:bg-warning hover:text-white transition-all duration-300 {{ isset($region) && $region === 'bukittinggi' ? 'bg-warning text-white ring-2 ring-warning ring-opacity-50' : '' }}">
                <i class="fas fa-map-marker-alt mr-2"></i> Bukit Tinggi
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
    @endif

    {{-- STATISTIK CARDS --}}
    <div class="row mb-4">
      {{-- Total Lapangan --}}
      <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
        <div class="card shadow-md border-0 rounded-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:scale-105">
          <div class="card-body p-0">
            <div class="bg-gradient-to-br from-blue-500 to-blue-700 text-white p-6">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-blue-100 text-sm font-semibold mb-2">Total Lapangan</p>
                  <h3 class="text-4xl font-bold">{{ $totalLapangan ?? 0 }}</h3>
                  <p class="text-blue-100 text-xs mt-2">
                    <i class="fas fa-check-circle"></i> {{ $lapanganAktif ?? 0 }} Aktif
                  </p>
                </div>
                <div class="text-6xl opacity-20">
                  <i class="fas fa-futbol"></i>
                </div>
              </div>
            </div>
            <a href="{{ route('lapangan.daftar.Lapangan') }}" class="block p-3 bg-slate-50 hover:bg-blue-50 text-center text-blue-600 font-semibold text-sm transition-colors duration-300">
              Lihat Detail <i class="fas fa-arrow-right ml-2"></i>
            </a>
          </div>
        </div>
      </div>

      {{-- Total Booking --}}
      <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
        <div class="card shadow-md border-0 rounded-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:scale-105">
          <div class="card-body p-0">
            <div class="bg-gradient-to-br from-green-500 to-green-700 text-white p-6">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-green-100 text-sm font-semibold mb-2">Total Booking</p>
                  <h3 class="text-4xl font-bold">{{ $totalBoking ?? 0 }}</h3>
                  <p class="text-green-100 text-xs mt-2">
                    <i class="fas fa-check-circle"></i> {{ $bokingCompleted ?? 0 }} Selesai
                  </p>
                </div>
                <div class="text-6xl opacity-20">
                  <i class="fas fa-calendar-check"></i>
                </div>
              </div>
            </div>
            <a href="#" class="block p-3 bg-slate-50 hover:bg-green-50 text-center text-green-600 font-semibold text-sm transition-colors duration-300">
              Lihat Detail <i class="fas fa-arrow-right ml-2"></i>
            </a>
          </div>
        </div>
      </div>

      {{-- Total Events --}}
      <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
        <div class="card shadow-md border-0 rounded-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:scale-105">
          <div class="card-body p-0">
            <div class="bg-gradient-to-br from-yellow-500 to-yellow-700 text-white p-6">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-yellow-100 text-sm font-semibold mb-2">Total Event</p>
                  <h3 class="text-4xl font-bold">{{ $totalEvents ?? 0 }}</h3>
                  <p class="text-yellow-100 text-xs mt-2">
                    <i class="fas fa-calendar"></i> Event Aktif
                  </p>
                </div>
                <div class="text-6xl opacity-20">
                  <i class="fas fa-star"></i>
                </div>
              </div>
            </div>
            <a href="#" class="block p-3 bg-slate-50 hover:bg-yellow-50 text-center text-yellow-600 font-semibold text-sm transition-colors duration-300">
              Lihat Detail <i class="fas fa-arrow-right ml-2"></i>
            </a>
          </div>
        </div>
      </div>

      {{-- Total Sliders --}}
      <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
        <div class="card shadow-md border-0 rounded-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:scale-105">
          <div class="card-body p-0">
            <div class="bg-gradient-to-br from-red-500 to-red-700 text-white p-6">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-red-100 text-sm font-semibold mb-2">Total Slider</p>
                  <h3 class="text-4xl font-bold">{{ $totalSliders ?? 0 }}</h3>
                  <p class="text-red-100 text-xs mt-2">
                    <i class="fas fa-images"></i> Gambar
                  </p>
                </div>
                <div class="text-6xl opacity-20">
                  <i class="fas fa-image"></i>
                </div>
              </div>
            </div>
            <a href="#" class="block p-3 bg-slate-50 hover:bg-red-50 text-center text-red-600 font-semibold text-sm transition-colors duration-300">
              Lihat Detail <i class="fas fa-arrow-right ml-2"></i>
            </a>
          </div>
        </div>
      </div>
    </div>

    {{-- LAPANGAN & BOOKING SECTION --}}
    @if(isset($region) && isset($lapangan))
    <div class="row">
      {{-- LAPANGAN --}}
      <div class="col-lg-6 mb-4">
        <div class="card shadow-md border-0 rounded-lg overflow-hidden">
          <div class="card-header bg-gradient-to-r from-blue-500 to-blue-600 text-white p-4 border-0">
            <h3 class="card-title mb-0 flex items-center font-bold text-lg">
              <i class="fas fa-futbol mr-3"></i> Daftar Lapangan ({{ $regionLabel }})
            </h3>
          </div>
          <div class="card-body p-4">
            @if($lapangan->count() > 0)
              <div class="space-y-3">
                @foreach($lapangan as $item)
                <div class="border border-slate-200 rounded-lg p-4 hover:shadow-md transition-all duration-300 hover:border-blue-300">
                  <div class="flex justify-between items-start mb-2">
                    <h5 class="font-bold text-slate-800">{{ $item->namaLapangan }}</h5>
                    <span class="badge bg-blue-100 text-blue-800 text-xs font-semibold px-3 py-1 rounded-full">
                      {{ $item->jenisLapangan }}
                    </span>
                  </div>
                  <p class="text-sm text-slate-600 mb-2 line-clamp-2">{{ $item->deskripsi ?? '-' }}</p>
                  <div class="flex justify-between items-center">
                    <span class="font-bold text-lg text-green-600">
                      Rp {{ number_format($item->harga, 0, ',', '.') }}/jam
                    </span>
                    <span class="badge {{ $item->status === 'aktif' ? 'bg-success' : 'bg-danger' }} text-white text-xs px-3 py-1 rounded-full">
                      {{ ucfirst($item->status) }}
                    </span>
                  </div>
                </div>
                @endforeach
              </div>
            @else
              <div class="text-center py-8">
                <i class="fas fa-inbox text-4xl text-slate-300 mb-3"></i>
                <p class="text-slate-500 font-semibold">Belum ada lapangan</p>
              </div>
            @endif
          </div>
          <div class="card-footer bg-slate-50 p-4 border-t">
            <a href="{{ route('inputLapangan.Lapangan') }}" class="btn btn-primary btn-sm w-full">
              <i class="fas fa-plus mr-2"></i> Tambah Lapangan Baru
            </a>
          </div>
        </div>
      </div>

      {{-- BOOKING TERBARU --}}
      <div class="col-lg-6 mb-4">
        <div class="card shadow-md border-0 rounded-lg overflow-hidden">
          <div class="card-header bg-gradient-to-r from-green-500 to-green-600 text-white p-4 border-0">
            <h3 class="card-title mb-0 flex items-center font-bold text-lg">
              <i class="fas fa-calendar-check mr-3"></i> Booking Terbaru ({{ $regionLabel }})
            </h3>
          </div>
          <div class="card-body p-4">
            @if($boking->count() > 0)
              <div class="space-y-3">
                @foreach($boking->take(5) as $item)
                <div class="border border-slate-200 rounded-lg p-4 hover:shadow-md transition-all duration-300 hover:border-green-300">
                  <div class="flex justify-between items-start mb-2">
                    <h5 class="font-bold text-slate-800">{{ $item->nama ?? 'Pelanggan' }}</h5>
                    <span class="badge {{ $item->status === 'completed' ? 'bg-success' : ($item->status === 'pending' ? 'bg-warning' : 'bg-danger') }} text-white text-xs px-3 py-1 rounded-full">
                      {{ ucfirst($item->status) }}
                    </span>
                  </div>
                  <p class="text-sm text-slate-600 mb-2">
                    <i class="fas fa-map-pin mr-2 text-green-600"></i><strong>{{ $item->lapangan ?? 'N/A' }}</strong>
                  </p>
                  <div class="flex justify-between items-center text-sm">
                    <span class="text-slate-600">
                      <i class="fas fa-calendar mr-2 text-blue-600"></i>{{ $item->tanggal?->format('d M Y') }}
                    </span>
                    <span class="text-slate-600">
                      <i class="fas fa-clock mr-2 text-orange-600"></i>{{ $item->jam_mulai ?? '-' }} - {{ $item->jam_selesai ?? '-' }}
                    </span>
                  </div>
                </div>
                @endforeach
              </div>
            @else
              <div class="text-center py-8">
                <i class="fas fa-inbox text-4xl text-slate-300 mb-3"></i>
                <p class="text-slate-500 font-semibold">Belum ada booking</p>
              </div>
            @endif
          </div>
          <div class="card-footer bg-slate-50 p-4 border-t">
            <a href="#" class="btn btn-success btn-sm w-full">
              <i class="fas fa-list mr-2"></i> Lihat Semua Booking
            </a>
          </div>
        </div>
      </div>
    </div>

    {{-- EVENTS & SLIDERS --}}
    @if($events->count() > 0 || $sliders->count() > 0)
    <div class="row mt-4">
      @if($events->count() > 0)
      <div class="col-lg-6 mb-4">
        <div class="card shadow-md border-0 rounded-lg overflow-hidden">
          <div class="card-header bg-gradient-to-r from-yellow-500 to-yellow-600 text-white p-4 border-0">
            <h3 class="card-title mb-0 flex items-center font-bold text-lg">
              <i class="fas fa-star mr-3"></i> Event Terbaru
            </h3>
          </div>
          <div class="card-body p-4">
            <div class="space-y-3">
              @foreach($events as $event)
              <div class="border border-slate-200 rounded-lg p-4 hover:shadow-md transition-all duration-300">
                <h5 class="font-bold text-slate-800 mb-2">{{ $event->nama }}</h5>
                <p class="text-sm text-slate-600 mb-2">{{ Str::limit($event->deskripsi, 100) }}</p>
                <div class="flex justify-between items-center text-sm">
                  <span class="text-slate-600"><i class="fas fa-calendar mr-2"></i>{{ $event->tanggal?->format('d M Y') }}</span>
                  <span class="badge bg-{{ $event->status === 'active' ? 'success' : 'danger' }} text-white">{{ ucfirst($event->status) }}</span>
                </div>
              </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
      @endif

      @if($sliders->count() > 0)
      <div class="col-lg-6 mb-4">
        <div class="card shadow-md border-0 rounded-lg overflow-hidden">
          <div class="card-header bg-gradient-to-r from-red-500 to-red-600 text-white p-4 border-0">
            <h3 class="card-title mb-0 flex items-center font-bold text-lg">
              <i class="fas fa-image mr-3"></i> Slider
            </h3>
          </div>
          <div class="card-body p-4">
            <div class="grid grid-cols-2 gap-3">
              @foreach($sliders as $slider)
              <div class="rounded-lg overflow-hidden hover:shadow-lg transition-all duration-300 bg-slate-100 relative group">
                <div class="aspect-square bg-slate-200 flex items-center justify-center">
                  @if($slider->gambar)
                    <img src="{{ $slider->gambar }}" alt="{{ $slider->judul }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                  @else
                    <i class="fas fa-image text-slate-400 text-4xl"></i>
                  @endif
                </div>
                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent p-2">
                  <p class="text-white text-xs font-semibold line-clamp-1">{{ $slider->judul ?? 'Slider' }}</p>
                </div>
              </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
      @endif
    </div>
    @endif

    @endif

  </div>
</div>

<style>
  .line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
    line-clamp: 1;
  }
  .line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    line-clamp: 2;
  }
</style>

@endsection
