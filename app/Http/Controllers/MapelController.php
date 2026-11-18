<?php

namespace App\Http\Controllers;

use App\Models\Mapel;
use Illuminate\Http\Request;

class MapelController extends Controller
{
    /* ----------------------------------------------------------------
       INDEX  –  Halaman utama Mata Pelajaran
    ---------------------------------------------------------------- */
    public function index()
    {
        return view('admin.mapel');
    }

    /* ----------------------------------------------------------------
       LIST  –  Partial AJAX: daftar mapel per jenis sekolah admin
    ---------------------------------------------------------------- */
    public function list(Request $request)
    {
        $search = trim($request->get('search', ''));
        $jenis  = session('admin_jenis_sekolah', 'smk');

        $query = Mapel::jenisSekolah($jenis);

        if ($search !== '') {
            $query->where('nama_mapel', 'like', "%{$search}%");
        }

        $mapel = $query->orderBy('nama_mapel')->get();

        return view('admin.partials.list_mapel', compact('mapel'));
    }

    /* ----------------------------------------------------------------
       STORE  –  Simpan mapel baru dengan jenis sekolah
    ---------------------------------------------------------------- */
    public function store(Request $request)
    {
        $jenis = session('admin_jenis_sekolah', 'smk');

        $request->validate([
            'nama_mapel' => 'required|string|max:255',
        ]);

        /* Pastikan nama mapel unique per jenis sekolah */
        $exists = Mapel::where('nama_mapel', $request->nama_mapel)
                       ->where('jenis_sekolah', $jenis)
                       ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Mapel ' . $request->nama_mapel . ' sudah ada untuk ' . strtoupper($jenis) . '.',
            ], 422);
        }

        Mapel::create([
            'nama_mapel'    => $request->nama_mapel,
            'jenis_sekolah' => $jenis,
        ]);

        return response()->json(['success' => true, 'message' => 'Mata pelajaran berhasil ditambahkan.']);
    }

    /* ----------------------------------------------------------------
       EDIT  –  Ambil data mapel untuk form edit
    ---------------------------------------------------------------- */
    public function edit($id)
    {
        $mapel = Mapel::findOrFail($id);

        return response()->json([
            'id'             => $mapel->id,
            'nama_mapel'     => $mapel->nama_mapel,
            'jenis_sekolah'  => $mapel->jenis_sekolah,
        ]);
    }

    /* ----------------------------------------------------------------
       UPDATE  –  Simpan perubahan mapel
    ---------------------------------------------------------------- */
    public function update(Request $request, $id)
    {
        $mapel = Mapel::findOrFail($id);

        $request->validate([
            'nama_mapel' => "required|string|max:255|unique:mapel,nama_mapel,{$id}",
        ]);

        $mapel->update(['nama_mapel' => $request->nama_mapel]);

        return response()->json(['success' => true, 'message' => 'Mata pelajaran berhasil diperbarui.']);
    }

    /* ----------------------------------------------------------------
       DESTROY  –  Hapus mapel
    ---------------------------------------------------------------- */
    public function destroy($id)
    {
        $mapel = Mapel::findOrFail($id);
        $mapel->delete();

        return response()->json(['success' => true, 'message' => 'Mata pelajaran berhasil dihapus.']);
    }
}
