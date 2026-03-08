<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| ADMIN CONTROLLERS
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\TempatSampahController;
use App\Http\Controllers\Admin\NotifikasiController as AdminNotifikasi;

/*
|--------------------------------------------------------------------------
| PETUGAS CONTROLLERS
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Petugas\DashboardController as PetugasDashboard;
use App\Http\Controllers\Petugas\NotifikasiController as PetugasNotifikasi;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTE
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->group(function () {

    // 📊 Dashboard Admin
    Route::get('/admin/dashboard', [AdminDashboard::class, 'index'])
        ->name('admin.dashboard');

    // 🔄 REALTIME DATA DASHBOARD (JSON)
    Route::get('/admin/dashboard/realtime', function (\Illuminate\Http\Request $request) {

        $user = $request->user();

        return response()->json([
            'total'  => \App\Models\TempatSampah::count(),
            'kosong' => \App\Models\TempatSampah::where('status', 'kosong')->count(),
            'hampir' => \App\Models\TempatSampah::where('status', 'hampir_penuh')->count(),
            'penuh'  => \App\Models\TempatSampah::where('status', 'penuh')->count(),
            'notif'  => $user->notifikasis()
                ->wherePivot('dibaca', false)
                ->count(),
        ]);
    });

Route::get('/admin/users/{id}/edit', [UserController::class, 'edit'])
    ->name('admin.users.edit');

Route::put('/admin/users/{id}', [UserController::class, 'update'])
    ->name('admin.users.update');

Route::delete('/admin/users/{id}', [UserController::class, 'destroy'])
    ->name('admin.users.destroy');




    // 🔔 Notifikasi Admin
    Route::get('/admin/notifikasi', [AdminNotifikasi::class, 'index'])
        ->name('admin.notifikasi');

    // 👥 Kelola Petugas
    Route::get('/admin/users', [UserController::class, 'index'])
        ->name('admin.users.index');
    Route::get('/admin/users/create', [UserController::class, 'create'])
        ->name('admin.users.create');
    Route::post('/admin/users', [UserController::class, 'store'])
        ->name('admin.users.store');

    // 🗑️ CRUD Tempat Sampah
    Route::get('/admin/tempat-sampah', [TempatSampahController::class, 'index'])
        ->name('admin.tempat-sampah.index');
    Route::get('/admin/tempat-sampah/create', [TempatSampahController::class, 'create'])
        ->name('admin.tempat-sampah.create');
    Route::post('/admin/tempat-sampah', [TempatSampahController::class, 'store'])
        ->name('admin.tempat-sampah.store');
    Route::get('/admin/tempat-sampah/{id}/edit', [TempatSampahController::class, 'edit'])
        ->name('admin.tempat-sampah.edit');
    Route::put('/admin/tempat-sampah/{id}', [TempatSampahController::class, 'update'])
        ->name('admin.tempat-sampah.update');
    Route::delete('/admin/tempat-sampah/{id}', [TempatSampahController::class, 'destroy'])
        ->name('admin.tempat-sampah.destroy');
});

/*
|--------------------------------------------------------------------------
| PETUGAS ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:petugas'])->group(function () {

    // 👷 Dashboard Petugas
    Route::get('/petugas/dashboard', [PetugasDashboard::class, 'index'])
        ->name('petugas.dashboard');

    Route::get('/petugas/dashboard/realtime', function () {
        return response()->json([
            'total'  => \App\Models\TempatSampah::count(),
            'kosong' => \App\Models\TempatSampah::where('status', 'kosong')->count(),
            'hampir' => \App\Models\TempatSampah::where('status', 'hampir_penuh')->count(),
            'penuh'  => \App\Models\TempatSampah::where('status', 'penuh')->count(),
            'data'   => \App\Models\TempatSampah::select('id','kode_tempat','lokasi','status')->get(),
        ]);
    });

    // 🔔 Notifikasi Petugas
    Route::get('/petugas/notifikasi', [PetugasNotifikasi::class, 'index'])
        ->name('petugas.notifikasi');
});

/*
|--------------------------------------------------------------------------
| PROFILE (SEMUA USER LOGIN)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
