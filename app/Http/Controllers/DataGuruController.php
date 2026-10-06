<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Verifikasi;
use App\Models\Mapel;
use App\Models\GuruMapel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class DataGuruController extends Controller
{
    /* ----------------------------------------------------------------
       SHOW REGISTER  –  Halaman pendaftaran mandiri guru
    ---------------------------------------------------------------- */
    public function showRegister()
    {
        return view('register.guru');
    }

    /* ----------------------------------------------------------------
       REGISTER  –  Proses pendaftaran mandiri guru
    ---------------------------------------------------------------- */
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'username' => ['required', 'string', 'max:60', 'unique:users,username', 'regex:/^[a-zA-Z0-9_.\-]+$/'],
            'password' => ['required', 'confirmed', Password::min(8)->numbers()],
            'image'    => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
        ], [
            'username.regex' => 'Username hanya boleh mengandung huruf, angka, titik, underscore, dan dash.',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $file     = $request->file('image');
            $filename = uniqid('guru_') . '.' . $file->getClientOriginalExtension();
            $file->storeAs('assets/profile-guru', $filename, 'public');
            $imagePath = 'assets/profile-guru/' . $filename;
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role'     => 'guru',
            'image'    => $imagePath,
        ]);

        Verifikasi::create([
            'user_id' => $user->id,
            'status'  => 'pending',
            'role'    => 'guru',
        ]);

        return response()->json(['success' => true, 'message' => 'Pendaftaran berhasil.']);
    }

    /* ----------------------------------------------------------------
       INDEX  –  Halaman utama Data Guru
    ---------------------------------------------------------------- */
    public function index()
    {
        return view('admin.data_guru');
    }

    /* ----------------------------------------------------------------
       LIST  –  Partial AJAX: daftar guru (aktif / nonaktif)
    ---------------------------------------------------------------- */
    public function list(Request $request)
    {
        $search = trim($request->get('search', ''));
        $tab    = $request->get('tab', 'aktif');

        $query = ($tab === 'nonaktif')
            ? User::onlyTrashed()->where('role', 'guru')
            : User::where('role', 'guru');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name',     'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email',   'like', "%{$search}%");
            });
        }

        $guru = $query->with('mapel')->orderBy('name')->get();

        return view('admin.partials.list_data_guru', compact('guru', 'tab'));
    }

    /* ----------------------------------------------------------------
       CHECK USERNAME  –  AJAX polling ketersediaan username
    ---------------------------------------------------------------- */
    public function checkUsername(Request $request)
    {
        $username = trim($request->get('username', ''));

        if ($username === '') {
            return response()->json(['available' => false, 'message' => 'Username tidak boleh kosong.']);
        }

        $exists = User::withTrashed()->where('username', $username)->exists();

        return response()->json([
            'available' => ! $exists,
            'message'   => $exists ? 'Username sudah digunakan.' : 'Username tersedia.',
        ]);
    }

    /* ----------------------------------------------------------------
       STORE  –  Simpan guru baru via admin (modal +Guru, tanpa verifikasi)
    ---------------------------------------------------------------- */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'username' => ['required', 'string', 'max:60', 'unique:users,username', 'regex:/^[a-zA-Z0-9_.\-]+$/'],
            'password' => ['required', 'confirmed', Password::min(8)->numbers()],
            'image'    => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
        ], [
            'username.regex' => 'Username hanya boleh mengandung huruf, angka, titik, underscore, dan dash.',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $file      = $request->file('image');
            $filename  = uniqid('guru_') . '.' . $file->getClientOriginalExtension();
            $file->storeAs('assets/profile-guru', $filename, 'public');
            $imagePath = 'assets/profile-guru/' . $filename;
        }

        $guru = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role'     => 'guru',
            'image'    => $imagePath,
        ]);

        return response()->json(['success' => true, 'message' => 'Data guru berhasil ditambahkan.', 'guru_id' => $guru->id]);
    }

    /* ----------------------------------------------------------------
       EDIT  –  Ambil data guru untuk form edit
    ---------------------------------------------------------------- */
    public function edit($id)
    {
        $guru = User::where('role', 'guru')->findOrFail($id);

        return response()->json([
            'id'       => $guru->id,
            'name'     => $guru->name,
            'email'    => $guru->email,
            'username' => $guru->username,
            'image'    => $guru->image ? asset('storage/' . $guru->image) : null,
        ]);
    }

    /* ----------------------------------------------------------------
       UPDATE  –  Simpan perubahan data guru
    ---------------------------------------------------------------- */
    public function update(Request $request, $id)
    {
        $guru = User::where('role', 'guru')->findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => "required|email|unique:users,email,{$id}",
            'username' => ["required", "string", "max:60", "unique:users,username,{$id}", "regex:/^[a-zA-Z0-9_.\-]+$/"],
            'image'    => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
        ], [
            'username.regex' => 'Username hanya boleh mengandung huruf, angka, titik, underscore, dan dash.',
        ]);

        if ($request->hasFile('image')) {
            if ($guru->image && Storage::disk('public')->exists($guru->image)) {
                Storage::disk('public')->delete($guru->image);
            }
            $file      = $request->file('image');
            $filename  = uniqid('guru_') . '.' . $file->getClientOriginalExtension();
            $file->storeAs('assets/profile-guru', $filename, 'public');
            $guru->image = 'assets/profile-guru/' . $filename;
        }

        $guru->name     = $request->name;
        $guru->email    = $request->email;
        $guru->username = $request->username;

        if ($request->filled('password')) {
            $request->validate([
                'password' => [Password::min(8)->numbers()],
            ]);
            $guru->password = Hash::make($request->password);
        }

        $guru->save();

        return response()->json(['success' => true, 'message' => 'Data guru berhasil diperbarui.']);
    }

    /* ----------------------------------------------------------------
       DESTROY  –  Soft-delete guru
    ---------------------------------------------------------------- */
    public function destroy($id)
    {
        $guru = User::where('role', 'guru')->findOrFail($id);
        $guru->delete();

        return response()->json(['success' => true, 'message' => 'Guru berhasil dinonaktifkan.']);
    }

    /* ----------------------------------------------------------------
       RESTORE  –  Pulihkan guru yang sudah di-softdelete
    ---------------------------------------------------------------- */
    public function restore($id)
    {
        $guru = User::onlyTrashed()->where('role', 'guru')->findOrFail($id);
        $guru->restore();

        return response()->json(['success' => true, 'message' => 'Guru berhasil diaktifkan kembali.']);
    }

    /* ----------------------------------------------------------------
       FORCE DELETE  –  Hapus permanen
    ---------------------------------------------------------------- */
    public function forceDelete($id)
    {
        $guru = User::onlyTrashed()->where('role', 'guru')->findOrFail($id);

        if ($guru->image && Storage::disk('public')->exists($guru->image)) {
                Storage::disk('public')->delete($guru->image);
            }

        $guru->forceDelete();

        return response()->json(['success' => true, 'message' => 'Data guru dihapus permanen.']);
    }

    /* ----------------------------------------------------------------
       SEMUA MAPEL  –  Daftar semua mapel (tanpa konteks guru)
    ---------------------------------------------------------------- */
    public function semuaMapel()
    {
        $semua = Mapel::orderBy('nama_mapel')->get(['id', 'nama_mapel']);
        return response()->json(['semua_mapel' => $semua]);
    }

    /* ----------------------------------------------------------------
       GET MAPEL  –  Ambil mapel yang sudah dipilih + semua mapel
    ---------------------------------------------------------------- */
    public function getMapel($id)
    {
        $guru = User::where('role', 'guru')->findOrFail($id);

        $semuaMapel   = Mapel::orderBy('nama_mapel')->get(['id', 'nama_mapel']);
        $mapelDipilih = GuruMapel::where('user_id', $id)->pluck('mapel_id')->toArray();

        return response()->json([
            'semua_mapel'   => $semuaMapel,
            'mapel_dipilih' => $mapelDipilih,
        ]);
    }

    /* ----------------------------------------------------------------
       SET MAPEL  –  Simpan mapel guru (replace semua)
    ---------------------------------------------------------------- */
    public function setMapel(Request $request, $id)
    {
        $request->validate([
            'mapel_ids'   => 'nullable|array',
            'mapel_ids.*' => 'exists:mapel,id',
        ]);

        User::where('role', 'guru')->findOrFail($id);

        /* Hapus semua mapel lama, ganti dengan yang baru */
        GuruMapel::where('user_id', $id)->delete();

        $mapelIds = $request->input('mapel_ids', []);
        foreach ($mapelIds as $mapelId) {
            GuruMapel::create(['user_id' => $id, 'mapel_id' => $mapelId]);
        }

        return response()->json([
            'success' => true,
            'message' => count($mapelIds) > 0
                ? count($mapelIds) . ' mata pelajaran berhasil disimpan.'
                : 'Mata pelajaran guru berhasil dikosongkan.',
        ]);
    }

    /* ================================================================
       NOTIFIKASI VERIFIKASI
    ================================================================ */

    /* ----------------------------------------------------------------
       NOTIF COUNT  –  Jumlah pengajuan pending (untuk badge lonceng)
    ---------------------------------------------------------------- */
    public function notifCount()
    {
        $count = Verifikasi::pending()->forRole('guru')->count();
        return response()->json(['count' => $count]);
    }

    /* ----------------------------------------------------------------
       NOTIF LIST  –  Daftar pengajuan pending
    ---------------------------------------------------------------- */
    public function notifList()
    {
        $list = Verifikasi::with('user')
            ->pending()
            ->forRole('guru')
            ->latest()
            ->take(20)
            ->get()
            ->map(function ($v) {
                return [
                    'id'         => $v->id,
                    'user_id'    => $v->user_id,
                    'name'       => $v->user->name    ?? '-',
                    'email'      => $v->user->email   ?? '-',
                    'username'   => $v->user->username ?? '-',
                    'created_at' => $v->created_at->diffForHumans(),
                    'avatar'     => strtoupper(mb_substr($v->user->name ?? 'G', 0, 1)),
                ];
            });

        return response()->json(['list' => $list]);
    }

    /* ----------------------------------------------------------------
       NOTIF APPROVE  –  Setujui pengajuan
    ---------------------------------------------------------------- */
    public function notifApprove($id)
    {
        $verifikasi = Verifikasi::pending()->forRole('guru')->findOrFail($id);
        $verifikasi->update(['status' => 'success']);

        return response()->json(['success' => true, 'message' => 'Pendaftaran guru disetujui.']);
    }

    /* ----------------------------------------------------------------
       NOTIF REJECT  –  Tolak pengajuan, hapus user
    ---------------------------------------------------------------- */
    public function notifReject($id)
    {
        $verifikasi = Verifikasi::pending()->forRole('guru')->findOrFail($id);

        $user = User::find($verifikasi->user_id);
        if ($user) {
            if ($user->image && Storage::disk('public')->exists($user->image)) {
                Storage::disk('public')->delete($user->image);
            }
            $user->forceDelete();
        }

        $verifikasi->update(['status' => 'fail']);

        return response()->json(['success' => true, 'message' => 'Pendaftaran guru ditolak.']);
    }

}
