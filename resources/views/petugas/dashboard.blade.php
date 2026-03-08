@extends('layouts.petugas')

@section('title', 'Dashboard Petugas')

@section('content')

<h4 class="mb-4 fw-semibold">Monitoring Tempat Sampah</h4>

<!-- SUMMARY -->
<div class="row mb-4">

    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body border-start border-4 border-success">
                <small class="text-muted">Kosong</small>
                <h4 id="kosong" class="mb-0 text-success">{{ $kosong }}</h4>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body border-start border-4 border-warning">
                <small class="text-muted">Hampir Penuh</small>
                <h4 id="hampir" class="mb-0 text-warning">{{ $hampir }}</h4>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body border-start border-4 border-danger">
                <small class="text-muted">Penuh</small>
                <h4 id="penuh" class="mb-0 text-danger">{{ $penuh }}</h4>
            </div>
        </div>
    </div>

</div>

<!-- TABLE -->
<div class="card shadow-sm border-0">
    <div class="card-header bg-white fw-semibold">
        Daftar Tempat Sampah
    </div>

    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Kode</th>
                    <th>Lokasi</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                @forelse($data as $index => $d)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $d->kode_tempat }}</td>
                        <td>{{ $d->lokasi }}</td>
                        <td>
                            @if($d->status == 'kosong')
                                <span class="badge bg-success">Kosong</span>
                            @elseif($d->status == 'hampir_penuh')
                                <span class="badge bg-warning text-dark">Hampir Penuh</span>
                            @else
                                <span class="badge bg-danger">Penuh</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">
                            Tidak ada data
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- TOAST NOTIFICATION -->
<div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">
    <div id="toastPetugas" class="toast align-items-center text-bg-danger border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body">
                ⚠️ Ada tempat sampah yang penuh! Segera lakukan pengangkutan.
            </div>
            <button type="button"
                    class="btn-close btn-close-white me-2 m-auto"
                    data-bs-dismiss="toast">
            </button>
        </div>
    </div>
</div>

<!-- REALTIME SCRIPT -->
<script>
let toastSudahMuncul = false;

async function fetchRealtimePetugas() {
    try {
        const res = await fetch('/petugas/dashboard/realtime');
        const data = await res.json();

        // Update summary
        document.getElementById('kosong').innerText = data.kosong;
        document.getElementById('hampir').innerText = data.hampir;
        document.getElementById('penuh').innerText = data.penuh;

        // Update table
        let rows = '';
        data.data.forEach((item, index) => {

            let badge = '';
            if (item.status === 'kosong') {
                badge = '<span class="badge bg-success">Kosong</span>';
            } else if (item.status === 'hampir_penuh') {
                badge = '<span class="badge bg-warning text-dark">Hampir Penuh</span>';
            } else {
                badge = '<span class="badge bg-danger">Penuh</span>';
            }

            rows += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${item.kode_tempat}</td>
                    <td>${item.lokasi}</td>
                    <td>${badge}</td>
                </tr>
            `;
        });

        document.getElementById('tableBody').innerHTML = rows;

        // 🔔 Toast logic
        if (data.penuh > 0 && !toastSudahMuncul) {
            const toastEl = document.getElementById('toastPetugas');
            const toast = new bootstrap.Toast(toastEl);
            toast.show();
            toastSudahMuncul = true;
        }

        if (data.penuh === 0) {
            toastSudahMuncul = false;
        }

    } catch (e) {
        console.error('Realtime error:', e);
    }
}

setInterval(fetchRealtimePetugas, 5000);
</script>

@endsection
