<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\TempatSampah;

class DashboardController extends Controller
{
    public function index()
    {
        return view('petugas.dashboard', [
            'total' => TempatSampah::count(),
            'kosong' => TempatSampah::where('status','kosong')->count(),
            'hampir' => TempatSampah::where('status','hampir_penuh')->count(),
            'penuh' => TempatSampah::where('status','penuh')->count(),
            'data' => TempatSampah::all(),
        ]);
    }
}
