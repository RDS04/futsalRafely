@extends('layouts.app')
@section('content')

<div class="content-header">
    <div class="container-fluid">
        <h1 class="m-0">Tambah Lapangan Futsal</h1>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Form Data Lapangan</h3>
            </div>
            @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            <form action="{{ route('lapangan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                @csrf
                <div class="card-body">

                    {{-- NAMA LAPANGAN --}}
                    <div class="form-group">
                        <label>Nama Lapangan</label>
                        <input type="text" name="namaLapangan"
                            class="form-control"
                            placeholder="Contoh: Lapangan A"
                            required>
                    </div>
                    {{-- JENIS LAPANGAN --}}
                    <div class="form-group">
                        <label>Jenis Lapangan</label>
                        <input type="text" name="jenisLapangan"
                            class="form-control"
                            placeholder="Contoh: Vinyl, Rumput Sintetis, Parquet"
                            required>
                    </div>

                    {{-- HARGA --}}
                    <div class="form-group">
                        <label>Harga per Jam</label>
                        <input type="number" name="harga"
                            class="form-control"
                            placeholder="Contoh: 150000"
                            required>
                    </div>

                    {{-- STATUS --}}
                    <div class="form-group">
                        <label>Status Lapangan</label>
                        <select name="status" class="form-control" required>
                            <option value="tersedia">Tersedia</option>
                            <option value="tidak tersedia">Tidak Tersedia</option>
                        </select>
                    </div>

                    {{-- KETERANGAN --}}
                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea name="deskripsi"
                            class="form-control"
                            rows="3"
                            placeholder="Opsional"></textarea>
                    </div>
                    {{-- GAMBAR --}}
                    <div class="form-group">
                        <label>Gambar</label>
                        <input type="file" name="gambar" onchange="previewImage(event)">
                        <img id="preview" class="mt-2" width="200">

                        <script>
                            function previewImage(event) {
                                const img = document.getElementById('preview');
                                img.src = URL.createObjectURL(event.target.files[0]);
                            }
                        </script>


                    </div>

                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-primary">
                            Simpan Data
                        </button>
                        <a href="{{ route('inputLapangan.padang') }}" class="btn btn-secondary">
                            Kembali
                        </a>
                    </div>
            </form>
        </div>
    </div>
</div>
@endsection