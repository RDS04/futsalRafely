@include('layout.layout')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 px-4">
    <div class="w-full max-w-md bg-white/10 backdrop-blur-md shadow-xl border border-white/20 rounded-2xl p-8">

        <h2 class="text-3xl font-semibold text-white text-center mb-6">
            Login Member
        </h2>

        @if(session('error'))
            <div class="mb-4 p-3 rounded-lg bg-red-500/20 text-red-300 text-sm border border-red-400/30">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf

            {{-- Name --}}
            <label class="block text-white/90 mb-1 font-medium">Name</label>
            <input type="text" name="name"
                class="w-full mb-4 p-3 rounded-lg bg-white/10 text-white placeholder-white/70 border border-white/20 focus:border-blue-400 focus:ring-2 focus:ring-blue-500"
                placeholder="Masukkan nama..." required>

            {{-- Password --}}
            <label class="block text-white/90 mb-1 font-medium">Password</label>
            <input type="password" name="password"
                class="w-full mb-4 p-3 rounded-lg bg-white/10 text-white placeholder-white/70 border border-white/20 focus:border-blue-400 focus:ring-2 focus:ring-blue-500"
                placeholder="Masukkan password..." required>

            {{-- Button --}}
            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 rounded-lg transition">
                Login
            </button>

            {{-- Register Link --}}
            <p class="text-center text-white/70 text-sm mt-4">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-blue-400 hover:underline">
                    Daftar sekarang
                </a>
            </p>

        </form>
    </div>
</div>
