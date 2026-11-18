# Laporan Audit Keamanan — Aplikasi Absensi SIKIB

**Tanggal Audit:** 18 November 2026  
**Target:** `/home/thomas-lb/kerjaan/joki/akew/absensi`  
**Commit Referensi:** `4ffd035` (worktree dirty — 20 file dimodifikasi)  
**Framework:** Laravel 12.x (PHP 8.2+)  
**Database:** MySQL  
**Profil Audit:** Standard — 6 fase penuh  
**Auditor:** Kiro Security Audit Skill (defensive, source-only, no live probing)

---

## Ringkasan Eksekutif

Audit statis menyeluruh dilakukan terhadap seluruh source code aplikasi absensi sekolah SIKIB. Ditemukan **17 temuan** yang terkonfirmasi dari source code, terdiri dari:

| Tingkat Risiko | Jumlah |
|---|---|
| 🔴 Critical | 1 |
| 🟠 High | 5 |
| 🟡 Medium | 7 |
| 🔵 Low | 4 |
| **Total** | **17** |

**Temuan paling kritis:** Tidak ada rate limiting pada endpoint login memungkinkan brute-force password tanpa hambatan. Dikombinasikan dengan captcha yang sangat lemah (hanya 9 jawaban unik), akun admin dapat dikompromisi dalam hitungan menit.

**Temuan high berikutnya yang penting:** Guru memiliki privilege CRUD penuh pada data murid di seluruh sistem, dan dua IDOR pada proses absensi memungkinkan guru memanipulasi data absensi guru lain.

**Catatan penting:** Semua query Eloquent ORM yang ditemukan menggunakan parameterized binding — tidak ada kerentanan SQL Injection yang terkonfirmasi. Semua output di Blade views menggunakan `{{ }}` yang melakukan HTML escaping otomatis — tidak ada XSS yang terkonfirmasi. CSRF token hadir di semua form.

---

## Metodologi

Audit dilakukan dalam 6 fase sesuai Cloudflare Security Audit Skill:

1. **Reconnaissance** — Pemetaan seluruh route, controller, model, middleware, konfigurasi, dan dependency.
2. **Hunting Waves** — Empat hunter agen paralel mencakup domain: autentikasi/session, file upload/serving, otorisasi/IDOR, dan konfigurasi/informasi sensitif.
3. **Validasi Kandidat** — Setiap kandidat temuan diverifikasi dengan inspeksi source code tambahan dan eksekusi query lokal (read-only, sandbox lokal, tidak ada network eksternal).
4. **Structured Output** — Semua temuan ditulis ke `findings.json` dengan schema terstruktur.
5. **Verifikasi Independen** — Fakta kunci diverifikasi ulang: captcha range (PHP), role enum di DB, tinker di composer.json, ThrottleRequests di routes.
6. **Laporan Ini** — Disusun dari findings.json yang telah diverifikasi.

**Keterbatasan audit ini:**
- Tidak dilakukan pengujian penetrasi aktif terhadap instance yang berjalan.
- Perilaku web server (Nginx/Apache) untuk path traversal tidak dapat diverifikasi tanpa environment yang berjalan.
- Dependency vulnerability scan (`composer audit`) tidak dijalankan — perlu validasi terpisah.
- Kebijakan deployment production (nilai .env aktual) tidak dapat diinspeksi — beberapa temuan bertingkat "needs validation" untuk kondisi tersebut.

---

## Temuan Detail

### 🔴 AUTH-001 — Tidak Ada Rate Limiting pada Endpoint Login
**Tingkat Risiko:** Critical  
**File:** `routes/web.php` | `app/Http/Controllers/AuthController.php`

**Penyebab:**  
Route `POST /login` tidak dilindungi middleware `ThrottleRequests` atau mekanisme lockout apapun. Tidak ada penghitung percobaan gagal, tidak ada delay progresif, tidak ada lockout sementara.

