@extends('layouts.admin')

@section('title', 'Edit Tempat Sampah')

@section('content')

<h3>Edit Tempat Sampah</h3>

<form method="POST" action="{{ route('admin.tempat-sampah.update', $data->id) }}">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label>Kode Tempat</label>
        <input class="form-control" name="kode_tempat"
               value="{{ $data->kode_tempat }}" required>
    </div>

    <div class="mb-3">
        <label>Lokasi</label>
        <input class="form-control" name="lokasi"
               value="{{ $data->lokasi }}" required>
    </div>

    <div class="mb-3">
        <label>Kapasitas (cm)</label>
        <input type="number" class="form-control" name="kapasitas_cm"
               value="{{ $data->kapasitas_cm }}" required>
    </div>

    <!-- 🔥 INI YANG PENTING -->
    <div class="mb-3">
        <label>Status</label>
        <select class="form-select" name="status" required>
            <option value="kosong"
                {{ $data->status == 'kosong' ? 'selected' : '' }}>
                Kosong
            </option>
            <option value="hampir_penuh"
                {{ $data->status == 'hampir_penuh' ? 'selected' : '' }}>
                Hampir Penuh
            </option>
            <option value="penuh"
                {{ $data->status == 'penuh' ? 'selected' : '' }}>
                Penuh
            </option>
        </select>
    </div>

    <button class="btn btn-primary">Update</button>
</form>

@endsection
