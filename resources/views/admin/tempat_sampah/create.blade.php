@extends('layouts.admin')

@section('title', 'Tambah Tempat Sampah')

@section('content')

<h4 class="mb-4 fw-semibold">Tambah Tempat Sampah</h4>

<div class="card shadow-sm border-0">
    <div class="card-body">

        <form action="{{ route('admin.tempat-sampah.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Kode Tempat</label>
                <input type="text" name="kode_tempat"
                       class="form-control @error('kode_tempat') is-invalid @enderror"
                       value="{{ old('kode_tempat') }}">
                @error('kode_tempat')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Lokasi</label>
                <input type="text" name="lokasi"
                       class="form-control @error('lokasi') is-invalid @enderror"
                       value="{{ old('lokasi') }}">
                @error('lokasi')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Kapasitas (cm)</label>
                <input type="number" name="kapasitas_cm"
                       class="form-control @error('kapasitas_cm') is-invalid @enderror"
                       value="{{ old('kapasitas_cm') }}">
                @error('kapasitas_cm')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.tempat-sampah.index') }}"
               class="btn btn-secondary">Kembali</a>

        </form>

    </div>
</div>

@endsection
