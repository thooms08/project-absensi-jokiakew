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
       LIST  –  Partial AJAX: daftar mapel
    ---------------------------------------------------------------- */
    public function list(Request $request)
    {
        $search = trim($request->get('search', ''));

        $query = Mapel::query();

        if ($search !== '') {
            $query->where('nama_mapel', 'like', "%{$search}%");
        }

        $mapel = $query->orderBy('nama_mapel')->get();

        return view('admin.partials.list_mapel', compact('mapel'));
    }

    /* ----------------------------------------------------------------
       STORE  –  Simpan mapel baru
    ---------------------------------------------------------------- */
    public function store(Request $request)
    {
        $request->validate([
            'nama_mapel' => 'required|string|max:255|unique:mapel,nama_mapel',
        ]);

        Mapel::create(['nama_mapel' => $request->nama_mapel]);

        return response()->json(['success' => true, 'message' => 'Mata pelajaran berhasil ditambahkan.']);
    }

    /* ----------------------------------------------------------------
       EDIT  –  Ambil data mapel untuk form edit
    ---------------------------------------------------------------- */
    public function edit($id)
    {
        $mapel = Mapel::findOrFail($id);

        return response()->json([
            'id'         => $mapel->id,
            'nama_mapel' => $mapel->nama_mapel,
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
