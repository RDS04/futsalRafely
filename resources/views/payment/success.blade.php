@extends('layout.layout')
@section("content")

<div class="min-h-screen bg-gray-50 py-10 px-4">
  <div class="max-w-2xl mx-auto">
    <!-- Success Card -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
      <!-- Success Header -->
      <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-12 text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-full mb-4">
          <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
          </svg>
        </div>
        <h1 class="text-3xl font-bold text-white mb-2">Pembayaran Berhasil!</h1>
        <p class="text-green-50 text-lg">Booking Anda telah dikonfirmasi</p>
      </div>

      <!-- Content -->
      <div class="px-6 py-8">
        <div class="mb-8 p-4 bg-blue-50 border-l-4 border-blue-500 rounded">
          <p class="text-blue-900 text-sm">
            <strong>Terima kasih!</strong> Pembayaran Anda telah diterima dan booking futsal Anda telah dikonfirmasi. 
            Silakan cek email untuk menerima konfirmasi lengkap.
          </p>
        </div>

        <!-- Order Details -->
        <div class="mb-8">
          <h2 class="text-xl font-semibold text-gray-900 mb-4">Detail Pesanan</h2>
          <div class="space-y-3 bg-gray-50 p-4 rounded">
            <div class="flex justify-between">
              <span class="text-gray-700">Order ID:</span>
              <span class="font-semibold text-gray-900">{{ request('order_id') }}</span>
            </div>
            <div class="flex justify-between border-t pt-3">
              <span class="text-gray-700">Status:</span>
              <span class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold">
                ✓ Terkonfirmasi
              </span>
            </div>
          </div>
        </div>

        <!-- What's Next -->
        <div class="mb-8">
          <h2 class="text-xl font-semibold text-gray-900 mb-4">Langkah Selanjutnya</h2>
          <ul class="space-y-3">
            <li class="flex items-start">
              <span class="flex items-center justify-center w-6 h-6 bg-green-500 text-white rounded-full text-sm font-bold mr-3 flex-shrink-0">1</span>
              <span class="text-gray-900">Anda akan menerima email konfirmasi dengan detail lengkap booking</span>
            </li>
            <li class="flex items-start">
              <span class="flex items-center justify-center w-6 h-6 bg-green-500 text-white rounded-full text-sm font-bold mr-3 flex-shrink-0">2</span>
              <span class="text-gray-900">Hadir 15 menit sebelum waktu booking dimulai</span>
            </li>
            <li class="flex items-start">
              <span class="flex items-center justify-center w-6 h-6 bg-green-500 text-white rounded-full text-sm font-bold mr-3 flex-shrink-0">3</span>
              <span class="text-gray-900">Nikmati bermain di lapangan futsal pilihan Anda</span>
            </li>
          </ul>
        </div>

        <!-- Info Box -->
        <div class="mb-8 bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded">
          <p class="text-yellow-900 text-sm">
            <strong>Catatan:</strong> Jika Anda perlu membatalkan booking, silakan hubungi kami minimal 24 jam sebelum jadwal booking.
          </p>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-4">
          <a href="{{ route('boking.form') }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg text-center transition">
            Pesan Lagi
          </a>
          <a href="{{ route('home') }}" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-900 font-bold py-3 px-4 rounded-lg text-center transition">
            Beranda
          </a>
        </div>
      </div>
    </div>

    <!-- Contact Info -->
    <div class="mt-8 text-center">
      <p class="text-gray-600 mb-2">Butuh bantuan?</p>
      <p class="text-gray-900 font-semibold">Hubungi: 0812-3456-7890 | Email: support@futsal.com</p>
    </div>
  </div>
</div>

@endsection
