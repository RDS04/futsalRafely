@extends('layout.layout')
@section("content")

@endsection
<section class="max-w-3xl mx-auto py-10 px-6">
    <h2 class="text-3xl font-bold text-gray-800 text-center mb-8">Form Pemesanan Lapangan {{ Auth::user()->region }} </h2>

    <div class="bg-white rounded-xl shadow-lg p-8">
        <form action="{{ route('boking.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Nama Pemesan -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Pemesan</label>
                <input type="text" name="nama" 
                    value="{{ Auth::check() ? Auth::user()->name : '' }}"
                    placeholder="Masukkan nama pemesan"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg 
                           focus:ring-2 focus:ring-blue-500 outline-none transition">
            </div>

            <!-- Tanggal -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Booking</label>
                <input type="date" name="tanggal" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg 
                           focus:ring-2 focus:ring-blue-500 outline-none transition">
            </div>

            <!-- Jam Mulai & Selesai -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Jam Mulai</label>
                    <input type="time" name="jam_mulai"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg 
                               focus:ring-2 focus:ring-blue-500 outline-none transition">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Jam Selesai</label>
                    <input type="time" name="jam_selesai"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg 
                               focus:ring-2 focus:ring-blue-500 outline-none transition">
                </div>
            </div>

            <!-- Pilih Lapangan -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Lapangan</label>
                <select name="lapangan"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg 
                           focus:ring-2 focus:ring-blue-500 outline-none transition">
                    <option value="">Pilih Lapangan</option>
                    <option value="Vinyl Indoor">Vinyl Indoor</option>
                    <option value="Rumput Sintetis">Rumput Sintetis</option>
                    <option value="Outdoor">Outdoor</option>
                </select>
            </div>

            <!-- Catatan -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan Tambahan</label>
                <textarea name="catatan" rows="4"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg 
                           focus:ring-2 focus:ring-blue-500 outline-none transition resize-none"
                    placeholder="Contoh: ingin tambah wasit, sewa bola, dll"></textarea>
            </div>

            <!-- Submit -->
            <button type="submit"
                class="w-full bg-gradient-to-r from-blue-500 to-blue-600 
                       text-white font-bold py-3 rounded-lg hover:shadow-lg 
                       hover:scale-[1.02] transition">
                Pesan Sekarang
            </button>
        </form>
    </div>
</section>
