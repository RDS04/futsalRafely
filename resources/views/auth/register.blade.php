@include('layout.layout')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 px-4">
    <div class="w-full max-w-md bg-white/10 backdrop-blur-md shadow-xl border border-white/20 rounded-2xl p-8">
        <h2 class="text-3xl font-semibold text-white text-center mb-6">
            Daftar Member Baru
        </h2>
        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg">
                <ul class="text-sm">
                    @foreach ($errors->all() as $error)
                        <li>- {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register.store') }}" method="POST">
            @csrf
            {{-- NAMA --}}
            <label class="block mb-1 text-slate-700 font-medium">Nama Lengkap</label>
            <input type="text" name="name"
                class="w-full mb-4 p-3 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500"
                placeholder="Masukkan nama lengkap">

            {{-- JENIS KELAMIN --}}
            <label class="block mb-1 text-slate-700 font-medium">Jenis Kelamin</label>
            <select name="gender"
                class="w-full mb-4 p-3 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500">
                <option value="">-- Pilih Jenis Kelamin --</option>
                <option value="L">Laki-Laki</option>
                <option value="P">Perempuan</option>
            </select>

            {{-- REGION --}}
            <label class="block mb-1 text-slate-700 font-medium">Region</label>
            <select name="region"
                class="w-full mb-4 p-3 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500" >
                <option value="">-- Pilih Cabang Futsal --</option>
                <option value="padang">Padang</option>
                <option value="sijunjung">Sijunjung</option>
                <option value="bukittinggi">Bukittinggi</option>
            </select>

            {{-- EMAIL --}}
            <label class="block mb-1 text-slate-700 font-medium">Email</label>
            <input type="email" name="email"
                class="w-full mb-4 p-3 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500"
                placeholder="Masukkan email" >

            {{-- PASSWORD --}}
            <label class="block mb-1 text-slate-700 font-medium">Password</label>
            <input type="password" name="password"
                class="w-full mb-4 p-3 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500"
                placeholder="Masukkan password" >

            {{-- NOMOR HP --}}
            <label class="block mb-1 text-slate-700 font-medium">No HP</label>
            <input type="text" name="phone"
                class="w-full mb-4 p-3 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500"
                placeholder="08xxx" >

            {{-- ALAMAT --}}
            <label class="block mb-1 text-slate-700 font-medium">Alamat</label>
            <textarea name="address"
                class="w-full mb-4 p-3 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500"
                placeholder="Masukkan alamat..." rows="3" ></textarea>

            {{-- BUTTON --}}
            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 rounded-lg transition">
                Daftar
            </button>
        </form>
    </div>
</div>