**Dampak:**  
Penyerang dapat melakukan brute-force password tanpa hambatan. Dikombinasikan dengan captcha yang hanya memiliki 9 jawaban unik (AUTH-002) dan user enumeration yang dimungkinkan (AUTH-003), serangan credential stuffing atau dictionary attack dapat berhasil dalam menit.

**Bukti:**
```
# Grep ThrottleRequests di seluruh route dan bootstrap: kosong
routes/web.php: Route::post('/login', [AuthController::class, 'login'])
# Tidak ada ->middleware('throttle:...') pada route ini
```

**Rekomendasi:**  
Tambahkan throttle pada route login:
```php
Route::post('/login', [AuthController::class, 'login'])
    ->name('login.post')
    ->middleware('throttle:5,1');  // 5 percobaan per menit
```
Pertimbangkan juga lockout progresif di controller (menyimpan jumlah gagal di cache Redis/database per IP).

---

### 🟠 AUTH-002 — Captcha Trivial: Hanya 9 Jawaban Unik
**Tingkat Risiko:** High  
**File:** `app/Http/Controllers/AuthController.php` baris 110–113

**Penyebab:**  
Captcha diimplementasi sendiri sebagai penjumlahan dua angka antara 1–5. Kombinasi yang mungkin: `rand(1,5) + rand(1,5)`, menghasilkan 25 soal berbeda tapi hanya **9 jawaban unik** (nilai 2 sampai 10).

**Dampak:**  
Bot cukup mencoba 9 nilai (2–10) secara berurutan untuk memecahkan captcha. Ini efektif menghilangkan perlindungan anti-bot. Eksekusi verifikasi lokal:
```
php: Unique captcha answers: 9 (values: 2,3,4,5,6,7,8,9,10)
```

**Rekomendasi:**  
Gunakan Google reCAPTCHA v3 (invisible) atau hCaptcha. Sebagai perbaikan minimal cepat, perluas ke `rand(10,99) + rand(10,99)` untuk 180 kemungkinan jawaban. Namun solusi image-based atau challenge-based jauh lebih disarankan.

---

### 🟠 AUTHZ-001 — IDOR: Guru Dapat Konfirmasi Absensi Jadwal Guru Lain
**Tingkat Risiko:** High  
**File:** `app/Http/Controllers/AbsenMuridController.php` baris 292–295  
**Route:** `POST /guru/absen-murid/notif/{id}/konfirmasi`

**Penyebab:**  
Method `guruKonfirmasi($id)` mengambil record `AbsenMurid` berdasarkan ID saja tanpa memvalidasi bahwa jadwal terkait diajarkan oleh guru yang sedang login.

```php
// Kode yang bermasalah (baris 292):
$absen = AbsenMurid::findOrFail($id);
$absen->update(['dikonfirmasi_at' => now()]);
```

**Dampak:**  
Guru A dapat mengirim request POST dengan ID absen milik jadwal guru B, mengkonfirmasi absensi yang seharusnya bukan wewenangnya. Ini merusak integritas data konfirmasi kehadiran.

**Rekomendasi:**
```php
$absen = AbsenMurid::whereHas('jadwal', function($q) {
    $q->where('user_id', auth()->id());
})->findOrFail($id);
```

---

### 🟠 AUTHZ-002 — IDOR: Guru Dapat Scan QR ke Jadwal Guru Lain
**Tingkat Risiko:** High  
**File:** `app/Http/Controllers/AbsenMuridController.php` baris 310–313  
**Route:** `POST /guru/absen-murid/scan-qr`

**Penyebab:**  
`guruScanQr()` menerima `jadwal_id` dari request tanpa memverifikasi kepemilikan. Validasi hanya memastikan jadwal ada di database, bukan bahwa jadwal tersebut milik guru yang melakukan request.

**Dampak:**  
Guru A dapat scan QR murid dan mencatatkannya sebagai hadir di jadwal guru B, memalsukan data kehadiran pada mata pelajaran yang berbeda.

