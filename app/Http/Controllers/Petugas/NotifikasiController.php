<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $notifikasi = $user->notifikasis()->latest()->get();

        foreach ($notifikasi as $n) {
            $n->pivot->update(['dibaca' => true]);
        }

        return view('petugas.notifikasi', compact('notifikasi'));
    }
}
