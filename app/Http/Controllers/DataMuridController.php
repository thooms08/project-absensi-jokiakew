<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Verifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class DataMuridController extends Controller
{
    /* ----------------------------------------------------------------
       SHOW REGISTER  –  Halaman pendaftaran mandiri murid
    ---------------------------------------------------------------- */
    public function showRegister()
    {
        return view('register.murid');
    }

    /* ----------------------------------------------------------------
       REGISTER  –  Proses pendaftaran mandiri murid
    ---------------------------------------------------------------- */
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'username' => 'required|string|max:60|unique:users,username|alpha_dash',
            'password' => ['required', 'confirmed', Password::min(8)->numbers()],
            'image'    => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $file     = $request->file('image');
            $filename = uniqid('murid_') . '.' . $file->getClientOriginalExtension();
            $file->storeAs('assets/profile-murid', $filename, 'public');
            $imagePath = 'assets/profile-murid/' . $filename;
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role'     => 'murid',
            'image'    => $imagePath,
        ]);

        /* Buat entri verifikasi pending untuk notifikasi admin */
        Verifikasi::create([
            'user_id' => $user->id,
            'status'  => 'pending',
            'role'    => 'murid',
        ]);

        return response()->json(['success' => true, 'message' => 'Pendaftaran berhasil.']);
    }

    /* ----------------------------------------------------------------
       INDEX  –  Halaman utama Data Murid
    ---------------------------------------------------------------- */
    public function index()
    {
        return view('admin.data_murid');
    }

    /* ----------------------------------------------------------------
       LIST  –  Partial AJAX: daftar murid (aktif / nonaktif)
    ---------------------------------------------------------------- */
    public function list(Request $request)
    {
        $search = trim($request->get('search', ''));
        $tab    = $request->get('tab', 'aktif');

        $query = ($tab === 'nonaktif')
            ? User::onlyTrashed()->where('role', 'murid')
            : User::where('role', 'murid');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name',     'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email',   'like', "%{$search}%");
            });
        }

        $murid = $query->orderBy('name')->get();

        return view('admin.partials.list_data_murid', compact('murid', 'tab'));
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
       STORE  –  Simpan murid baru via admin
    ---------------------------------------------------------------- */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'username' => 'required|string|max:60|unique:users,username|alpha_dash',
            'password' => ['required', 'confirmed', Password::min(8)->numbers()],
            'image'    => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $file      = $request->file('image');
            $filename  = uniqid('murid_') . '.' . $file->getClientOriginalExtension();
            $file->storeAs('assets/profile-murid', $filename, 'public');
            $imagePath = 'assets/profile-murid/' . $filename;
        }

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role'     => 'murid',
            'image'    => $imagePath,
        ]);

        return response()->json(['success' => true, 'message' => 'Data murid berhasil ditambahkan.']);
    }

    /* ----------------------------------------------------------------
       EDIT  –  Ambil data murid untuk form edit
    ---------------------------------------------------------------- */
    public function edit($id)
    {
        $murid = User::where('role', 'murid')->findOrFail($id);

        return response()->json([
            'id'       => $murid->id,
            'name'     => $murid->name,
            'email'    => $murid->email,
            'username' => $murid->username,
            'image'    => $murid->image ? asset('storage/' . $murid->image) : null,
        ]);
    }

    /* ----------------------------------------------------------------
       UPDATE  –  Simpan perubahan data murid
    ---------------------------------------------------------------- */
    public function update(Request $request, $id)
    {
        $murid = User::where('role', 'murid')->findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => "required|email|unique:users,email,{$id}",
            'username' => "required|string|max:60|unique:users,username,{$id}|alpha_dash",
            'image'    => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($murid->image && Storage::disk('public')->exists($murid->image)) {
                Storage::disk('public')->delete($murid->image);
            }
            $file      = $request->file('image');
            $filename  = uniqid('murid_') . '.' . $file->getClientOriginalExtension();
            $file->storeAs('assets/profile-murid', $filename, 'public');
            $murid->image = 'assets/profile-murid/' . $filename;
        }

        $murid->name     = $request->name;
        $murid->email    = $request->email;
        $murid->username = $request->username;

        if ($request->filled('password')) {
            $request->validate([
                'password' => ['confirmed', Password::min(8)->numbers()],
            ]);
            $murid->password = Hash::make($request->password);
        }

        $murid->save();

        return response()->json(['success' => true, 'message' => 'Data murid berhasil diperbarui.']);
    }

    /* ----------------------------------------------------------------
       DESTROY  –  Soft-delete murid
    ---------------------------------------------------------------- */
    public function destroy($id)
    {
        $murid = User::where('role', 'murid')->findOrFail($id);
        $murid->delete();

        return response()->json(['success' => true, 'message' => 'Murid berhasil dinonaktifkan.']);
    }

    /* ----------------------------------------------------------------
       RESTORE  –  Pulihkan murid yang sudah di-softdelete
    ---------------------------------------------------------------- */
    public function restore($id)
    {
        $murid = User::onlyTrashed()->where('role', 'murid')->findOrFail($id);
        $murid->restore();

        return response()->json(['success' => true, 'message' => 'Murid berhasil diaktifkan kembali.']);
    }

    /* ----------------------------------------------------------------
       FORCE DELETE  –  Hapus permanen
    ---------------------------------------------------------------- */
    public function forceDelete($id)
    {
        $murid = User::onlyTrashed()->where('role', 'murid')->findOrFail($id);

        if ($murid->image && Storage::disk('public')->exists($murid->image)) {
            Storage::disk('public')->delete($murid->image);
        }

        $murid->forceDelete();

        return response()->json(['success' => true, 'message' => 'Data murid dihapus permanen.']);
    }

    /* ================================================================
       NOTIFIKASI VERIFIKASI MURID
    ================================================================ */

    public function notifCount()
    {
        $count = Verifikasi::pending()->forRole('murid')->count();
        return response()->json(['count' => $count]);
    }

    public function notifList()
    {
        $list = Verifikasi::with('user')
            ->pending()
            ->forRole('murid')
            ->latest()
            ->take(20)
            ->get()
            ->map(function ($v) {
                return [
                    'id'         => $v->id,
                    'user_id'    => $v->user_id,
                    'name'       => $v->user->name     ?? '-',
                    'email'      => $v->user->email    ?? '-',
                    'username'   => $v->user->username ?? '-',
                    'created_at' => $v->created_at->diffForHumans(),
                    'avatar'     => strtoupper(mb_substr($v->user->name ?? 'M', 0, 1)),
                ];
            });

        return response()->json(['list' => $list]);
    }

    public function notifApprove($id)
    {
        $verifikasi = Verifikasi::pending()->forRole('murid')->findOrFail($id);
        $verifikasi->update(['status' => 'success']);

        return response()->json(['success' => true, 'message' => 'Pendaftaran murid disetujui.']);
    }

    public function notifReject($id)
    {
        $verifikasi = Verifikasi::pending()->forRole('murid')->findOrFail($id);

        $user = User::find($verifikasi->user_id);
        if ($user) {
            if ($user->image && Storage::disk('public')->exists($user->image)) {
                Storage::disk('public')->delete($user->image);
            }
            $user->forceDelete();
        }

        $verifikasi->update(['status' => 'fail']);

        return response()->json(['success' => true, 'message' => 'Pendaftaran murid ditolak.']);
    }
}