**Rekomendasi:**
```php
// Ganti findOrFail dengan:
$jadwal = JadwalMengajar::where('id', $request->jadwal_id)
    ->where('user_id', auth()->id())
    ->firstOrFail();
```

---

### 🟠 AUTHZ-004 — Privilege Escalation: Guru Setara Admin untuk Manajemen Murid
**Tingkat Risiko:** High  
**File:** `routes/web.php` baris 167–175  

**Penyebab:**  
Grup route guru mengekspos operasi `store`, `update`, `destroy`, `restore`, dan `forceDelete` untuk data murid tanpa scope kelas atau sekolah.

```
guru.data_murid.store        POST  /guru/data-murid
guru.data_murid.update       POST  /guru/data-murid/{id}
guru.data_murid.destroy      DELETE /guru/data-murid/{id}
guru.data_murid.restore      POST  /guru/data-murid/{id}/restore
guru.data_murid.force_delete DELETE /guru/data-murid/{id}/force
```

**Dampak:**  
Setiap guru dapat menambah murid baru ke sistem, mengubah password murid (termasuk murid yang bukan di kelasnya), menonaktifkan, dan menghapus permanen akun murid dari seluruh sistem.

**Rekomendasi:**  
Hapus operasi CUD dari grup route guru. Pertahankan hanya `list`, `edit` (read), dan opsional `store` yang dibatasi ke kelas yang diajar. Pindahkan destroy/forceDelete ke admin-only.

---

### 🟠 AUTHZ-005 — Stale Role Literal: Rekap Absensi Guru di Admin Selalu Kosong
**Tingkat Risiko:** High  
**File:** `app/Http/Controllers/AbsenGuruController.php` baris 238, 282, 327

**Penyebab:**  
Setelah migrasi enum role dari `'guru'` ke `'guru_smk'/'guru_smp'`, tiga method di `AbsenGuruController` masih menggunakan literal lama: `where('role', 'guru')`.

**Dampak:**  
Verifikasi DB: `SELECT COUNT(*) FROM users WHERE role='guru'` menghasilkan 0. Semua 14 guru aktif menggunakan role baru. Halaman rekap absensi guru di panel admin selalu menampilkan data kosong — admin tidak dapat memonitor kehadiran guru sama sekali. Ini adalah blind spot operasional dan keamanan yang serius.

**Bukti:**
```
baris 238: $query = \App\Models\User::where('role', 'guru');
baris 282: $guru = \App\Models\User::where('role', 'guru')->findOrFail($userId);
baris 327: $guru = \App\Models\User::where('role', 'guru')->findOrFail($userId);
```

**Rekomendasi:**
```php
// Ganti ketiga lokasi dengan:
whereIn('role', ['guru_smk', 'guru_smp'])
// Lakukan hal sama pada AbsenMuridController::adminList (baris yang menggunakan where('role','murid'))
```

---

### 🟡 AUTH-003 — User Enumeration via Pesan Error Login
**Tingkat Risiko:** Medium  
**File:** `app/Http/Controllers/AuthController.php` baris 48–65

**Penyebab:**  
Login mengembalikan status berbeda untuk dua kondisi berbeda: `'identity_error'` ketika username/email tidak ditemukan, dan `'password_error'` ketika password salah.

**Dampak:**  
Penyerang dapat memastikan apakah username/email terdaftar di sistem sebelum melakukan serangan password. Memfasilitasi harvesting akun dan serangan targeted.

**Rekomendasi:**
```php
// Gunakan satu pesan generik untuk keduanya:
return response()->json([
    'status' => 'error',
    'message' => 'Kredensial yang Anda masukkan tidak valid.',
], 422);
```

---

### 🟡 AUTH-004 — Session Cookie Secure Flag Tidak Ada Default Aman
**Tingkat Risiko:** Medium  
**File:** `config/session.php` baris 172 | `.env.example`

