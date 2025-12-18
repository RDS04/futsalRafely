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
            {{-- Password --}}
            <label class="block text-white/90 mb-1 font-medium">Password</label>

            <div class="relative mb-4">
                <input
                    type="password"
                    name="password"
                    id="password"
                    class="w-full p-3 pr-12 rounded-lg bg-white/10 text-white placeholder-white/70 border border-white/20 focus:border-blue-400 focus:ring-2 focus:ring-blue-500"
                    placeholder="Masukkan password..."
                    required>

                <!-- Eye Button -->
                <button
                    type="button"
                    onclick="togglePassword()"
                    class="absolute inset-y-0 right-3 flex items-center text-white/70 hover:text-white transition">
                    <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5
                     c4.478 0 8.268 2.943 9.542 7
                     -1.274 4.057-5.064 7-9.542 7
                     -4.477 0-8.268-2.943-9.542-7z" />
                    </svg>

                    <svg id="eyeClosed" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13.875 18.825A10.05 10.05 0 0112 19
                     c-4.478 0-8.268-2.943-9.543-7
                     a9.97 9.97 0 012.478-4.132M6.223 6.223
                     A9.956 9.956 0 0112 5
                     c4.478 0 8.268 2.943 9.543 7
                     a9.97 9.97 0 01-4.132 5.41M15 12
                     a3 3 0 00-3-3m0 0
                     a3 3 0 013 3m-3-3
                     L3 3" />
                    </svg>
                </button>
            </div>


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
<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const eyeOpen = document.getElementById('eyeOpen');
        const eyeClosed = document.getElementById('eyeClosed');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeOpen.classList.remove('hidden');
            eyeClosed.classList.add('hidden');
        } else {
            passwordInput.type = 'password';
            eyeOpen.classList.add('hidden');
            eyeClosed.classList.remove('hidden');
        }
    }
</script>