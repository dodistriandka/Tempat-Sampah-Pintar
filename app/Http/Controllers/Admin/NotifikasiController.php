<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $notifikasi = $user->notifikasis()->latest()->get();

        // tandai hanya milik user ini sebagai dibaca
        foreach ($notifikasi as $n) {
            $n->pivot->update(['dibaca' => true]);
        }

        return view('admin.notifikasi', compact('notifikasi'));
    }
}
