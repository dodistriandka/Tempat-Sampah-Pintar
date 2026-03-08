@extends('layouts.admin')

@section('title', 'Data Tempat Sampah')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-semibold mb-0">Data Tempat Sampah</h4>

    <a href="{{ route('admin.tempat-sampah.create') }}"
       class="btn btn-primary btn-sm">
        + Tambah Tempat Sampah
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Kode</th>
                    <th>Lokasi</th>
                    <th>Kapasitas (cm)</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $index => $d)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $d->kode_tempat }}</td>
                        <td>{{ $d->lokasi }}</td>
                        <td>{{ $d->kapasitas_cm }}</td>
                        <td>
                            @if($d->status == 'kosong')
                                <span class="badge bg-success">Kosong</span>
                            @elseif($d->status == 'hampir_penuh')
                                <span class="badge bg-warning text-dark">Hampir Penuh</span>
                            @else
                                <span class="badge bg-danger">Penuh</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.tempat-sampah.edit', $d->id) }}"
                               class="btn btn-sm btn-warning">
                                Edit
                            </a>

                            <form action="{{ route('admin.tempat-sampah.destroy', $d->id) }}"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            Belum ada data tempat sampah
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
