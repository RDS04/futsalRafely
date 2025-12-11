@extends('layout.layout')
@section("content")
<div class="max-w-3xl mx-auto p-6 mt-6 bg-white shadow-lg rounded-2xl">

    <h2 class="text-2xl font-bold mb-4 text-teal-600">Pembayaran Booking</h2>

    <!-- INFORMASI PESANAN -->
    <div class="bg-gray-50 p-4 rounded-lg border mb-6">
        <p class="text-sm text-gray-600">Total Pembayaran</p>
        <p class="text-3xl font-bold text-teal-600">Rp {{ number_format($total, 0, ',', '.') }}</p>
    </div>

    <!-- METODE PEMBAYARAN -->
    <h3 class="text-lg font-semibold mb-3">Pilih Metode Pembayaran</h3>

    <form action="/pembayaran/kirim" method="POST" enctype="multipart/form-data">
        @csrf

        <div x-data="{ metode: '' }" class="space-y-4">

            <!-- PILIHAN METODE -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

                <!-- BANK TRANSFER -->
                <label @click="metode='bank'"
                    class="cursor-pointer p-4 border rounded-xl flex flex-col items-center hover:border-teal-500 transition"
                    :class="metode=='bank' ? 'border-teal-500 bg-teal-50' : '' ">
                    🏦
                    <span class="mt-2 font-semibold text-sm">Bank Transfer</span>
                </label>

                <!-- E-WALLET -->
                <label @click="metode='ewallet'"
                    class="cursor-pointer p-4 border rounded-xl flex flex-col items-center hover:border-teal-500 transition"
                    :class="metode=='ewallet' ? 'border-teal-500 bg-teal-50' : '' ">
                    📱
                    <span class="mt-2 font-semibold text-sm">E-Wallet</span>
                </label>

                <!-- QRIS -->
                <label @click="metode='qris'"
                    class="cursor-pointer p-4 border rounded-xl flex flex-col items-center hover:border-teal-500 transition"
                    :class="metode=='qris' ? 'border-teal-500 bg-teal-50' : '' ">
                    🔳
                    <span class="mt-2 font-semibold text-sm">QRIS</span>
                </label>

                <!-- VIRTUAL ACCOUNT -->
                <label @click="metode='va'"
                    class="cursor-pointer p-4 border rounded-xl flex flex-col items-center hover:border-teal-500 transition"
                    :class="metode=='va' ? 'border-teal-500 bg-teal-50' : '' ">
                    💳
                    <span class="mt-2 font-semibold text-sm">Virtual Account</span>
                </label>
            </div>

            <!-- DETAIL INSTRUKSI -->
            <div class="mt-4">

                <!-- BANK TRANSFER -->
                <div x-show="metode=='bank'" class="p-4 bg-gray-50 rounded-lg border">
                    <p class="font-semibold">Transfer ke rekening berikut:</p>
                    <p class="mt-2 text-gray-700">🏦 BCA • 1234567890 • A/N Lapangan Futsal</p>
                    <p class="text-gray-700">🏦 Mandiri • 9876543210 • A/N Lapangan Futsal</p>
                </div>

                <!-- E-WALLET -->
                <div x-show="metode=='ewallet'" class="p-4 bg-gray-50 rounded-lg border">
                    <p class="font-semibold">E-Wallet tersedia:</p>
                    <p class="mt-2">📲 Dana • 089123456789</p>
                    <p>📲 OVO • 089987654321</p>
                    <p>📲 Gopay • 081234567890</p>
                </div>

                <!-- QRIS -->
                <div x-show="metode=='qris'" class="p-4 bg-gray-50 rounded-lg border">
                    <p class="font-semibold mb-3">Scan QRIS di bawah ini:</p>
                    <img src="/img/qris.png" class="w-48 mx-auto">
                </div>

                <!-- VIRTUAL ACCOUNT -->
                <div x-show="metode=='va'" class="p-4 bg-gray-50 rounded-lg border">
                    <p class="font-semibold">Gunakan Virtual Account berikut:</p>
                    <p class="mt-2">💳 VA BCA: 393900{{ Auth::id() }}</p>
                    <p>💳 VA Mandiri: 88777{{ Auth::id() }}</p>
                </div>

            </div>

            <!-- UPLOAD BUKTI -->
            <div class="mt-6">
                <label class="font-semibold block mb-2">Upload Bukti Pembayaran</label>
                <input type="file" name="bukti"
                    class="w-full p-3 border rounded-lg bg-gray-50 cursor-pointer">
            </div>

            <!-- BUTTON SUBMIT -->
            <button class="mt-6 w-full bg-teal-600 hover:bg-teal-700 text-white py-3 rounded-xl font-semibold">
                Konfirmasi Pembayaran
            </button>
        </div>
    </form>
</div>

@endsection