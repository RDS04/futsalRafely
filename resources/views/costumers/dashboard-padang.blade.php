@extends('layout.layout')
@section('content')

<style>
    .slider-track {
        transition: transform 0.6s ease-in-out;
    }

    .dot.active {
        background-color: #0ea5e9;
        border-color: #0284c7;
        transform: scale(1.3);
        box-shadow: 0 0 10px rgba(14, 165, 233, 0.6);
    }

    .slider-btn {
        opacity: 0;
        transition: all 0.3s ease;
    }

    .slider-container:hover .slider-btn {
        opacity: 1;
    }

    .slider-btn:hover {
        background-color: #0ea5e9;
        color: white;
    }
</style>
</head>

<body class="bg-gray-50 font-sans">
    <nav
        class="bg-gradient-to-r from-gray-800 to-gray-900 p-4 sticky top-0 z-50 shadow-md flex justify-between items-center text-white">
        <div class="flex items-center gap-2">
            <img src="data:image/jpeg;base64,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"
                alt="Logo" class="w-12 h-12 rounded-full">
            <span class="font-semibold text-lg">Futsal Booking</span>
        </div>

        <ul class="flex space-x-8">
            <li><a href="#" class="text-gray-300 hover:text-blue-400">Beranda</a></li>
            <li><a href="#scheduleSection" class="text-gray-300 hover:text-blue-400">Jadwal</a></li>
            <li><a href="#lapanganUnggulan" class="text-gray-300 hover:text-blue-400">Pesanan</a></li>
            <li><a href="#event" class="text-gray-300 hover:text-blue-400">Event</a></li>
            <li><a href="#footer" class="text-gray-300 hover:text-blue-400">Kontak</a></li>
        </ul>

        <!-- Navbar User Section -->
        <div class="flex items-center gap-4">
            @guest
            <!-- KALAU BELUM LOGIN -->
            <div class="flex gap-3">
                <a href="{{ route('login') }}"
                    class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                    Login
                </a>

                <a href="{{ route('register') }}"
                    class="px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-800 transition">
                    Register
                </a>
            </div>
            @endguest

            @auth
            <!-- KALAU SUDAH LOGIN -->
            <div class="relative" id="profileDropdown">
                <!-- PROFILE BUTTON -->
                <button id="profileBtn"
                    class="flex items-center gap-2 bg-gray-800 bg-opacity-50 p-2 rounded-lg backdrop-blur hover:bg-gray-700 transition">

                    <img src="{{ Auth::user()->profile ?? 'https://ui-avatars.com/api/?name=' . Auth::user()->name }}"
                        alt="User" class="w-10 h-10 rounded-full border-2 border-blue-400">

                    <div class="flex flex-col text-left">
                        <p class="font-semibold text-sm">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-400">Pengguna</p>
                    </div>
                </button>

                <!-- DROPDOWN MENU -->
                <div id="dropdownMenu"
                    class="hidden absolute right-0 mt-2 w-48 bg-white shadow-lg rounded-lg overflow-hidden z-50">

                    <a href="{{ route('show.payment') }}"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition">
                        Riwayat Booking
                    </a>

                    <a href="/edit-profile"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition">
                        Edit Profil
                    </a>

                    <hr class="my-2">

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-red-100 transition">
                            Logout
                        </button>
                    </form>
                </div>
            </div>

            @endauth
        </div>

    </nav>

    <main class="min-h-screen bg-gray-100">
        <section class="mt-8 mb-8 px-4">
            <div class="relative w-11/12 mx-auto overflow-hidden rounded-lg shadow-lg slider-container mt-8">
                <button onclick="moveSlide(-1)" class="slider-btn absolute left-4 top-1/2  bg-white text-gray-800 rounded-full w-12 h-12 flex items-center justify-center font-bold text-xl z-10">❮</button>
                    <ul class="flex slider-track" id="sliderTrack">
                        @foreach ($sliders as $slide)
                        <li class="min-w-full">
                            <img src="{{ asset('storage/' . $slide->gambar) }}"
                                alt="Futsal Banner 1" class="w-full h-[600px] object-cover">
                        </li>
                        @endforeach
                    </ul>
                <button onclick="moveSlide(1)"class="slider-btn absolute right-4 top-1/2  bg-white text-gray-800 rounded-full w-12 h-12 flex items-center justify-center font-bold text-xl z-10">❯</button>
            </div>

            <div class="flex justify-center gap-3 mt-6">
                <span
                    class="dot active w-3 h-3 rounded-full bg-gray-600 border-2 border-gray-800 cursor-pointer transition-all"
                    onclick="goToSlide(0)"></span>
                <span
                    class="dot w-3 h-3 rounded-full bg-gray-600 border-2 border-gray-800 cursor-pointer transition-all"
                    onclick="goToSlide(1)"></span>
            </div>
        </section>


        <!-- Schedule Section -->
        <section class="max-w-5xl mx-auto py-12 px-4" id="scheduleSection">
            <h2 class="text-3xl text-gray-800 font-bold text-center mb-8">Jadwal Lapangan</h2>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Calendar -->
                <div class="lg:col-span-2 bg-white rounded-lg shadow-lg p-6">
                    <div class="flex justify-between items-center mb-6">
                        <button onclick="prevMonth()"
                            class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">← Sebelumnya</button>
                        <h3 class="text-2xl font-bold text-gray-800" id="monthYear"></h3>
                        <button onclick="nextMonth()"
                            class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Selanjutnya →</button>
                    </div>

                    <div class="grid grid-cols-7 gap-2 mb-4">
                        <div class="text-center font-bold text-gray-700">Min</div>
                        <div class="text-center font-bold text-gray-700">Sen</div>
                        <div class="text-center font-bold text-gray-700">Sel</div>
                        <div class="text-center font-bold text-gray-700">Rab</div>
                        <div class="text-center font-bold text-gray-700">Kam</div>
                        <div class="text-center font-bold text-gray-700">Jum</div>
                        <div class="text-center font-bold text-gray-700">Sab</div>
                    </div>

                    <div class="grid grid-cols-7 gap-2" id="calendarGrid"></div>
                </div>

                <!-- Legend & Details -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Keterangan</h3>
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="w-6 h-6 bg-green-500 rounded"></div>
                            <p class="text-gray-700">Lapangan Kosong</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-6 h-6 bg-red-500 rounded"></div>
                            <p class="text-gray-700">Lapangan Terpakai</p>
                        </div>
                    </div>

                    <div class="mt-6 border-t pt-4">
                        <h4 class="font-bold text-gray-800 mb-3">Detail Tanggal</h4>
                        <div id="dateDetail" class="text-sm text-gray-600 space-y-2">
                            <p>Pilih tanggal untuk melihat detail</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Featured Venues Section -->
        <section class="max-w-6xl mx-auto py-12" id="lapanganUnggulan">
            <h2 class="text-3xl text-gray-800 font-bold text-center mb-8">
                Lapangan Unggulan
            </h2>

            @if ($lapangan->isEmpty())
            {{-- EMPTY STATE --}}
            <div class="min-h-[50vh] flex items-center justify-center px-4">
                <div class="max-w-md w-full text-center bg-white rounded-2xl shadow-lg p-8 border">
                    <div class="flex justify-center mb-4">
                        <div class="w-16 h-16 flex items-center justify-center rounded-full bg-blue-100 text-blue-600">
                            ⚽
                        </div>
                    </div>

                    <h1 class="text-2xl font-semibold text-gray-800 mb-2">
                        Lapangan Belum Tersedia
                    </h1>

                    <p class="text-gray-500 mb-6">
                        Admin belum menambahkan data lapangan untuk region ini.
                    </p>

                    <a href="/" class="px-5 py-2 rounded-lg bg-blue-600 text-white text-sm hover:bg-blue-700">
                        Kembali
                    </a>
                </div>
            </div>
            @else

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($lapangan as $lp)
                <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-xl transition">

                    {{-- IMAGE --}}
                    <div class="relative">
                        <img
                            src="{{ asset('storage/' . $lp->gambar) }}"
                            class="w-full h-52 object-cover"
                            alt="{{ $lp->nama_lapangan }}">

                        {{-- STATUS BADGE --}}
                        @if ($lp->status === 'tersedia')
                        <span class="absolute top-3 left-3 bg-green-600 text-white text-xs px-3 py-1 rounded-full">
                            Tersedia
                        </span>
                        @else
                        <span class="absolute top-3 left-3 bg-red-600 text-white text-xs px-3 py-1 rounded-full">
                            Tidak Tersedia
                        </span>
                        @endif
                    </div>

                    {{-- CONTENT --}}
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-800">
                            {{ $lp->nama_lapangan }}
                        </h3>

                        <p class="text-gray-500 text-sm mb-2">
                            Jenis: {{ $lp->jenisLapangan }}
                        </p>

                        <p class="text-blue-600 font-bold mb-4">
                            Rp {{ number_format($lp->harga, 0, ',', '.') }}/jam
                        </p>
                        <i>
                            <p class="text-gray-600 text-sm mb-4 h-16 overflow-hidden text-ellipsis">
                                {{ Str::limit($lp->deskripsi, 100, '...') }}
                            </p>
                        </i>

                        {{-- BUTTON --}}
                        @if ($lp->status === 'tersedia')
                        <a href="{{ route('boking.form', $lp->id) }}">
                            <button
                                class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                                Pesan Sekarang
                            </button>
                        </a>
                        @else
                        <button
                            class="w-full bg-gray-300 text-gray-600 py-2 rounded-lg cursor-not-allowed">
                            Tidak Bisa Dipesan
                        </button>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </section>



        <!-- Latest Events Section -->
        <section class="max-w-5xl mx-auto py-12 px-4" id="event">
            <h2 class="text-3xl text-gray-800 font-bold text-center ">Event Terbaru</h2>
            <div class="space-y-6 ">
                @foreach ($event as $ev)
                <div
                    class="bg-white shadow-md hover:shadow-xl transition-shadow rounded-lg overflow-hidden flex w-full">
                    <!-- FOTO (KIRI) -->
                    <div class="relative w-[400px] h-[200px]">
                        <img src="{{ asset('storage/' . $ev->gambar) }}" alt="{{ $ev->judul }}"
                            class="w-full h-full object-cover">

                        <span
                            class="absolute top-3 right-3 bg-yellow-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
                            Berlangsung
                        </span>
                    </div>

                    <!-- KETERANGAN (KANAN) -->
                    <div class="p-6 w-full flex flex-col justify-between">
                        <div class="flex flex-col w-100%">
                            <h3 class="text-2xl font-bold text-teal-500 mb-3">{{ $ev->judul }} </h3>
                            <div class="space-y-2 text-sm text-gray-600 mb-4">
                                <div class="flex items-center gap-2">📅 <span>{{ $ev->tanggal_mulai }} - {{ $ev->tanggal_selesai }}</span></div>
                            </div>
                            <p class="text-gray-600 text-sm text-justify leading-relaxed">
                                {{ Str::limit($ev->deskripsi, 150, '...') }}
                            </p>
                            <a href="#" class="text-teal-500 hover:underline">Selengkapnya →</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
    </main>


    <!-- Contact Form Section -->
    <section class="max-w-6xl mx-auto py-16 px-4 bg-gray-50" id="footer">
        <h2 class="text-4xl font-extrabold text-gray-800 text-center mb-14 tracking-wide">
            Hubungi Kami
        </h2>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">

            <!-- CONTACT INFO -->
            <div class="space-y-8">

                <div class="flex items-start gap-5">
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 shadow-lg
                            rounded-xl flex items-center justify-center text-white text-2xl">
                        📍
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Lokasi</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Jl. Merdeka No. 45, Jakarta Pusat, Indonesia 12190
                        </p>
                    </div>
                </div>

                <div class="flex items-start gap-5">
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 shadow-lg
                            rounded-xl flex items-center justify-center text-white text-2xl">
                        📞
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Telepon</h3>
                        <p class="text-gray-600">+62 (21) 234-5678</p>
                        <p class="text-gray-600">WhatsApp: +62 812-3456-7890</p>
                    </div>
                </div>

                <div class="flex items-start gap-5">
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 shadow-lg
                            rounded-xl flex items-center justify-center text-white text-2xl">
                        ✉️
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Email</h3>
                        <p class="text-gray-600">info@futsalbooking.com</p>
                        <p class="text-gray-600">support@futsalbooking.com</p>
                    </div>
                </div>

                <div class="flex items-start gap-5">
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 shadow-lg
                            rounded-xl flex items-center justify-center text-white text-2xl">
                        🕐
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Jam Operasional</h3>
                        <p class="text-gray-600">Senin - Jumat: 08:00 - 22:00</p>
                        <p class="text-gray-600">Sabtu - Minggu: 07:00 - 23:00</p>
                    </div>
                </div>

            </div>

            <!-- CONTACT FORM -->
            <div class="bg-white/70 backdrop-blur-lg shadow-xl p-10 rounded-2xl border border-white/40" id="footer">
                <form class="space-y-5">

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                        <input type="text" placeholder="Masukkan nama Anda" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 
                               focus:ring-blue-500 outline-none transition shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                        <input type="email" placeholder="Masukkan email Anda" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 
                               focus:ring-blue-500 outline-none transition shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Telepon</label>
                        <input type="tel" placeholder="Masukkan nomor telepon Anda" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 
                               focus:ring-blue-500 outline-none transition shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Subjek</label>
                        <select class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 
                                   focus:ring-blue-500 outline-none transition shadow-sm">
                            <option>Pilih Subjek</option>
                            <option>Pertanyaan Umum</option>
                            <option>Keluhan</option>
                            <option>Saran</option>
                            <option>Kerjasama</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Pesan</label>
                        <textarea rows="5" placeholder="Tuliskan pesan Anda di sini..." class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 
                               focus:ring-blue-500 outline-none transition shadow-sm resize-none"></textarea>
                    </div>

                    <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-blue-600 text-white font-semibold 
                           py-3 rounded-lg hover:shadow-xl hover:scale-[1.03] transition-all duration-300">
                        Kirim Pesan
                    </button>
                </form>
            </div>

        </div>
    </section>


    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-400 py-12">
        <div class="max-w-5xl mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div>
                <h3 class="text-white text-lg font-semibold">📍 Tentang Kami</h3>
                <p>Futsal Booking adalah platform terpercaya untuk reservasi lapangan futsal berkualitas tinggi di
                    seluruh Indonesia.</p>
            </div>
            <div>
                <h3 class="text-white text-lg font-semibold">🔗 Navigasi</h3>
                <ul>
                    <li><a href="#beranda" class="hover:text-blue-400">Beranda</a></li>
                    <li><a href="#jadwal" class="hover:text-blue-400">Jadwal</a></li>
                    <li><a href="#pesanan" class="hover:text-blue-400">Pesanan Saya</a></li>
                    <li><a href="#event" class="hover:text-blue-400">Event</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-white text-lg font-semibold">📞 Hubungi Kami</h3>
                <ul>
                    <li><a href="tel:+62212345678" class="hover:text-blue-400">+62 (21) 234-5678</a></li>
                    <li><a href="mailto:info@futsalbooking.com" class="hover:text-blue-400">info@futsalbooking.com</a>
                    </li>
                    <li><a href="#" class="hover:text-blue-400">WhatsApp: +62 812-3456-7890</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-white text-lg font-semibold">🌐 Ikuti Kami</h3>
                <ul>
                    <li><a href="#" class="hover:text-blue-400">Facebook</a></li>
                    <li><a href="#" class="hover:text-blue-400">Instagram</a></li>
                    <li><a href="#" class="hover:text-blue-400">Twitter</a></li>
                    <li><a href="#" class="hover:text-blue-400">TikTok</a></li>
                </ul>
            </div>
        </div>
        <div class="border-t border-gray-700 pt-4 text-center text-gray-500">
            <p>&copy; 2024 Futsal Booking. Semua hak dilindungi. | <a href="#" class="text-blue-400">Kebijakan
                    Privasi</a> | <a href="#" class="text-blue-400">Syarat & Ketentuan</a></p>
        </div>
    </footer>
</body>

<script src="{{ asset('static/js/index.js') }}"></script>

@endsection