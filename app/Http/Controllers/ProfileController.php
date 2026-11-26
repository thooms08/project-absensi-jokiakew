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
            $filename = $this->simpanLogoPublic($request->logo_base64);
            if ($filename) {
                /* Hapus logo lama dari public jika ada */
                $this->hapusLogoPublic($sekolah->logo);
                $sekolah->logo = $filename;
            }
        }

        $sekolah->save();

        return response()->json([
            'success'      => true,
            'message'      => 'Profil sekolah berhasil diperbarui.',
            'logo_url'     => $sekolah->logo
                ? asset('uploads/logo/' . $sekolah->logo)
                : null,
            'nama_sekolah' => $sekolah->nama_sekolah,
        ]);
    }

    /* ================================================================
       GURU — Halaman profile
    ================================================================ */

    public function showGuru()
    {
        return view('guru.profile');
    }

    /* ----------------------------------------------------------------
       UPDATE PROFILE GURU  –  name, username, password, foto
    ---------------------------------------------------------------- */
    public function updateGuru(Request $request)
    {
        $guru = Auth::user();

        $rules = [
            'name'     => 'required|string|max:255',
            'username' => [
                'required', 'string', 'max:60',
                "unique:users,username,{$guru->id}",
                'regex:/^[a-zA-Z0-9_.\-]+$/',
            ],
        ];

        $messages = [
            'username.regex' => 'Username hanya boleh mengandung huruf, angka, titik, underscore, dan dash.',
        ];

        if ($request->filled('password')) {
            $rules['password']              = ['required', 'string', Password::min(8)->numbers()];
            $rules['password_confirmation'] = 'required|same:password';
            $messages['password_confirmation.same'] = 'Konfirmasi password tidak cocok.';
        }

        $request->validate($rules, $messages);

        $guru->name     = $request->name;
        $guru->username = $request->username;

        if ($request->filled('password')) {
            $guru->password = Hash::make($request->password);
        }

        if ($request->filled('foto_base64')) {
            $path = $this->simpanFotoBase64(
                $request->foto_base64,
                'profile_guru',
                'guru_' . $guru->id
            );
            if ($path) {
                $this->hapusFotoPrivate($guru->image);
                $guru->image = $path;
            }
        }

        $guru->save();

        return response()->json([
            'success'  => true,
            'message'  => 'Profil berhasil diperbarui.',
            'foto_url' => $guru->image
                ? route('guru.profile.foto', ['filename' => basename($guru->image)])
                : null,
        ]);
    }

    /* ----------------------------------------------------------------
       CHECK USERNAME GURU  –  AJAX uniqueness check
    ---------------------------------------------------------------- */
    public function checkUsernameGuru(Request $request)
    {
        $username  = $request->input('username', '');
        $currentId = Auth::id();

        if (!$username || !preg_match('/^[a-zA-Z0-9_.\-]+$/', $username)) {
            return response()->json(['available' => false, 'message' => 'Format username tidak valid.']);
        }

        $exists = \App\Models\User::where('username', $username)
            ->where('id', '!=', $currentId)
            ->whereIn('role', \App\Models\User::ROLES_GURU)
            ->exists();

        return response()->json([
            'available' => !$exists,
            'message'   => $exists ? 'Username sudah digunakan guru lain.' : 'Username tersedia.',
        ]);
    }

    /* ----------------------------------------------------------------
       SERVE FOTO GURU  –  stream dari storage private
    ---------------------------------------------------------------- */
    public function fotoGuru($filename)
    {
        $path = storage_path('app/private/assets/profile_guru/' . $filename);
        if (!file_exists($path)) abort(404);
        $mime = mime_content_type($path) ?: 'image/jpeg';
        return response()->file($path, ['Content-Type' => $mime]);
    }

    /* ================================================================
       MURID — Halaman profile
    ================================================================ */

    public function showMurid()
    {
        return view('murid.profile');
    }

    /* ----------------------------------------------------------------
       UPDATE PROFILE MURID  –  name, username, password, foto
    ---------------------------------------------------------------- */
    public function updateMurid(Request $request)
    {
        $murid = Auth::user();

        $rules = [
            'name'     => 'required|string|max:255',
            'username' => [
                'required', 'string', 'max:60',
                "unique:users,username,{$murid->id}",
                'regex:/^[a-zA-Z0-9_.\-]+$/',
            ],
        ];

        $messages = [
            'username.regex' => 'Username hanya boleh mengandung huruf, angka, titik, underscore, dan dash.',
        ];

        if ($request->filled('password')) {
            $rules['password']              = ['required', 'string', Password::min(8)->numbers()];
            $rules['password_confirmation'] = 'required|same:password';
            $messages['password_confirmation.same'] = 'Konfirmasi password tidak cocok.';
        }

        $request->validate($rules, $messages);

        $murid->name     = $request->name;
        $murid->username = $request->username;

        if ($request->filled('password')) {
            $murid->password = Hash::make($request->password);
        }

        if ($request->filled('foto_base64')) {
            $path = $this->simpanFotoBase64(
                $request->foto_base64,
                'profile_murid',
                'murid_' . $murid->id
            );
            if ($path) {
                $this->hapusFotoPrivate($murid->image);
                $murid->image = $path;
            }
        }

        $murid->save();

        return response()->json([
            'success'  => true,
            'message'  => 'Profil berhasil diperbarui.',
            'foto_url' => $murid->image
                ? route('murid.profile.foto', ['filename' => basename($murid->image)])
                : null,
        ]);
    }

    /* ----------------------------------------------------------------
       CHECK USERNAME MURID  –  AJAX uniqueness check
    ---------------------------------------------------------------- */
    public function checkUsernameMurid(Request $request)
    {
        $username  = $request->input('username', '');
        $currentId = Auth::id();

        if (!$username || !preg_match('/^[a-zA-Z0-9_.\-]+$/', $username)) {
            return response()->json(['available' => false, 'message' => 'Format username tidak valid.']);
        }

        $exists = \App\Models\User::where('username', $username)
            ->where('id', '!=', $currentId)
            ->whereIn('role', \App\Models\User::ROLES_MURID)
            ->exists();

        return response()->json([
            'available' => !$exists,
            'message'   => $exists ? 'Username sudah digunakan murid lain.' : 'Username tersedia.',
        ]);
    }

    /* ----------------------------------------------------------------
       SERVE FOTO MURID  –  stream dari storage private
    ---------------------------------------------------------------- */
    public function fotoMurid($filename)
    {
        $path = storage_path('app/private/assets/profile_murid/' . $filename);
        if (!file_exists($path)) abort(404);
        $mime = mime_content_type($path) ?: 'image/jpeg';
        return response()->file($path, ['Content-Type' => $mime]);
    }

    /* ================================================================
       PRIVATE HELPERS
    ================================================================ */

    /**
     * Simpan LOGO SEKOLAH dari base64 ke public/uploads/logo/.
     * Kolom DB menyimpan nama file saja (bukan path).
     * Mengembalikan nama file, atau null jika gagal.
     */
    private function simpanLogoPublic(string $base64): ?string
    {
        if (!preg_match('/^data:image\/(\w+);base64,/', $base64, $match)) {
            return null;
        }

        $ext     = strtolower($match[1]) === 'jpeg' ? 'jpg' : strtolower($match[1]);
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        if (!in_array($ext, $allowed)) return null;

        $data = base64_decode(substr($base64, strpos($base64, ',') + 1));
        if (!$data) return null;

        $imageInfo = @getimagesizefromstring($data);
        if ($imageInfo === false) return null;

        $dir = public_path('uploads/logo');
        if (!is_dir($dir)) mkdir($dir, 0755, true);

        $filename = 'logo_sekolah_' . time() . '.' . $ext;
        file_put_contents($dir . '/' . $filename, $data);

        return $filename;   // hanya nama file
    }

    /**
     * Hapus logo lama dari public/uploads/logo/.
     * Menerima nama file (format baru) ATAU path relatif lama (logo_sekolah/nama.jpg).
     */
    private function hapusLogoPublic(?string $value): void
    {
        if (!$value) return;

        // Format baru: hanya nama file
        $filename = basename($value);
        $full     = public_path('uploads/logo/' . $filename);
        if (file_exists($full)) @unlink($full);
    }

    /**
     * Simpan foto profil user dari base64 ke storage/app/private/assets/{folder}/.
     * Mengembalikan path relatif (folder/filename), atau null jika gagal.
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

        $imageInfo = @getimagesizefromstring($data);
        if ($imageInfo === false) return null;

        $dir = storage_path('app/private/assets/' . $folder);
        if (!is_dir($dir)) mkdir($dir, 0755, true);

        $filename = $prefix . '_' . time() . '.' . $ext;
        file_put_contents($dir . '/' . $filename, $data);

        return $folder . '/' . $filename;
    }

    /**
     * Hapus foto profil user dari storage private.
     */
    private function hapusFotoPrivate(?string $relativePath): void
    {
        if (!$relativePath) return;
        $full = storage_path('app/private/assets/' . $relativePath);
        if (file_exists($full)) @unlink($full);
    }
}
