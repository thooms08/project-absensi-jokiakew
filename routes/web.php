<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DataGuruController;
use App\Http\Controllers\DataMuridController;
use App\Http\Controllers\MapelController;
use App\Http\Controllers\KelasController;

/* ====================================================================
   HALAMAN UTAMA
==================================================================== */
Route::get('/', function () {
    return view('index');
})->name('home');

/* ====================================================================
   AUTH (tamu saja)
==================================================================== */
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/captcha/refresh', [AuthController::class, 'refreshCaptcha'])->name('captcha.refresh');
});

/* Registrasi mandiri guru  –  publik */
Route::get('/register/guru',  [DataGuruController::class, 'showRegister'])->name('register.guru');
Route::post('/register/guru', [DataGuruController::class, 'register'])->name('register.guru.post');
Route::get('/register/guru/check-username', [DataGuruController::class, 'checkUsername'])->name('register.guru.check_username');

/* Registrasi mandiri murid  –  publik */
Route::get('/register/murid',  [DataMuridController::class, 'showRegister'])->name('register.murid');
Route::post('/register/murid', [DataMuridController::class, 'register'])->name('register.murid.post');
Route::get('/register/murid/check-username', [DataMuridController::class, 'checkUsername'])->name('register.murid.check_username');

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/* ====================================================================
   ADMIN  --  prefix: /admin   middleware: auth + role:admin
==================================================================== */
Route::prefix('admin')
    ->middleware(['auth', 'role:admin'])
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.index');
        })->name('dashboard');

        Route::get('/profile', function () {
            return view('admin.profile');
        })->name('profile');

        /* -- Data Guru -- */
        Route::get('/data-guru',                [DataGuruController::class, 'index'])->name('data_guru');
        Route::get('/data-guru/list',           [DataGuruController::class, 'list'])->name('data_guru.list');
        Route::get('/data-guru/check-username', [DataGuruController::class, 'checkUsername'])->name('data_guru.check_username');
        Route::post('/data-guru',               [DataGuruController::class, 'store'])->name('data_guru.store');
        Route::get('/data-guru/{id}/edit',      [DataGuruController::class, 'edit'])->name('data_guru.edit');
        Route::post('/data-guru/{id}',          [DataGuruController::class, 'update'])->name('data_guru.update');
        Route::delete('/data-guru/{id}',        [DataGuruController::class, 'destroy'])->name('data_guru.destroy');
        Route::post('/data-guru/{id}/restore',  [DataGuruController::class, 'restore'])->name('data_guru.restore');
        Route::delete('/data-guru/{id}/force',  [DataGuruController::class, 'forceDelete'])->name('data_guru.force_delete');
        Route::get('/data-guru/{id}/mapel',     [DataGuruController::class, 'getMapel'])->name('data_guru.get_mapel');
        Route::post('/data-guru/{id}/mapel',    [DataGuruController::class, 'setMapel'])->name('data_guru.set_mapel');
        Route::get('/data-guru/semua-mapel',    [DataGuruController::class, 'semuaMapel'])->name('data_guru.semua_mapel');

        /* -- Notifikasi Verifikasi Guru -- */
        Route::get('/notif/count',             [DataGuruController::class, 'notifCount'])->name('notif.count');
        Route::get('/notif/list',              [DataGuruController::class, 'notifList'])->name('notif.list');
        Route::post('/notif/{id}/approve',     [DataGuruController::class, 'notifApprove'])->name('notif.approve');
        Route::post('/notif/{id}/reject',      [DataGuruController::class, 'notifReject'])->name('notif.reject');

        /* -- Notifikasi Verifikasi Murid -- */
        Route::get('/notif-murid/count',              [DataMuridController::class, 'notifCount'])->name('notif_murid.count');
        Route::get('/notif-murid/list',               [DataMuridController::class, 'notifList'])->name('notif_murid.list');
        Route::post('/notif-murid/{id}/approve',      [DataMuridController::class, 'notifApprove'])->name('notif_murid.approve');
        Route::post('/notif-murid/{id}/reject',       [DataMuridController::class, 'notifReject'])->name('notif_murid.reject');

        Route::get('/absen-guru', function () {
            return view('admin.absen_guru');
        })->name('absen_guru');

        /* -- Data Murid -- */
        Route::get('/data-murid',                [DataMuridController::class, 'index'])->name('data_murid');
        Route::get('/data-murid/list',           [DataMuridController::class, 'list'])->name('data_murid.list');
        Route::get('/data-murid/check-username', [DataMuridController::class, 'checkUsername'])->name('data_murid.check_username');
        Route::post('/data-murid',               [DataMuridController::class, 'store'])->name('data_murid.store');
        Route::get('/data-murid/{id}/edit',      [DataMuridController::class, 'edit'])->name('data_murid.edit');
        Route::post('/data-murid/{id}',          [DataMuridController::class, 'update'])->name('data_murid.update');
        Route::delete('/data-murid/{id}',        [DataMuridController::class, 'destroy'])->name('data_murid.destroy');
        Route::post('/data-murid/{id}/restore',  [DataMuridController::class, 'restore'])->name('data_murid.restore');
        Route::delete('/data-murid/{id}/force',  [DataMuridController::class, 'forceDelete'])->name('data_murid.force_delete');

        Route::get('/absen-murid', function () {
            return view('admin.absen_murid');
        })->name('absen_murid');

        /* -- Mata Pelajaran -- */
        Route::get('/mapel',            [MapelController::class, 'index'])->name('mapel');
        Route::get('/mapel/list',       [MapelController::class, 'list'])->name('mapel.list');
        Route::post('/mapel',           [MapelController::class, 'store'])->name('mapel.store');
        Route::get('/mapel/{id}/edit',  [MapelController::class, 'edit'])->name('mapel.edit');
        Route::post('/mapel/{id}',      [MapelController::class, 'update'])->name('mapel.update');
        Route::delete('/mapel/{id}',    [MapelController::class, 'destroy'])->name('mapel.destroy');

        /* -- Kelas -- */
        Route::get('/kelas',                           [KelasController::class, 'index'])->name('kelas');
        Route::get('/kelas/list',                      [KelasController::class, 'list'])->name('kelas.list');
        Route::get('/kelas/semua-guru',                [KelasController::class, 'semuaGuru'])->name('kelas.semua_guru');
        Route::post('/kelas',                          [KelasController::class, 'store'])->name('kelas.store');
        Route::get('/kelas/{id}',                      [KelasController::class, 'detail'])->name('kelas.detail');
        Route::get('/kelas/{id}/edit',                 [KelasController::class, 'edit'])->name('kelas.edit');
        Route::post('/kelas/{id}',                     [KelasController::class, 'update'])->name('kelas.update');
        Route::delete('/kelas/{id}',                   [KelasController::class, 'destroy'])->name('kelas.destroy');
        Route::get('/kelas/{id}/guru-list',            [KelasController::class, 'guruList'])->name('kelas.guru_list');
        Route::get('/kelas/{id}/murid-list',           [KelasController::class, 'muridList'])->name('kelas.murid_list');
        Route::post('/kelas/{id}/tambah-guru',         [KelasController::class, 'tambahGuru'])->name('kelas.tambah_guru');
        Route::post('/kelas/{id}/tambah-murid',        [KelasController::class, 'tambahMurid'])->name('kelas.tambah_murid');
        Route::delete('/kelas/{id}/hapus-peserta',     [KelasController::class, 'hapusPeserta'])->name('kelas.hapus_peserta');
    });

