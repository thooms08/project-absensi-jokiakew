# Testing Checklist: Real-time AJAX Filter SMP/SMK

**Tujuan:** Verifikasi bahwa saat admin mengubah filter sekolah (SMK ↔ SMP) di sidebar, data pada halaman Mapel, Kelas, dan Jadwal Mengajar berubah secara real-time tanpa page reload.

---

## Architecture Overview

### Event Flow
1. Admin klik tombol SMK/SMP di sidebar → `school-btn` click handler
2. Handler mengirim POST ke `admin.sekolah.set_jenis` → set session `admin_jenis_sekolah`
3. Response emit custom event `sekolahChanged` dengan detail `{ jenis, registerMuridUrl, registerGuruUrl }`
4. Event listener di setiap halaman (mapel, kelas, jadwal) menangkap event
5. Callback memanggil `loadList()` → AJAX fetch ke endpoint list
6. Controller filter berdasarkan session `admin_jenis_sekolah` 
7. Return HTML partial → DOM update

### Key Endpoints
- **Set Jenis:** `POST /admin/sekolah/set-jenis` (AdminSekolahController)
- **Mapel List:** `GET /admin/mapel/list?search=...` (MapelController::list)
- **Kelas List:** `GET /admin/kelas/list?search=...` (KelasController::list)
- **Jadwal List:** `GET /admin/jadwal-mengajar/list?hari=...&search=...` (JadwalMengajarController::list)

### Database Data
- **Mapel:** 20 total (10 SMK + 10 SMP), kolom `jenis_sekolah` ENUM('smk','smp')
- **Kelas:** 21 total (9 SMK + 12 SMP), kolom `jenis_sekolah` ENUM('smk','smp')
- **Jadwal:** Filtered via guru role (`guru_smk` / `guru_smp`)

---

## Test Cases

### TC-1: Mapel Page - Filter SMK → SMP
**Setup:** Admin di halaman `/admin/mapel`
1. ✓ Page load → list mapel SMK ditampilkan (10 item)
   - Verify: X-A, X-B, X-C, XI-RPL, XI-TKJ, XI-MM, XII-RPL, XII-TKJ, XII-MM visible
2. ✓ Click SMP button di sidebar
3. ✓ Wait ~500ms for AJAX
4. ✓ Verify: List berubah ke mapel SMP (10 item berbeda)
   - Verify: Matematika, Bahasa Indonesia, IPA (Fisika), IPA (Kimia), IPS, Seni Budaya visible
5. ✓ No page reload detected (URL same, JS state preserved)

### TC-2: Mapel Page - Filter SMP → SMK
**Setup:** Admin di halaman `/admin/mapel` (currently on SMP)
1. ✓ List showing SMP mapel
2. ✓ Click SMK button
3. ✓ Wait ~500ms
4. ✓ Verify: List kembali ke SMK mapel
5. ✓ Search field reset (empty)

### TC-3: Kelas Page - Filter SMK → SMP
**Setup:** Admin di halaman `/admin/kelas`
1. ✓ Page load → list kelas SMK ditampilkan (9 item)
   - Verify: Matematika, Pemrograman visible
2. ✓ Click SMP button di sidebar
3. ✓ Wait ~500ms for AJAX
4. ✓ Verify: List berubah ke kelas SMP (12 item)
   - Verify: 7-A, 7-B, 8-C, 9-D visible
5. ✓ No page reload

### TC-4: Kelas Page - Filter SMP → SMK
**Setup:** Admin di halaman `/admin/kelas` (currently on SMP)
1. ✓ List showing SMP kelas
2. ✓ Click SMK button
3. ✓ Wait ~500ms
4. ✓ Verify: List kembali ke SMK kelas
5. ✓ Search field reset

