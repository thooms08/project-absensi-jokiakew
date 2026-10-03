<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\PesertaKelas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KelasController extends Controller
{
    /* ----------------------------------------------------------------
       INDEX  –  Halaman utama Kelas
    ---------------------------------------------------------------- */
    public function index()
    {
        return view('admin.kelas');
    }

    /* ----------------------------------------------------------------
       LIST  –  Partial AJAX: daftar kelas
    ---------------------------------------------------------------- */
    public function list(Request $request)
    {
        $search = trim($request->get('search', ''));

        $query = Kelas::with(['waliKelas', 'guru', 'murid']);

        if ($search !== '') {
            $query->where('nama_kelas', 'like', "%{$search}%");
        }

        $kelas = $query->orderBy('nama_kelas')->get();

        return view('admin.partials.list_kelas', compact('kelas'));
    }

    /* ----------------------------------------------------------------
       STORE  –  Simpan kelas baru
    ---------------------------------------------------------------- */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:100|unique:kelas,nama_kelas',
            'wali_id'    => 'nullable|exists:users,id',
        ]);

        $kelas = Kelas::create([
            'uuid'       => (string) Str::uuid(),
            'nama_kelas' => $request->nama_kelas,
        ]);

        /* Simpan wali kelas jika dipilih */
        if ($request->filled('wali_id')) {
            PesertaKelas::firstOrCreate(
                ['user_id' => $request->wali_id, 'kelas_id' => $kelas->id],
                ['label'   => 'wali_kelas']
            );
        }

        return response()->json(['success' => true, 'message' => 'Kelas berhasil ditambahkan.']);
    }

    /* ----------------------------------------------------------------
       EDIT  –  Ambil data kelas untuk form edit
    ---------------------------------------------------------------- */
    public function edit($id)
    {
        $kelas = Kelas::with('waliKelas.user')->findOrFail($id);

        return response()->json([
            'id'         => $kelas->id,
            'nama_kelas' => $kelas->nama_kelas,
            'wali_id'    => $kelas->waliKelas?->user_id,
            'wali_nama'  => $kelas->waliKelas?->user?->name,
        ]);
    }

    /* ----------------------------------------------------------------
       UPDATE  –  Simpan perubahan kelas
    ---------------------------------------------------------------- */
    public function update(Request $request, $id)
    {
        $kelas = Kelas::findOrFail($id);

        $request->validate([
            'nama_kelas' => "required|string|max:100|unique:kelas,nama_kelas,{$id}",
            'wali_id'    => 'nullable|exists:users,id',
        ]);

        $kelas->update(['nama_kelas' => $request->nama_kelas]);

        /* Update wali kelas: hapus lama, set baru */
        PesertaKelas::where('kelas_id', $id)
                    ->where('label', 'wali_kelas')
                    ->delete();

        if ($request->filled('wali_id')) {
            /* Jika user sudah masuk sebagai anggota kelas, ubah labelnya */
            $peserta = PesertaKelas::where('kelas_id', $id)
                                   ->where('user_id', $request->wali_id)
                                   ->first();
            if ($peserta) {
                $peserta->update(['label' => 'wali_kelas']);
            } else {
                PesertaKelas::create([
                    'user_id'  => $request->wali_id,
                    'kelas_id' => $id,
                    'label'    => 'wali_kelas',
                ]);
            }
        }

        return response()->json(['success' => true, 'message' => 'Kelas berhasil diperbarui.']);
    }

    /* ----------------------------------------------------------------
       DESTROY  –  Hapus kelas beserta pesertanya
    ---------------------------------------------------------------- */
    public function destroy($id)
    {
        $kelas = Kelas::findOrFail($id);
        /* Peserta kelas ikut terhapus via cascadeOnDelete di migrasi */
        $kelas->delete();

        return response()->json(['success' => true, 'message' => 'Kelas berhasil dihapus.']);
    }

    /* ----------------------------------------------------------------
       DETAIL  –  Halaman detail kelas (guru + murid)
    ---------------------------------------------------------------- */
    public function detail($id)
    {
        $kelas = Kelas::with([
            'peserta.user.mapel',
        ])->findOrFail($id);

        return view('admin.detail_kelas', compact('kelas'));
    }

    /* ----------------------------------------------------------------
       GURU LIST (AJAX)  –  Semua guru yang belum masuk kelas ini
    ---------------------------------------------------------------- */
    public function guruList(Request $request, $id)
    {
        $search = trim($request->get('search', ''));

        /* ID guru yang sudah ada di kelas ini */
        $sudahAda = PesertaKelas::where('kelas_id', $id)->pluck('user_id');

        $query = User::where('role', 'guru')
                     ->whereNotIn('id', $sudahAda);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        $guru = $query->orderBy('name')->get(['id', 'name', 'username', 'image']);

        return response()->json(['data' => $guru]);
    }

    /* ----------------------------------------------------------------
       MURID LIST (AJAX)  –  Semua murid yang belum masuk kelas ini
    ---------------------------------------------------------------- */
    public function muridList(Request $request, $id)
    {
        $search = trim($request->get('search', ''));

        $sudahAda = PesertaKelas::where('kelas_id', $id)->pluck('user_id');

        $query = User::where('role', 'murid')
                     ->whereNotIn('id', $sudahAda);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        $murid = $query->orderBy('name')->get(['id', 'name', 'username', 'image']);

        return response()->json(['data' => $murid]);
    }

    /* ----------------------------------------------------------------
       SEMUA GURU (AJAX)  –  Untuk pilih wali kelas saat buat/edit
    ---------------------------------------------------------------- */
    public function semuaGuru(Request $request)
    {
        $search = trim($request->get('search', ''));

        $query = User::where('role', 'guru');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        $guru = $query->orderBy('name')->get(['id', 'name', 'username']);

        return response()->json(['data' => $guru]);
    }

    /* ----------------------------------------------------------------
       TAMBAH GURU KE KELAS  –  support bulk (array user_ids)
    ---------------------------------------------------------------- */
    public function tambahGuru(Request $request, $id)
    {
        $request->validate([
            'user_ids'   => 'required|array|min:1',
            'user_ids.*' => 'exists:users,id',
        ]);

        Kelas::findOrFail($id);

        $sudahAda = PesertaKelas::where('kelas_id', $id)->pluck('user_id')->toArray();
        $added    = 0;

        foreach ($request->user_ids as $userId) {
            if (!in_array($userId, $sudahAda)) {
                PesertaKelas::create([
                    'user_id'  => $userId,
                    'kelas_id' => $id,
                    'label'    => 'guru_kelas',
                ]);
                $sudahAda[] = $userId;
                $added++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => $added . ' guru berhasil ditambahkan ke kelas.',
        ]);
    }

    /* ----------------------------------------------------------------
       TAMBAH MURID KE KELAS  –  support bulk (array user_ids)
    ---------------------------------------------------------------- */
    public function tambahMurid(Request $request, $id)
    {
        $request->validate([
            'user_ids'   => 'required|array|min:1',
            'user_ids.*' => 'exists:users,id',
        ]);

        Kelas::findOrFail($id);

        $sudahAda = PesertaKelas::where('kelas_id', $id)->pluck('user_id')->toArray();
        $added    = 0;

        foreach ($request->user_ids as $userId) {
            if (!in_array($userId, $sudahAda)) {
                PesertaKelas::create([
                    'user_id'  => $userId,
                    'kelas_id' => $id,
                    'label'    => 'murid',
                ]);
                $sudahAda[] = $userId;
                $added++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => $added . ' murid berhasil ditambahkan ke kelas.',
        ]);
    }

    /* ----------------------------------------------------------------
       HAPUS PESERTA DARI KELAS
    ---------------------------------------------------------------- */
    public function hapusPeserta(Request $request, $id)
    {
        $request->validate([
            'peserta_id' => 'required|exists:peserta_kelas,id',
        ]);

        $peserta = PesertaKelas::where('id', $request->peserta_id)
                               ->where('kelas_id', $id)
                               ->firstOrFail();

        $peserta->delete();

        return response()->json(['success' => true, 'message' => 'Anggota berhasil dihapus dari kelas.']);
    }
}
