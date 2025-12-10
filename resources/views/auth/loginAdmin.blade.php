@include('layout.layout')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 px-4">
    <div class="w-full max-w-md bg-white/10 backdrop-blur-md shadow-xl border border-white/20 rounded-2xl p-8">

        <h2 class="text-3xl font-semibold text-white text-center mb-6">
            Login Admin
        </h2>

        @if(session('error'))
            <div class="mb-4 p-3 rounded-lg bg-red-500/20 text-red-300 text-sm border border-red-400/30">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 p-3 rounded-lg bg-red-500/20 text-red-300 text-sm border border-red-400/30">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.post') }}">
            @csrf
            {{-- Name --}}
            <label class="block text-white/90 mb-1 font-medium">Nama Admin</label>
            <input type="text" name="name" value="{{ old('name') }}"
                class="w-full mb-4 p-3 rounded-lg bg-white/10 text-white placeholder-white/70 border @error('name') border-red-500 @else border-white/20 @enderror focus:border-blue-400 focus:ring-2 focus:ring-blue-500"
                placeholder="Masukkan nama admin..." required>
            @error('name')
                <p class="text-red-400 text-sm mb-4">{{ $message }}</p>
            @enderror

            {{-- Password --}}
            <label class="block text-white/90 mb-1 font-medium">Password</label>
            <input type="password" name="password"
                class="w-full mb-4 p-3 rounded-lg bg-white/10 text-white placeholder-white/70 border @error('password') border-red-500 @else border-white/20 @enderror focus:border-blue-400 focus:ring-2 focus:ring-blue-500"
                placeholder="Masukkan password..." required>
            @error('password')
                <p class="text-red-400 text-sm mb-4">{{ $message }}</p>
            @enderror

            {{-- Button --}}
            <button type="submit"
                class="w-full bg-amber-600 hover:bg-amber-700 text-white font-medium py-3 rounded-lg transition">
                Login Admin
            </button>

            <!-- {{-- Register Link --}}
            <p class="text-center text-white/70 text-sm mt-4">
                Belum terdaftar?
                <a href="{{ route('admin.register.show') }}" class="text-amber-400 hover:underline">
                    Daftar admin
                </a>
            </p> -->

        </form>
    </div>
</div>