**Penyebab:**  
`'secure' => env('SESSION_SECURE_COOKIE')` — tidak ada nilai default, dan `.env.example` tidak menyebutkan `SESSION_SECURE_COOKIE`. Jika variabel tidak disetel di `.env`, nilainya `null` (setara `false`).

**Dampak:**  
Session cookie dikirim melalui HTTP maupun HTTPS. Penyerang di jaringan yang sama (kafe, WiFi sekolah) dapat mencuri session cookie melalui sniffing.

**Rekomendasi:**
```php
// config/session.php:
'secure' => env('SESSION_SECURE_COOKIE', true),

// .env.example — tambahkan:
SESSION_SECURE_COOKIE=true
```

---

### 🟡 AUTHZ-003 — IDOR: Murid Dapat Akses Foto Murid Lain
**Tingkat Risiko:** Medium  
**File:** `app/Http/Controllers/AbsenMuridController.php` baris 196–200  
**Route:** `GET /murid/foto/{tipe}/{filename}`

**Penyebab:**  
`fotoServe()` hanya memvalidasi `$tipe` berada di whitelist, kemudian langsung menyajikan file. Tidak ada pengecekan apakah file tersebut milik murid yang sedang login.

**Dampak:**  
Murid yang mengetahui nama file foto murid lain (pola nama dapat diprediksi: `{folder}_{userId}_{time}_{uniqid}.{ext}`) dapat mengakses foto selfie atau foto izin murid lain.

**Rekomendasi:**
```php
$absen = AbsenMurid::where('user_id', auth()->id())
    ->where(fn($q) => $q->where('foto_selfie', $filename)
                         ->orWhere('foto_izin', $filename))
    ->firstOrFail();
```

---

### 🟡 FILE-001 — Ekstensi File Diambil dari Nama Client
**Tingkat Risiko:** Medium  
**File:** `app/Http/Controllers/DataGuruController.php` baris 46–48 | `DataMuridController.php` baris 44

**Penyebab:**  
`$file->getClientOriginalExtension()` mengambil ekstensi dari nama file yang dikirim oleh client, bukan dari MIME type aktual file.

**Dampak:**  
File dengan nama `shell.php.jpg` akan memiliki ekstensi `jpg` yang diterima validasi `mimes:jpeg,jpg,png,webp`, namun konten aktualnya tidak divalidasi. Jika web server dikonfigurasi untuk mengeksekusi `.php`, dan direktori `public/assets/profile-guru/` dapat diakses, ada risiko RCE.

**Rekomendasi:**
```php
// Gunakan extension() berbasis MIME:
$filename = uniqid('guru_') . '.' . $file->extension();
// Pertimbangkan pindah ke private storage seperti foto absen
```

---

### 🟡 FILE-002 — Foto Base64 Tidak Divalidasi Magic Bytes
**Tingkat Risiko:** Medium  
**File:** `app/Http/Controllers/AbsenGuruController.php` baris 433+ | `AbsenMuridController.php` baris 520+ | `ProfileController.php` baris 149+

**Penyebab:**  
Ketiga implementasi `simpanFotoBase64()`/`simpanFoto()` hanya memeriksa header string data URI (`data:image/jpeg;base64,...`). Tidak ada validasi konten biner aktual menggunakan `getimagesizefromstring()` atau `finfo`.

**Dampak:**  
Penyerang dapat mengirimkan string base64 dengan header yang valid namun konten bukan gambar. Saat ini file tersebut disimpan ke `storage/app/private/` yang tidak dapat diakses langsung, membatasi dampak. Namun jika ada kerentanan path traversal atau serve endpoint tidak aman, file dapat dieksekusi.

**Rekomendasi:**
```php
// Tambahkan setelah decode:
$imageInfo = @getimagesizefromstring($decoded);
if ($imageInfo === false) return null;  // bukan gambar valid
```

---

### 🟡 INFO-001 — `laravel/tinker` di Production Dependencies
**Tingkat Risiko:** Medium  
**File:** `composer.json` baris 11