/* ====================================================================
   GURU  --  prefix: /guru   middleware: auth + role:guru
==================================================================== */
Route::prefix('guru')
    ->middleware(['auth', 'role:guru'])
    ->name('guru.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('guru.index');
        })->name('dashboard');

        Route::get('/profile', function () {
            return view('guru.profile');
        })->name('profile');

        Route::get('/absen-guru', function () {
            return view('guru.absen_guru');
        })->name('absen_guru');

        Route::get('/absen-murid', function () {
            return view('guru.absen_murid');
        })->name('absen_murid');

        Route::get('/jadwal-mengajar', function () {
            return view('guru.jadwal_mengajar');
        })->name('jadwal_mengajar');

        Route::get('/data-kelas', function () {
            return view('guru.data_kelas');
        })->name('data_kelas');

        Route::get('/data-murid', function () {
            return view('guru.data_murid');
        })->name('data_murid');
    });

/* ====================================================================
   MURID  --  prefix: /murid   middleware: auth + role:murid
==================================================================== */
Route::prefix('murid')
    ->middleware(['auth', 'role:murid'])
    ->name('murid.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('murid.index');
        })->name('dashboard');

        Route::get('/absen', function () {
            return view('murid.absen');
        })->name('absen');

        Route::get('/riwayat-absen', function () {
            return view('murid.riwayat_absen');
        })->name('riwayat_absen');
    });
