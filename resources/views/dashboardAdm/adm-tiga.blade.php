@extends('layout.layout')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900">
    <div class="p-8">
        <h1 class="text-4xl font-bold text-white mb-8">Dashboard Admin - Bukittinggi</h1>
        
        <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-8">
            <p class="text-white/80">Selamat datang, {{ Auth::guard('admin')->user()->name }}</p>
            <p class="text-white/60 mt-2">Region: {{ Auth::guard('admin')->user()->region }}</p>
        </div>
    </div>
</div>
@endsection