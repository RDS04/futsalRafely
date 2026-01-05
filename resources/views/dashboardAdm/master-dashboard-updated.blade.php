@extends('layouts.app')

@section('content')
<div class="content-header bg-gradient-to-r from-purple-700 via-purple-600 to-indigo-600 text-white py-8 shadow-2xl">
  <div class="container-fluid px-4">
    <div class="row mb-4">
      <div class="col-sm-6">
        <h1 class="m-0 text-4xl font-bold">
          <i class="fas fa-crown mr-3 text-yellow-300 animate-pulse"></i>Master Dashboard
        </h1>
        <p class="text-purple-200 mt-2">
          <i class="fas fa-laptop-house mr-2"></i>Kelola semua region dari satu tempat
        </p>
      </div>
      <div class="col-sm-6 text-end">
        <div class="text-right">
          <p class="text-purple-100 mb-2">
            <i class="fas fa-user-shield mr-2"></i>{{ $admin->name }}
          </p>
          <span class="badge bg-yellow-400 text-yellow-900 px-4 py-2 text-sm font-bold">
            <i class="fas fa-star mr-2"></i>Master Admin (CEO)
          </span>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="content">
  <div class="container-fluid px-4">

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mt-4 animate-in" role="alert">
      <i class="fas fa-check-circle mr-2"></i>
      <strong>Sukses!</strong> {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    {{-- GLOBAL STATISTICS --}}
    <div class="row mt-6 mb-6">
      <!-- Total Lapangan -->
      <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
        <div class="card shadow-xl border-0 rounded-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
          <div class="card-body p-0">
            <div class="bg-gradient-to-br from-blue-400 to-blue-600 text-white p-6">
              <div class="flex items-center justify-between">
                <div>
                  <h3 class="text-3xl font-bold">{{ $totalLapangan }}</h3>
                  <p class="text-blue-100 text-sm mt-1">Total Lapangan</p>
                </div>
                <div class="text-5xl opacity-20">
                  <i class="fas fa-futbol"></i>
                </div>
              </div>
            </div>
            <div class="bg-slate-50 p-4 text-center border-t">
              <span class="text-xs text-slate-600 font-semibold">Dari semua region</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Total Booking -->
      <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
        <div class="card shadow-xl border-0 rounded-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
          <div class="card-body p-0">
            <div class="bg-gradient-to-br from-green-400 to-green-600 text-white p-6">
              <div class="flex items-center justify-between">
                <div>
                  <h3 class="text-3xl font-bold">{{ $totalBoking }}</h3>
                  <p class="text-green-100 text-sm mt-1">Total Booking</p>
                </div>
                <div class="text-5xl opacity-20">
                  <i class="fas fa-calendar-check"></i>
                </div>
              </div>
            </div>
            <div class="bg-slate-50 p-4 text-center border-t">
              <span class="text-xs text-slate-600 font-semibold">Dari semua region</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Total Event -->
      <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
        <div class="card shadow-xl border-0 rounded-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
          <div class="card-body p-0">
            <div class="bg-gradient-to-br from-yellow-400 to-yellow-600 text-white p-6">
              <div class="flex items-center justify-between">
                <div>
                  <h3 class="text-3xl font-bold">{{ $totalEvents }}</h3>
                  <p class="text-yellow-100 text-sm mt-1">Total Event</p>
                </div>
                <div class="text-5xl opacity-20">
                  <i class="fas fa-calendar-star"></i>
                </div>
              </div>
            </div>
            <div class="bg-slate-50 p-4 text-center border-t">
              <span class="text-xs text-slate-600 font-semibold">Dari semua region</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Total Admin -->
      <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
        <div class="card shadow-xl border-0 rounded-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
          <div class="card-body p-0">
            <div class="bg-gradient-to-br from-red-400 to-red-600 text-white p-6">
              <div class="flex items-center justify-between">
                <div>
                  <h3 class="text-3xl font-bold">{{ $totalAdmins }}</h3>
                  <p class="text-red-100 text-sm mt-1">Total Admin</p>
                  <small class="text-red-100">
                    <i class="fas fa-crown mr-1"></i>{{ $totalAdminMaster }} Master
                    <i class="fas fa-map-pin mr-1 ml-2"></i>{{ $totalAdminRegional }} Regional
                  </small>
                </div>
                <div class="text-5xl opacity-20">
                  <i class="fas fa-users"></i>
                </div>
              </div>
            </div>
            <div class="bg-slate-50 p-4 text-center border-t">
              <span class="text-xs text-slate-600 font-semibold">Admin aktif</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- REGION COMPARISON TABLE --}}
    <div class="row mb-6">
      <div class="col-md-12">
        <div class="card shadow-xl border-0 rounded-lg overflow-hidden">
          <div class="card-header bg-gradient-to-r from-slate-700 to-slate-900 text-white p-6 border-0">
            <h3 class="card-title mb-0 flex items-center font-bold text-xl">
              <i class="fas fa-chart-bar mr-3"></i> Perbandingan Statistik Per Region
            </h3>
          </div>
          <div class="card-body p-6">
            <div class="overflow-x-auto">
              <table class="table table-striped table-hover w-full">
                <thead class="bg-gradient-to-r from-slate-100 to-slate-200">
                  <tr>
                    <th class="px-4 py-3"><i class="fas fa-map-pin mr-2"></i>Region</th>
                    <th class="px-4 py-3 text-center"><i class="fas fa-futbol mr-2"></i>Lapangan</th>
                    <th class="px-4 py-3 text-center"><span class="badge bg-success">Aktif</span></th>
                    <th class="px-4 py-3 text-center"><i class="fas fa-calendar-check mr-2"></i>Booking</th>
                    <th class="px-4 py-3 text-center"><span class="badge bg-info">Confirmed</span></th>
                    <th class="px-4 py-3 text-center"><i class="fas fa-calendar-star mr-2"></i>Event</th>
                    <th class="px-4 py-3 text-center"><i class="fas fa-images mr-2"></i>Slider</th>
                    <th class="px-4 py-3 text-center">Action</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($regionStats as $regionCode => $stats)
                  <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-4 py-3 font-bold">
                      <i class="fas fa-map-marker-alt mr-2 text-primary"></i>
                      {{ ucfirst($regionCode) }}
                    </td>
                    <td class="px-4 py-3 text-center">
                      <span class="badge bg-primary">{{ $stats['lapangan'] }}</span>
                    </td>
                    <td class="px-4 py-3 text-center">
                      <span class="badge bg-success">{{ $stats['lapanganAktif'] }}</span>
                    </td>
                    <td class="px-4 py-3 text-center">
                      <span class="badge bg-info">{{ $stats['boking'] }}</span>
                    </td>
                    <td class="px-4 py-3 text-center">
                      <span class="badge bg-success">{{ $stats['bokingConfirmed'] }}</span>
                    </td>
                    <td class="px-4 py-3 text-center">
                      <span class="badge bg-warning">{{ $stats['events'] }}</span>
                    </td>
                    <td class="px-4 py-3 text-center">
                      <span class="badge bg-secondary">{{ $stats['sliders'] }}</span>
                    </td>
                    <td class="px-4 py-3 text-center">
                      <a href="{{ route('admin.dashboard.region', ['region' => $regionCode]) }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-arrow-right mr-1"></i>Lihat
                      </a>
                    </td>
                  </tr>
                  @empty
                  <tr>
                    <td colspan="8" class="text-center py-6 text-slate-500">
                      <i class="fas fa-inbox text-3xl mb-3 block opacity-50"></i>
                      Belum ada data region
                    </td>
                  </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- RECENT BOOKINGS & TOP LAPANGAN --}}
    <div class="row mb-6">
      {{-- RECENT BOOKINGS --}}
      <div class="col-lg-6 mb-4">
        <div class="card shadow-xl border-0 rounded-lg overflow-hidden">
          <div class="card-header bg-gradient-to-r from-emerald-500 to-emerald-700 text-white p-6 border-0">
            <h3 class="card-title mb-0 flex items-center font-bold text-lg">
              <i class="fas fa-history mr-3"></i> Booking Terbaru (10 Terakhir)
            </h3>
          </div>
          <div class="card-body p-4">
            @if($recentBookings->count() > 0)
              <div class="space-y-3">
                @foreach($recentBookings as $booking)
                <div class="border-l-4 border-emerald-500 bg-emerald-50 p-4 rounded-r-lg hover:shadow-md transition-all duration-300">
                  <div class="flex justify-between items-start">
                    <div class="flex-1">
                      <h5 class="font-bold text-slate-800">{{ $booking->nama ?? 'N/A' }}</h5>
                      <p class="text-sm text-slate-600 mt-1">
                        <i class="fas fa-futbol mr-1"></i>{{ $booking->lapangan }}
                      </p>
                      <p class="text-sm text-slate-600 mt-1">
                        <i class="fas fa-calendar mr-1"></i>{{ $booking->tanggal }}
                      </p>
                    </div>
                    <div class="text-right">
                      <span class="badge bg-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'pending' ? 'warning' : 'danger') }}">
                        {{ ucfirst($booking->status) }}
                      </span>
                      <p class="text-xs text-slate-500 mt-2">
                        <i class="fas fa-map-pin mr-1"></i>{{ ucfirst($booking->region) }}
                      </p>
                    </div>
                  </div>
                </div>
                @endforeach
              </div>
            @else
              <div class="text-center py-8">
                <i class="fas fa-inbox text-4xl text-slate-300 mb-3 block"></i>
                <p class="text-slate-500">Belum ada booking</p>
              </div>
            @endif
          </div>
        </div>
      </div>

      {{-- TOP LAPANGAN --}}
      <div class="col-lg-6 mb-4">
        <div class="card shadow-xl border-0 rounded-lg overflow-hidden">
          <div class="card-header bg-gradient-to-r from-orange-500 to-orange-700 text-white p-6 border-0">
            <h3 class="card-title mb-0 flex items-center font-bold text-lg">
              <i class="fas fa-trophy mr-3"></i> Lapangan Terpopuler
            </h3>
          </div>
          <div class="card-body p-4">
            @if($topLapangan->count() > 0)
              <div class="space-y-3">
                @foreach($topLapangan as $index => $lapangan)
                <div class="flex items-center p-3 bg-orange-50 rounded-lg border-l-4 border-orange-400 hover:shadow-md transition-all">
                  <div class="flex-shrink-0 mr-3">
                    <span class="inline-block w-10 h-10 rounded-full bg-orange-500 text-white text-center leading-10 font-bold">
                      #{{ $index + 1 }}
                    </span>
                  </div>
                  <div class="flex-1">
                    <h5 class="font-semibold text-slate-800">{{ $lapangan->lapangan ?? $lapangan->namaLapangan }}</h5>
                    <p class="text-sm text-slate-600">
                      <i class="fas fa-map-pin mr-1"></i>{{ ucfirst($lapangan->region ?? 'N/A') }}
                    </p>
                  </div>
                  <div class="text-right">
                    <span class="badge bg-info">{{ $lapangan->booking_count ?? 0 }} Booking</span>
                  </div>
                </div>
                @endforeach
              </div>
            @else
              <div class="text-center py-8">
                <i class="fas fa-chart-line text-4xl text-slate-300 mb-3 block"></i>
                <p class="text-slate-500">Belum ada data</p>
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>

    {{-- ADMIN MANAGEMENT --}}
    <div class="row mb-6">
      <div class="col-md-12">
        <div class="card shadow-xl border-0 rounded-lg overflow-hidden">
          <div class="card-header bg-gradient-to-r from-indigo-600 to-indigo-800 text-white p-6 border-0">
            <div class="flex justify-between items-center">
              <h3 class="card-title mb-0 flex items-center font-bold text-lg">
                <i class="fas fa-users-cog mr-3"></i> Manajemen Admin
              </h3>
              <a href="{{ route('admin.register.show') }}" class="btn btn-light btn-sm">
                <i class="fas fa-plus mr-1"></i>Tambah Admin
              </a>
            </div>
          </div>
          <div class="card-body p-6">
            <div class="overflow-x-auto">
              <table class="table table-striped table-hover w-full">
                <thead class="bg-gradient-to-r from-indigo-100 to-indigo-50">
                  <tr>
                    <th class="px-4 py-3"><i class="fas fa-user mr-2"></i>Nama Admin</th>
                    <th class="px-4 py-3"><i class="fas fa-envelope mr-2"></i>Email</th>
                    <th class="px-4 py-3"><i class="fas fa-shield-alt mr-2"></i>Role</th>
                    <th class="px-4 py-3"><i class="fas fa-map-pin mr-2"></i>Region</th>
                    <th class="px-4 py-3"><i class="fas fa-clock mr-2"></i>Dibuat</th>
                    <th class="px-4 py-3">Action</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($allAdmins as $adm)
                  <tr class="hover:bg-indigo-50 transition-colors">
                    <td class="px-4 py-3 font-semibold">{{ $adm->name }}</td>
                    <td class="px-4 py-3 text-sm">{{ $adm->email }}</td>
                    <td class="px-4 py-3">
                      @if($adm->role === 'master')
                        <span class="badge bg-warning text-dark">
                          <i class="fas fa-crown mr-1"></i>{{ $adm->role_label }}
                        </span>
                      @else
                        <span class="badge bg-info">
                          <i class="fas fa-map-marker-alt mr-1"></i>{{ $adm->role_label }}
                        </span>
                      @endif
                    </td>
                    <td class="px-4 py-3">
                      <span class="badge bg-secondary">{{ ucfirst($adm->region) }}</span>
                    </td>
                    <td class="px-4 py-3 text-sm">
                      {{ $adm->created_at->format('d M Y') }}
                    </td>
                    <td class="px-4 py-3">
                      <button class="btn btn-sm btn-outline-danger" title="Disable Admin">
                        <i class="fas fa-ban"></i>
                      </button>
                    </td>
                  </tr>
                  @empty
                  <tr>
                    <td colspan="6" class="text-center py-6 text-slate-500">
                      <i class="fas fa-inbox text-3xl mb-3 block opacity-50"></i>
                      Belum ada admin
                    </td>
                  </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- QUICK ACTIONS --}}
    <div class="row mb-6">
      <div class="col-md-12">
        <div class="card shadow-xl border-0 rounded-lg overflow-hidden">
          <div class="card-header bg-gradient-to-r from-pink-500 to-rose-500 text-white p-6 border-0">
            <h3 class="card-title mb-0 flex items-center font-bold text-lg">
              <i class="fas fa-lightning-bolt mr-3"></i> Aksi Cepat
            </h3>
          </div>
          <div class="card-body p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
              <!-- Padang Dashboard -->
              <a href="{{ route('admin.dashboard.region', 'padang') }}" class="group block p-6 bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg border-2 border-blue-200 hover:border-blue-500 hover:shadow-lg transition-all duration-300 text-center">
                <i class="fas fa-city text-4xl text-blue-600 mb-3 group-hover:scale-110 transition-transform"></i>
                <h5 class="font-bold text-slate-800">Dashboard Padang</h5>
                <p class="text-sm text-slate-600 mt-1">Kelola region Padang</p>
              </a>

              <!-- Sijunjung Dashboard -->
              <a href="{{ route('admin.dashboard.region', 'sijunjung') }}" class="group block p-6 bg-gradient-to-br from-green-50 to-green-100 rounded-lg border-2 border-green-200 hover:border-green-500 hover:shadow-lg transition-all duration-300 text-center">
                <i class="fas fa-leaf text-4xl text-green-600 mb-3 group-hover:scale-110 transition-transform"></i>
                <h5 class="font-bold text-slate-800">Dashboard Sijunjung</h5>
                <p class="text-sm text-slate-600 mt-1">Kelola region Sijunjung</p>
              </a>

              <!-- Bukittinggi Dashboard -->
              <a href="{{ route('admin.dashboard.region', 'bukittinggi') }}" class="group block p-6 bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg border-2 border-orange-200 hover:border-orange-500 hover:shadow-lg transition-all duration-300 text-center">
                <i class="fas fa-mountain text-4xl text-orange-600 mb-3 group-hover:scale-110 transition-transform"></i>
                <h5 class="font-bold text-slate-800">Dashboard Bukittinggi</h5>
                <p class="text-sm text-slate-600 mt-1">Kelola region Bukittinggi</p>
              </a>

              <!-- Logout -->
              <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="group block p-6 bg-gradient-to-br from-red-50 to-red-100 rounded-lg border-2 border-red-200 hover:border-red-500 hover:shadow-lg transition-all duration-300 text-center">
                <i class="fas fa-sign-out-alt text-4xl text-red-600 mb-3 group-hover:scale-110 transition-transform"></i>
                <h5 class="font-bold text-slate-800">Logout</h5>
                <p class="text-sm text-slate-600 mt-1">Keluar dari sistem</p>
              </a>
            </div>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
              @csrf
            </form>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

<style>
  .grid {
    display: grid;
  }
  .grid-cols-1 {
    grid-template-columns: repeat(1, minmax(0, 1fr));
  }
  @media (min-width: 768px) {
    .md\:grid-cols-2 {
      grid-template-columns: repeat(2, minmax(0, 1fr));
    }
  }
  @media (min-width: 1024px) {
    .lg\:grid-cols-4 {
      grid-template-columns: repeat(4, minmax(0, 1fr));
    }
  }
  
  .animate-in {
    animation: slideIn 0.3s ease-in;
  }
  @keyframes slideIn {
    from {
      opacity: 0;
      transform: translateY(-10px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
</style>

@endsection
