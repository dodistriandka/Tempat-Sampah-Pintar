<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TempatSampah;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        return view('admin.dashboard', [
            'total' => TempatSampah::count(),
            'kosong' => TempatSampah::where('status','kosong')->count(),
            'hampir' => TempatSampah::where('status','hampir_penuh')->count(),
            'penuh' => TempatSampah::where('status','penuh')->count(),
            'petugas' => User::where('role','petugas')->count(),

            // 🔔 data untuk dashboard modern
            'notifikasiBaru' => $user->notifikasis()
                ->latest()
                ->take(5)
                ->get(),

            'notifBelumDibaca' => $user->notifikasis()
                ->wherePivot('dibaca', false)
                ->count(),
        ]);
    }
}
