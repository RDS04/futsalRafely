@include('layout.layout')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900">
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-white">Dashboard Member - Padang</h1>
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                    Logout
                </button>
            </form>
        </div>

        <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-6">
            <h2 class="text-2xl font-semibold text-white mb-4">Selamat Datang, {{ Auth::user()->name }}!</h2>
            <p class="text-white/70 mb-4">Region: <span class="font-semibold text-blue-400">{{ Auth::user()->region }}</span></p>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                <div class="bg-blue-500/20 border border-blue-400/30 rounded-lg p-4">
                    <h3 class="text-white font-semibold mb-2">Email</h3>
                    <p class="text-white/70">{{ Auth::user()->email }}</p>
                </div>
                <div class="bg-blue-500/20 border border-blue-400/30 rounded-lg p-4">
                    <h3 class="text-white font-semibold mb-2">No. Telepon</h3>
                    <p class="text-white/70">{{ Auth::user()->phone }}</p>
                </div>
                <div class="bg-blue-500/20 border border-blue-400/30 rounded-lg p-4">
                    <h3 class="text-white font-semibold mb-2">Jenis Kelamin</h3>
                    <p class="text-white/70">{{ Auth::user()->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                </div>
            </div>

            <div class="mt-6 bg-blue-500/10 border border-blue-400/20 rounded-lg p-4">
                <h3 class="text-white font-semibold mb-2">Alamat</h3>
                <p class="text-white/70">{{ Auth::user()->address }}</p>
            </div>
        </div>
    </div>
</div>