**Penyebab:**  
`laravel/tinker` terdaftar di blok `require` (production), bukan `require-dev`.

**Dampak:**  
Tinker ter-install di production environment dan dapat dijalankan via `php artisan tinker` jika penyerang mendapatkan akses shell. Ini memberikan REPL PHP dengan akses penuh ke seluruh aplikasi, model, dan database.

**Rekomendasi:**
```bash
composer require --dev laravel/tinker
# Di production server:
composer install --no-dev
```

---

### 🟡 REG-001 — Halaman Register Tanpa CAPTCHA
**Tingkat Risiko:** Medium  
**File:** `app/Http/Controllers/DataGuruController.php` baris 24+ | `DataMuridController.php` baris 32+

**Penyebab:**  
Endpoint `POST /register/guru` dan `POST /register/murid` tidak memiliki CAPTCHA atau rate limiting. Siapapun dapat membuat akun tanpa hambatan.

**Dampak:**  
Bot dapat mendaftar massal, mengisi antrian verifikasi admin dengan ribuan pendaftaran palsu, menyebabkan denial of service pada proses verifikasi dan mengotori database.

**Rekomendasi:**
```php
// routes/web.php — tambahkan throttle:
Route::post('/register/guru', ...)->middleware('throttle:3,10');
Route::post('/register/murid', ...)->middleware('throttle:3,10');
// Tambahkan integrasi reCAPTCHA v3 ke form register
```

---

### 🔵 AUTH-005 — Session Tidak Terenkripsi
**Tingkat Risiko:** Low  
**File:** `.env.example` baris 24 | `config/session.php` baris 42

**Penyebab:**  
`SESSION_ENCRYPT=false` di `.env.example`. Data session tersimpan plaintext di tabel `sessions` database.

**Dampak:**  
Jika database bocor, seluruh payload session dapat dibaca. Dampak terbatas karena session sudah di belakang database, namun defence-in-depth menyarankan enkripsi.

**Rekomendasi:** Ubah ke `SESSION_ENCRYPT=true` di `.env` dan `.env.example`.

---

### 🔵 INFO-002 — Username Enumeration via Check-Username Publik
**Tingkat Risiko:** Low  
**File:** `routes/web.php` baris 32, 42

**Penyebab:**  
`GET /register/guru/check-username?username=X` mengembalikan `{"available": false}` jika username terdaftar, tanpa autentikasi dan tanpa rate limiting.

**Dampak:**  
Penyerang dapat mengenumerasi username yang terdaftar. Tingkat risiko dinilai Low karena hanya mengekspos username (bukan password), dan ini lazim pada form registrasi.

**Rekomendasi:** Tambahkan `->middleware('throttle:30,1')` pada kedua route.

---

### 🔵 INFO-003 — APP_DEBUG=true di Template .env.example
**Tingkat Risiko:** Low  
**File:** `.env.example` baris 4

**Penyebab:**  
Template default memiliki `APP_DEBUG=true`. Developer yang menyalin tanpa modifikasi akan menjalankan production dengan debug aktif.

**Rekomendasi:** Ubah ke `APP_DEBUG=false` di `.env.example`. Tambahkan komentar `# WAJIB false di production`.

---

### 🔵 INFO-004 — Tidak Ada Security Headers
**Tingkat Risiko:** Low  
**File:** `bootstrap/app.php`

**Penyebab:**  
Tidak ada middleware yang menambahkan `X-Frame-Options`, `X-Content-Type-Options`, `Content-Security-Policy`, atau `Referrer-Policy`.

**Dampak:**  
Aplikasi rentan terhadap clickjacking dan MIME type sniffing attacks. Tidak ada CSP mengurangi mitigasi XSS jika kerentanan baru ditemukan.

**Rekomendasi:**  
Tambahkan di `AppServiceProvider::boot()`:
```php
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: strict-origin-when-cross-origin');
```
Atau gunakan package `spatie/laravel-csp`.

