@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')

<h4 class="mb-4 fw-semibold">Smart Waste Monitoring</h4>

<!-- SUMMARY CARDS -->
<div class="row mb-4">

    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body d-flex justify-content-between align-items-center border-start border-4 border-primary">
                <div>
                    <small class="text-muted">Total Tempat Sampah</small>
                    <h3 id="total" class="mb-0">{{ $total }}</h3>
                </div>
                <div class="fs-1 text-primary">🗑️</div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body d-flex justify-content-between align-items-center border-start border-4 border-warning">
                <div>
                    <small class="text-muted">Hampir Penuh</small>
                    <h3 id="hampir" class="mb-0">{{ $hampir }}</h3>
                </div>
                <div class="fs-1 text-warning">⚠️</div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body d-flex justify-content-between align-items-center border-start border-4 border-danger">
                <div>
                    <small class="text-muted">Notifikasi Aktif</small>
                    <h3 id="notif" class="mb-0">{{ $notifBelumDibaca }}</h3>
                </div>
                <div class="fs-1 text-danger">🔔</div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body d-flex justify-content-between align-items-center border-start border-4 border-success">
                <div>
                    <small class="text-muted">Total Petugas</small>
                    <h3 class="mb-0">{{ $petugas }}</h3>
                </div>
                <div class="fs-1 text-success">👷</div>
            </div>
        </div>
    </div>

</div>

<!-- STATUS & CHART -->
<div class="row mb-4">

    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white fw-semibold">
                Ringkasan Status Tempat Sampah
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Kosong</span>
                        <strong id="kosong" class="text-success">{{ $kosong }}</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Hampir Penuh</span>
                        <strong class="text-warning">{{ $hampir }}</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Penuh</span>
                        <strong id="penuh" class="text-danger">{{ $penuh }}</strong>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white fw-semibold">
                Diagram Status Tempat Sampah
            </div>
            <div class="card-body d-flex justify-content-center align-items-center">
                <div style="width: 100%; max-width: 320px;">
                    <canvas id="statusChart" height="220"></canvas>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- NOTIFIKASI TERBARU -->
<div class="card shadow-sm border-0">
    <div class="card-header bg-white fw-semibold">
        Notifikasi Terbaru
    </div>

    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Pesan</th>
                    <th>Waktu</th>
                </tr>
            </thead>
            <tbody>
                @forelse($notifikasiBaru as $n)
                    <tr>
                        <td>{{ $n->pesan }}</td>
                        <td class="text-muted">{{ $n->created_at->diffForHumans() }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="text-center text-muted py-4">
                            Tidak ada notifikasi
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>



<!-- CHART JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('statusChart');

    const statusChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Kosong', 'Hampir Penuh', 'Penuh'],
            datasets: [{
                data: [{{ $kosong }}, {{ $hampir }}, {{ $penuh }}],
                backgroundColor: ['#198754','#ffc107','#dc3545'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 14,
                        padding: 12
                    }
                }
            }
        }
    });

    let toastSudahMuncul = false;

    async function fetchRealtimeData() {
        try {
            const res = await fetch('/admin/dashboard/realtime');
            const data = await res.json();

            document.getElementById('total').innerText = data.total;
            document.getElementById('kosong').innerText = data.kosong;
            document.getElementById('hampir').innerText = data.hampir;
            document.getElementById('penuh').innerText = data.penuh;
            document.getElementById('notif').innerText = data.notif;

            statusChart.data.datasets[0].data = [
                data.kosong,
                data.hampir,
                data.penuh
            ];
            statusChart.update();

            // 🔔 TOAST ALERT LOGIC
            if (data.penuh > 0 && !toastSudahMuncul) {
                const toastEl = document.getElementById('toastAlert');
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

    setInterval(fetchRealtimeData, 5000);
</script>



<!-- TOAST NOTIFICATION -->
<div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">
    <div id="toastAlert" class="toast align-items-center text-bg-danger border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body">
                ⚠️ Ada tempat sampah yang penuh!
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>


@endsection
