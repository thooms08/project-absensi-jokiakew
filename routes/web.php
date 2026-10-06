<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DataGuruController;
use App\Http\Controllers\DataMuridController;
use App\Http\Controllers\MapelController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\JadwalMengajarController;
use App\Http\Controllers\AbsenGuruController;
use App\Http\Controllers\AbsenMuridController;
use App\Http\Controllers\KodeAbsenController;

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

        Route::get('/absen-guru',                  [AbsenGuruController::class, 'adminIndex'])->name('absen_guru');
        Route::get('/absen-guru/list',             [AbsenGuruController::class, 'adminList'])->name('absen_guru.list');
        Route::get('/absen-guru/detail/{userId}',  [AbsenGuruController::class, 'adminDetailGuru'])->name('absen_guru.detail');
        Route::get('/absen-guru/foto/{userId}',    [AbsenGuruController::class, 'adminFotoGuru'])->name('absen_guru.foto');
        Route::get('/absen-guru/serve/{id}',       [AbsenGuruController::class, 'fotoServe'])->name('absen_guru.foto_serve');
        Route::get('/absen-guru/notif/count',      [AbsenGuruController::class, 'absenNotifCount'])->name('absen_guru.notif_count');
        Route::get('/absen-guru/notif/list',       [AbsenGuruController::class, 'absenNotifList'])->name('absen_guru.notif_list');
        Route::post('/absen-guru/notif/{id}/konfirmasi', [AbsenGuruController::class, 'konfirmasi'])->name('absen_guru.notif_konfirmasi');

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

        Route::get('/absen-murid',                  [AbsenMuridController::class, 'adminIndex'])->name('absen_murid');
        Route::get('/absen-murid/list',             [AbsenMuridController::class, 'adminList'])->name('absen_murid.list');
        Route::get('/absen-murid/detail/{userId}',  [AbsenMuridController::class, 'adminDetailMurid'])->name('absen_murid.detail');

        /* -- Mata Pelajaran -- */
        Route::get('/mapel',            [MapelController::class, 'index'])->name('mapel');
        Route::get('/mapel/list',       [MapelController::class, 'list'])->name('mapel.list');
        Route::post('/mapel',           [MapelController::class, 'store'])->name('mapel.store');
        Route::get('/mapel/{id}/edit',  [MapelController::class, 'edit'])->name('mapel.edit');
        Route::post('/mapel/{id}',      [MapelController::class, 'update'])->name('mapel.update');
        Route::delete('/mapel/{id}',    [MapelController::class, 'destroy'])->name('mapel.destroy');

        /* -- Kelas -- */
        Route::get('/kelas',                               [KelasController::class, 'index'])->name('kelas');
        Route::get('/kelas/list',                          [KelasController::class, 'list'])->name('kelas.list');
        Route::get('/kelas/semua-guru',                    [KelasController::class, 'semuaGuru'])->name('kelas.semua_guru');
        Route::post('/kelas',                              [KelasController::class, 'store'])->name('kelas.store');
        Route::get('/kelas/{uuid}',                        [KelasController::class, 'detail'])->name('kelas.detail');
        Route::get('/kelas/{id}/edit',                     [KelasController::class, 'edit'])->name('kelas.edit');
        Route::post('/kelas/{id}',                         [KelasController::class, 'update'])->name('kelas.update');
        Route::delete('/kelas/{id}',                       [KelasController::class, 'destroy'])->name('kelas.destroy');
        Route::get('/kelas/{id}/guru-list',                [KelasController::class, 'guruList'])->name('kelas.guru_list');
        Route::get('/kelas/{id}/murid-list',               [KelasController::class, 'muridList'])->name('kelas.murid_list');
        Route::post('/kelas/{id}/tambah-guru',             [KelasController::class, 'tambahGuru'])->name('kelas.tambah_guru');
        Route::post('/kelas/{id}/tambah-murid',            [KelasController::class, 'tambahMurid'])->name('kelas.tambah_murid');
        Route::delete('/kelas/{id}/hapus-peserta',         [KelasController::class, 'hapusPeserta'])->name('kelas.hapus_peserta');

        /* -- Jadwal Mengajar -- */
        Route::get('/jadwal-mengajar',                [JadwalMengajarController::class, 'index'])->name('jadwal_mengajar');
        Route::get('/jadwal-mengajar/list',           [JadwalMengajarController::class, 'list'])->name('jadwal_mengajar.list');
        Route::get('/jadwal-mengajar/dropdown',       [JadwalMengajarController::class, 'dropdownData'])->name('jadwal_mengajar.dropdown');
        Route::get('/jadwal-mengajar/edit-guru',      [JadwalMengajarController::class, 'editGuru'])->name('jadwal_mengajar.edit_guru');
        Route::post('/jadwal-mengajar',               [JadwalMengajarController::class, 'store'])->name('jadwal_mengajar.store');
        Route::post('/jadwal-mengajar/multi',         [JadwalMengajarController::class, 'storeMulti'])->name('jadwal_mengajar.store_multi');
        Route::delete('/jadwal-mengajar/bulk',        [JadwalMengajarController::class, 'destroyBulk'])->name('jadwal_mengajar.destroy_bulk');
        Route::get('/jadwal-mengajar/{id}/edit',      [JadwalMengajarController::class, 'edit'])->name('jadwal_mengajar.edit');
        Route::post('/jadwal-mengajar/{id}',          [JadwalMengajarController::class, 'update'])->name('jadwal_mengajar.update');
        Route::patch('/jadwal-mengajar/{id}/sesi',    [JadwalMengajarController::class, 'updateSesi'])->name('jadwal_mengajar.update_sesi');
        Route::delete('/jadwal-mengajar/{id}',        [JadwalMengajarController::class, 'destroy'])->name('jadwal_mengajar.destroy');
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

        Route::get('/absen-guru',               [AbsenGuruController::class, 'index'])->name('absen_guru');
        Route::get('/absen-guru/kartu',          [AbsenGuruController::class, 'kartu'])->name('absen_guru.kartu');
        Route::post('/absen-guru',               [AbsenGuruController::class, 'store'])->name('absen_guru.store');
        Route::get('/absen-guru/status',         [AbsenGuruController::class, 'status'])->name('absen_guru.status');

        Route::get('/absen-murid',                         [AbsenMuridController::class, 'guruPage'])->name('absen_murid');
        Route::get('/absen-murid/notif/count',             [AbsenMuridController::class, 'guruNotifCount'])->name('absen_murid.notif_count');
        Route::get('/absen-murid/notif/list',              [AbsenMuridController::class, 'guruNotifList'])->name('absen_murid.notif_list');
        Route::post('/absen-murid/notif/{id}/konfirmasi',  [AbsenMuridController::class, 'guruKonfirmasi'])->name('absen_murid.notif_konfirmasi');
        Route::get('/kode-absen/jadwal-hari-ini',          [KodeAbsenController::class, 'jadwalHariIni'])->name('kode_absen.jadwal_hari_ini');
        Route::post('/kode-absen/buat',                    [KodeAbsenController::class, 'buat'])->name('kode_absen.buat');
        Route::get('/kode-absen/status',                   [KodeAbsenController::class, 'status'])->name('kode_absen.status');

        Route::get('/jadwal-mengajar',      [JadwalMengajarController::class, 'guruIndex'])->name('jadwal_mengajar');
        Route::get('/jadwal-mengajar/list', [JadwalMengajarController::class, 'guruList'])->name('jadwal_mengajar.list');

        Route::get('/data-kelas',               [KelasController::class, 'guruKelas'])->name('data_kelas');
        Route::get('/data-kelas/list',          [KelasController::class, 'guruKelasList'])->name('data_kelas.list');
        Route::get('/data-kelas/{uuid}',        [KelasController::class, 'guruDetailKelas'])->name('data_kelas.detail');

        Route::get('/data-murid',                [DataMuridController::class, 'guruIndex'])->name('data_murid');
        Route::get('/data-murid/list',           [DataMuridController::class, 'list'])->name('data_murid.list');
        Route::get('/data-murid/check-username', [DataMuridController::class, 'checkUsername'])->name('data_murid.check_username');
        Route::post('/data-murid',               [DataMuridController::class, 'store'])->name('data_murid.store');
        Route::get('/data-murid/{id}/edit',      [DataMuridController::class, 'edit'])->name('data_murid.edit');
        Route::post('/data-murid/{id}',          [DataMuridController::class, 'update'])->name('data_murid.update');
        Route::delete('/data-murid/{id}',        [DataMuridController::class, 'destroy'])->name('data_murid.destroy');
        Route::post('/data-murid/{id}/restore',  [DataMuridController::class, 'restore'])->name('data_murid.restore');
        Route::delete('/data-murid/{id}/force',  [DataMuridController::class, 'forceDelete'])->name('data_murid.force_delete');
    });

/* ====================================================================
   MURID  --  prefix: /murid   middleware: auth + role:murid
==================================================================== */
Route::prefix('murid')
    ->middleware(['auth', 'role:murid'])
    ->name('murid.')
    ->group(function () {
        Route::get('/dashboard',      [AbsenMuridController::class, 'index'])->name('dashboard');
        Route::get('/absen',          [AbsenMuridController::class, 'absenPage'])->name('absen');
        Route::get('/absen/kartu',    [AbsenMuridController::class, 'kartu'])->name('absen.kartu');
        Route::post('/absen',         [AbsenMuridController::class, 'store'])->name('absen.store');
        Route::get('/absen/summary',  [AbsenMuridController::class, 'summary'])->name('absen.summary');
        Route::get('/foto/{tipe}/{filename}', [AbsenMuridController::class, 'fotoServe'])->name('foto_serve');

        Route::get('/riwayat-absen', function () {
            return view('murid.riwayat_absen');
        })->name('riwayat_absen');
    });
