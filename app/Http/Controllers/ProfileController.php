<?php

namespace App\Http\Controllers;

use App\Models\ProfileSekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /* ================================================================
       ADMIN — Halaman profile
    ================================================================ */

    public function showAdmin()
    {
        $sekolah = ProfileSekolah::getInstance();
        return view('admin.profile', compact('sekolah'));
    }

    /* ----------------------------------------------------------------
       UPDATE PROFILE ADMIN  –  name, email, username, password, foto
    ---------------------------------------------------------------- */
    public function updateAdmin(Request $request)
    {
        $admin = Auth::user();

        $rules = [
            'name'     => 'required|string|max:255',
            'email'    => "required|email|unique:users,email,{$admin->id}",
            'username' => [
                'required', 'string', 'max:60',
                "unique:users,username,{$admin->id}",
                'regex:/^[a-zA-Z0-9_.\-]+$/',
            ],
        ];

        $messages = [
            'username.regex' => 'Username hanya boleh mengandung huruf, angka, titik, underscore, dan dash.',
        ];

        /* Validasi password hanya jika diisi */
        if ($request->filled('password')) {
            $rules['password']              = ['required', 'string', Password::min(8)->numbers()];
            $rules['password_confirmation'] = 'required|same:password';
            $messages['password_confirmation.same'] = 'Konfirmasi password tidak cocok.';
        }

        $request->validate($rules, $messages);

        $admin->name     = $request->name;
        $admin->email    = $request->email;
        $admin->username = $request->username;

        if ($request->filled('password')) {
            $admin->password = Hash::make($request->password);
        }

        /* Foto profil (base64 dari preview kamera/upload) */
        if ($request->filled('foto_base64')) {
            $path = $this->simpanFotoBase64(
                $request->foto_base64,
                'profile_admin',
                'admin_' . $admin->id
            );
            if ($path) {
                /* Hapus foto lama jika ada */
                $this->hapusFotoPrivate($admin->image);
                $admin->image = $path;
            }
        }

        $admin->save();

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui.',
            'foto_url'=> $admin->image
                ? route('admin.profile.foto', ['filename' => basename($admin->image)])
                : null,
        ]);
    }

    /* ----------------------------------------------------------------
       SERVE FOTO ADMIN  –  stream dari storage private
    ---------------------------------------------------------------- */
    public function fotoAdmin($filename)
    {
        $path = storage_path('app/private/assets/profile_admin/' . $filename);
        if (!file_exists($path)) abort(404);
        $mime = mime_content_type($path) ?: 'image/jpeg';
        return response()->file($path, ['Content-Type' => $mime]);
    }

    /* ----------------------------------------------------------------
       UPDATE PROFIL SEKOLAH  –  nama_sekolah + logo (base64)
    ---------------------------------------------------------------- */
    public function updateSekolah(Request $request)
    {
        $request->validate([
            'nama_sekolah' => 'required|string|max:255',
        ]);

        $sekolah = ProfileSekolah::getInstance();
        $sekolah->nama_sekolah = $request->nama_sekolah;

        if ($request->filled('logo_base64')) {
            $path = $this->simpanFotoBase64(
                $request->logo_base64,
                'logo_sekolah',
                'logo_sekolah'
            );
            if ($path) {
                $this->hapusFotoPrivate($sekolah->logo);
                $sekolah->logo = $path;
            }
        }

        $sekolah->save();

        return response()->json([
            'success'  => true,
            'message'  => 'Profil sekolah berhasil diperbarui.',
            'logo_url' => $sekolah->logo
                ? route('admin.profile.logo', ['filename' => basename($sekolah->logo)])
                : null,
            'nama_sekolah' => $sekolah->nama_sekolah,
        ]);
    }

    /* ----------------------------------------------------------------
       SERVE LOGO SEKOLAH  –  stream dari storage private
    ---------------------------------------------------------------- */
    public function fotoSekolah($filename)
    {
        $path = storage_path('app/private/assets/logo_sekolah/' . $filename);
        if (!file_exists($path)) abort(404);
        $mime = mime_content_type($path) ?: 'image/png';
        return response()->file($path, ['Content-Type' => $mime]);
    }

    /* ================================================================
       PRIVATE HELPERS
    ================================================================ */

    /**
     * Simpan gambar dari string base64 ke folder private.
     *
     * @param  string $base64   Data URI base64 (data:image/...;base64,...)
     * @param  string $folder   Sub-folder di storage/app/private/assets/
     * @param  string $prefix   Prefix nama file
     * @return string|null      Path relatif (folder/filename) atau null jika gagal
     */
    private function simpanFotoBase64(string $base64, string $folder, string $prefix): ?string
    {
        if (!preg_match('/^data:image\/(\w+);base64,/', $base64, $match)) {
            return null;
        }

        $ext     = strtolower($match[1]) === 'jpeg' ? 'jpg' : strtolower($match[1]);
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        if (!in_array($ext, $allowed)) return null;

        $data = base64_decode(substr($base64, strpos($base64, ',') + 1));
        if (!$data) return null;

        $dir = storage_path('app/private/assets/' . $folder);
        if (!is_dir($dir)) mkdir($dir, 0755, true);

        $filename = $prefix . '_' . time() . '.' . $ext;
        file_put_contents($dir . '/' . $filename, $data);

        return $folder . '/' . $filename;   // relatif terhadap private/assets/
    }

    /**
     * Hapus file foto dari storage private (jika ada).
     *
     * @param  string|null $relativePath  Path relatif (folder/filename)
     */
    private function hapusFotoPrivate(?string $relativePath): void
    {
        if (!$relativePath) return;
        $full = storage_path('app/private/assets/' . $relativePath);
        if (file_exists($full)) @unlink($full);
    }
}
