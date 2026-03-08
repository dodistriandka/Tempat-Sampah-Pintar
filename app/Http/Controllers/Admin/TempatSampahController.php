<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TempatSampah;
use Illuminate\Http\Request;
use App\Models\Notifikasi;
use App\Models\User;


class TempatSampahController extends Controller
{
    public function index()
    {
        $data = TempatSampah::all();
        return view('admin.tempat_sampah.index', compact('data'));
    }

    public function create()
    {
        return view('admin.tempat_sampah.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_tempat' => 'required|unique:tempat_sampahs',
            'lokasi' => 'required',
            'kapasitas_cm' => 'required|integer',
        ]);

        TempatSampah::create($request->all());

        return redirect()->route('admin.tempat-sampah.index')
            ->with('success', 'Tempat sampah berhasil ditambahkan');
    }

    public function edit($id)
    {
        $data = TempatSampah::findOrFail($id);
        return view('admin.tempat_sampah.edit', compact('data'));
    }

   public function update(Request $request, $id)
{
    $data = TempatSampah::findOrFail($id);

    $request->validate([
        'kode_tempat' => 'required|unique:tempat_sampahs,kode_tempat,' . $id,
        'lokasi' => 'required',
        'kapasitas_cm' => 'required|integer',
        'status' => 'required',
    ]);

    $statusLama = $data->status;

    $data->update($request->all());

    // 🔔 NOTIFIKASI PER USER
    if ($statusLama !== 'penuh' && $data->status === 'penuh') {

        $notif = Notifikasi::create([
            'tempat_sampah_id' => $data->id,
            'pesan' => "Tempat sampah {$data->kode_tempat} di {$data->lokasi} sudah PENUH",
        ]);

        $users = User::whereIn('role', ['admin', 'petugas'])->get();

        foreach ($users as $user) {
            $notif->users()->attach($user->id, ['dibaca' => false]);
        }
    }

    return redirect()->route('admin.tempat-sampah.index')
        ->with('success', 'Data berhasil diupdate');
}


    public function destroy($id)
{
    $data = \App\Models\TempatSampah::findOrFail($id);

    $data->delete();

    return redirect()->route('admin.tempat-sampah.index')
        ->with('success', 'Data tempat sampah berhasil dihapus');
}


}
