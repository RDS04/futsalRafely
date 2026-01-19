@extends('layouts.app')

@section('content')


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