---

## Temuan yang Tidak Dikonfirmasi (Needs Validation)

Dua pola berikut adalah anti-pattern keamanan namun tidak dapat dikonfirmasi sebagai kerentanan tanpa mengetahui konfigurasi web server:

**FILE-NV-001: Potensi Path Traversal di fotoServe**  
`ProfileController::fotoAdmin($filename)` mengkonkatenasikan `$filename` dari URL parameter langsung ke path: `storage_path('app/private/assets/profile_admin/' . $filename)`. Laravel Router secara default menolak karakter `/` di route segment, namun ini bergantung pada konfigurasi web server. Rekomendasi: tambahkan `basename()` atau validasi eksplisit.

---

## Temuan yang Dikecualikan (Tidak Terkonfirmasi / False Positive)

| Kandidat | Alasan Dikecualikan |
|---|---|
| SQL Injection | Semua query menggunakan Eloquent ORM dengan parameterized binding. Tidak ditemukan `DB::statement()` dengan interpolasi string langsung. |
| XSS | Semua output di Blade view menggunakan `{{ }}` yang otomatis meng-escape HTML. Tidak ditemukan `{!! !!}` pada output data pengguna. |
| CSRF | Token `@csrf` hadir di semua form yang mengubah state. |
| Session Fixation | `$request->session()->regenerate()` dipanggil dengan benar setelah login berhasil. |
| Session HttpOnly | `'http_only' => env('SESSION_HTTP_ONLY', true)` — default aman. |

---

## Ringkasan Prioritas Perbaikan

Urutan berdasarkan dampak dan kemudahan implementasi:

| Prioritas | ID | Perbaikan | Effort |
|---|---|---|---|
| 1 | AUTH-001 | Tambahkan `throttle:5,1` ke route POST /login | Rendah (1 baris) |
| 2 | AUTHZ-005 | Perbaiki stale `where('role','guru')` di 3 baris AbsenGuruController + 1 AbsenMuridController | Rendah (4 baris) |
| 3 | AUTHZ-001 | Tambahkan ownership check di guruKonfirmasi | Rendah (1 baris) |
| 4 | AUTHZ-002 | Tambahkan kepemilikan jadwal di guruScanQr | Rendah (1 baris) |
| 5 | AUTHZ-003 | Tambahkan ownership check di fotoServe murid | Rendah (3 baris) |
| 6 | AUTHZ-004 | Hapus operasi CUD murid dari grup route guru | Medium |
| 7 | AUTH-002 | Ganti captcha custom dengan reCAPTCHA | Medium |
| 8 | AUTH-004 | Setel SESSION_SECURE_COOKIE=true | Rendah (1 baris) |
| 9 | INFO-001 | Pindahkan tinker ke require-dev | Rendah (1 command) |
| 10 | REG-001 | Tambahkan throttle ke route register | Rendah (2 baris) |

---

## Catatan Teknis

- **Versi yang diaudit:** Worktree dirty dengan 20 file dimodifikasi dari commit `4ffd035`. File-file yang dimodifikasi ikut diaudit.
- **Tools:** Analisis statis murni (source code reading), eksekusi query SQL lokal read-only untuk verifikasi data, PHP CLI untuk verifikasi captcha range.
- **Tidak dilakukan:** Dynamic testing, fuzzing, network scanning, atau akses ke instance production.
- **Coverage:** Route, controller, middleware, model, konfigurasi, dependency, dan view (HTML escaping) telah diperiksa. Controller yang belum diperiksa secara mendalam: `MapelController`, `JadwalMengajarController`, `KodeAbsenController` (hanya direviu sebagian via reconnaissance).

---

*Laporan ini dihasilkan dari analisis statis defensif. Tidak ada perubahan dilakukan pada source code. Semua temuan memerlukan konfirmasi ulang oleh pemilik sistem sebelum perbaikan diterapkan ke production.*