### TC-5: Jadwal Mengajar Page - Filter SMK → SMP
**Setup:** Admin di halaman `/admin/jadwal-mengajar` (default to today's hari)
1. ✓ Page load → list jadwal SMK guru ditampilkan
2. ✓ Click SMP button di sidebar
3. ✓ Wait ~500ms for AJAX
4. ✓ Verify: List berubah ke jadwal SMP guru
5. ✓ No page reload

### TC-6: Jadwal Mengajar Page - Filter SMP → SMK
**Setup:** Admin di halaman `/admin/jadwal-mengajar` (currently on SMP)
1. ✓ List showing SMP jadwal
2. ✓ Click SMK button
3. ✓ Wait ~500ms
4. ✓ Verify: List kembali ke SMK jadwal
5. ✓ Search field reset

### TC-7: Multiple Rapid Filter Changes
**Setup:** Admin di `/admin/mapel`
1. ✓ Click SMK → wait 300ms
2. ✓ Click SMP → wait 300ms
3. ✓ Click SMK → wait 300ms
4. ✓ Verify: Final list is SMK mapel
5. ✓ No console errors
6. ✓ No duplicate AJAX requests queued

### TC-8: Filter Change with Search Active
**Setup:** Admin di `/admin/mapel`, search field filled with "Matematika"
1. ✓ List showing filtered results (e.g., SMK Matematika)
2. ✓ Click SMP button
3. ✓ Verify: Search field cleared
4. ✓ Verify: List showing ALL SMP mapel (no search filter)

### TC-9: Modal State During Filter Change
**Setup:** Admin has modal (tambah/edit) open, then changes filter
1. ✓ Modal remains open
2. ✓ Background list updates silently
3. ✓ Modal close → list is updated to new filter
4. Note: Dropdown (guru, mapel, kelas) in modal might need refresh if jenis changed

---

## Browser Console Verification

### Expected Console Logs
```
// When filter changes:
Sekolah berubah ke: smp
// Or
Sekolah berubah ke: smk
```

### No Errors Expected
- ❌ 404 on /admin/mapel/list?search=...
- ❌ 500 on any AJAX endpoint
- ❌ Undefined function references
- ❌ CORS errors

---

## Code Verification Checklist

### MapelController
- ✓ `list()` method filters by session `admin_jenis_sekolah`
- ✓ `list()` returns view 'admin.partials.list_mapel'

### KelasController
- ✓ `list()` method filters by session `admin_jenis_sekolah`
- ✓ `list()` returns view 'admin.partials.list_kelas'

### JadwalMengajarController
- ✓ `list()` method filters by guru role from session (`guru_smk` / `guru_smp`)
- ✓ `list()` returns view 'admin.partials.list_jadwal_mengajar'

### Views (Blade)
- ✓ `mapel.blade.php` has event listener `document.addEventListener('sekolahChanged', ...)`
- ✓ `kelas.blade.php` has event listener `document.addEventListener('sekolahChanged', ...)`
- ✓ `jadwal_mengajar.blade.php` has event listener `document.addEventListener('sekolahChanged', ...)`

### Layout
- ✓ `admin/layouts/app.blade.php` emits `sekolahChanged` custom event after setJenis()

---

## Performance Notes

- AJAX request: ~100-500ms depending on data size
- List rendering: <100ms
- Total UX perceived latency: <600ms
- No page reload = no flash/flicker
- Search field reset is intentional (UX: start fresh with new jenis)

---

## Edge Cases to Consider

1. **Concurrent requests:** If user clicks filter twice rapidly
   - Current: loadList() replaces previous request (good)
   
2. **Modal open during filter change:**
   - Modal stays open, list updates behind
   - Dropdown inside modal not auto-refreshed (might show old jenis data)
   - Acceptable: User closes modal to see new list
   
3. **Network timeout:**
   - AJAX fails silently (catch handler shows error message)
   - No state corruption

4. **Session timeout:**
   - User redirected to login (expected behavior)

---

## Manual Testing Steps

1. Open `/admin/mapel` → Verify SMK mapel list
2. Open browser DevTools (F12) → Console tab
3. Click "SMP" button → Check console for "Sekolah berubah ke: smp"
4. Wait and verify list updated with SMP mapel
5. Repeat for `/admin/kelas` and `/admin/jadwal-mengajar`
6. Try rapid switching and search scenarios from TC-7 and TC-8

---

## Success Criteria

✓ All 9 test cases pass
✓ No console errors
✓ No page reloads occur
✓ List updates within 600ms of filter change
✓ Search field resets properly
✓ Hari buttons (jadwal) remain in correct position
✓ UI remains responsive during AJAX (spinner shows)

