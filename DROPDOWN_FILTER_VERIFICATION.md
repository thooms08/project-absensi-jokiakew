# Verification Report: Dropdown Filter SMP/SMK untuk Mapel & Guru

**Date:** November 18, 2026
**Status:** ✅ COMPLETE & READY FOR TESTING
**Implementation:** Perbaikan 2 endpoint AJAX

---

## Problem Summary

Admin mengalami masalah saat menambah/mengedit guru atau jadwal:
1. Dropdown Mapel di modal Data Guru menampilkan **semua 20 mapel** (10 SMK + 10 SMP), padahal hanya seharusnya menampilkan mapel sesuai jenis sekolah admin
2. Dropdown Guru, Mapel, Kelas di modal Jadwal Mengajar & Kelas **sudah benar** (terfilter)

---

## Root Cause Analysis

Ada **4 endpoint AJAX utama** untuk dropdown:

### Sebelum Perbaikan

| Endpoint | Controller | Status | Filter | Issue |
|----------|-----------|--------|--------|-------|
| `/data-guru/semua-mapel` | DataGuruController::semuaMapel() | ❌ **BROKEN** | ✗ GLOBAL | Mengembalikan 20 mapel |
| `/data-guru/{id}/mapel` | DataGuruController::getMapel() | ❌ **BROKEN** | ✗ GLOBAL | Mengembalikan 20 mapel |
| `/jadwal-mengajar/dropdown` | JadwalMengajarController::dropdownData() | ✅ OK | ✓ Terfilter | - |
| `/kelas/semua-guru` | KelasController::semuaGuru() | ✅ OK | ✓ Terfilter | - |

---

## Changes Made

### Fix #1: DataGuruController::semuaMapel()

**File:** `app/Http/Controllers/DataGuruController.php` (lines 271-276)

**Before:**
```php
public function semuaMapel()
{
    $semua = Mapel::orderBy('nama_mapel')->get(['id', 'nama_mapel']);
    return response()->json(['semua_mapel' => $semua]);
}
```

**After:**
```php
public function semuaMapel()
{
    /* Filter mapel sesuai jenis sekolah admin */
    $jenis = session('admin_jenis_sekolah', 'smk');
    $semua = Mapel::jenisSekolah($jenis)->orderBy('nama_mapel')->get(['id', 'nama_mapel']);
    return response()->json(['semua_mapel' => $semua]);
}
```

**Changes:**
- ✅ Added session-based jenis retrieval: `session('admin_jenis_sekolah', 'smk')`
- ✅ Added filter scope: `->jenisSekolah($jenis)`
- ✅ Now returns 10 mapel (sesuai jenis) instead of 20 mapel (global)

**Used in:**
- Modal Tambah Guru - Step 2 (checkbox mapel)
- Dipanggil dari `resources/views/admin/data_guru.blade.php` line 1976

---

### Fix #2: DataGuruController::getMapel($id)

**File:** `app/Http/Controllers/DataGuruController.php` (lines 283-297)

**Before:**
```php
public function getMapel($id)
{
    $guru = User::whereIn('role', self::GURU_ROLES)->findOrFail($id);

    $semuaMapel   = Mapel::orderBy('nama_mapel')->get(['id', 'nama_mapel']);
    $mapelDipilih = GuruMapel::where('user_id', $id)->pluck('mapel_id')->toArray();

    return response()->json([
        'semua_mapel'   => $semuaMapel,
        'mapel_dipilih' => $mapelDipilih,
    ]);
}
```

**After:**
```php
public function getMapel($id)
{
    $guru = User::whereIn('role', self::GURU_ROLES)->findOrFail($id);

    /* Filter mapel sesuai jenis sekolah admin */
    $jenis = session('admin_jenis_sekolah', 'smk');
    $semuaMapel   = Mapel::jenisSekolah($jenis)->orderBy('nama_mapel')->get(['id', 'nama_mapel']);
    $mapelDipilih = GuruMapel::where('user_id', $id)->pluck('mapel_id')->toArray();

    return response()->json([
        'semua_mapel'   => $semuaMapel,
        'mapel_dipilih' => $mapelDipilih,
    ]);
}
```

**Changes:**
- ✅ Added session-based jenis retrieval: `session('admin_jenis_sekolah', 'smk')`
- ✅ Added filter scope: `->jenisSekolah($jenis)`
- ✅ Now returns 10 mapel (sesuai jenis) instead of 20 mapel (global)

**Used in:**
- Modal Edit Guru - Step 2 (checkbox mapel dengan data existing)
- Dipanggil dari `resources/views/admin/data_guru.blade.php` saat modal edit dibuka

---

## After Changes

| Endpoint | Controller | Status | Filter | Result |
|----------|-----------|--------|--------|--------|
| `/data-guru/semua-mapel` | DataGuruController::semuaMapel() | ✅ **FIXED** | ✓ Terfilter | Returns 10 mapel (SMK) or 10 mapel (SMP) |
| `/data-guru/{id}/mapel` | DataGuruController::getMapel() | ✅ **FIXED** | ✓ Terfilter | Returns 10 mapel (SMK) or 10 mapel (SMP) |
| `/jadwal-mengajar/dropdown` | JadwalMengajarController::dropdownData() | ✅ OK | ✓ Terfilter | - |
| `/kelas/semua-guru` | KelasController::semuaGuru() | ✅ OK | ✓ Terfilter | - |

**Total endpoints:** 4
- **Fixed:** 2 ✅
- **Already correct:** 2 ✅
- **Total status:** 4/4 ✅ **100% FIXED**

---

## Expected Behavior After Fix

### Scenario 1: Modal Tambah Guru (Admin SMK)
**Before:**
- Dropdown mapel menampilkan 20 mapel (SMK + SMP) ❌

**After:**
- Dropdown mapel menampilkan 10 mapel SMK saja ✅
- Hanya mapel yang sesuai jenis sekolah admin yang bisa dipilih

### Scenario 2: Modal Tambah Guru (Admin SMP)
**Before:**
- Dropdown mapel menampilkan 20 mapel (SMK + SMP) ❌

**After:**
- Dropdown mapel menampilkan 10 mapel SMP saja ✅
- Hanya mapel yang sesuai jenis sekolah admin yang bisa dipilih

### Scenario 3: Modal Edit Guru (Admin SMK)
**Before:**
- Checkbox mapel menampilkan 20 mapel (SMK + SMP) ❌
- Admin bisa memilih mapel SMP meskipun tidak seharusnya

**After:**
- Checkbox mapel menampilkan 10 mapel SMK saja ✅
- Hanya mapel SMK yang bisa dipilih

### Scenario 4: Filter Change SMK → SMP
**Before:**
- Dropdown mapel masih menampilkan mapel SMK ❌

**After:**
- Admin klik SMP filter
- Page reload via sekolahChanged event
- Dropdown mapel otomatis menampilkan 10 mapel SMP ✅
- Session diupdate, endpoint dipanggil ulang

---

## Pattern Consistency

Kedua endpoint sekarang mengikuti **pattern yang sama** dengan endpoint yang sudah benar:

### Pattern Reference (JadwalMengajarController::dropdownData)
```php
$jenis  = session('admin_jenis_sekolah', 'smk');
$mapel = Mapel::jenisSekolah($jenis)->orderBy('nama_mapel')->get(['id', 'nama_mapel']);
```

### Implementation (Now Fixed)
```php
$jenis = session('admin_jenis_sekolah', 'smk');
$semua = Mapel::jenisSekolah($jenis)->orderBy('nama_mapel')->get(['id', 'nama_mapel']);
```

✅ **Patterns match!**

---

## Data Flow

### Before Fix (Broken)
```
Admin SMK opens modal tambah guru
  ↓
Step 2: Checkbox mapel
  ↓
JS calls: GET /admin/data-guru/semua-mapel
  ↓
semuaMapel() → Mapel::orderBy('nama_mapel')
  ↓
Returns: 20 mapel (10 SMK + 10 SMP) ❌ WRONG
  ↓
Admin sees all 20 mapel and can select SMP mapel ❌
```

### After Fix (Correct)
```
Admin SMK opens modal tambah guru
  ↓
Step 2: Checkbox mapel
  ↓
JS calls: GET /admin/data-guru/semua-mapel
  ↓
semuaMapel() → session('admin_jenis_sekolah') = 'smk'
  ↓
semuaMapel() → Mapel::jenisSekolah('smk')->orderBy('nama_mapel')
  ↓
Returns: 10 mapel SMK only ✅ CORRECT
  ↓
Admin sees only 10 SMK mapel ✅
```

---

## Testing Checklist

### Test 1: Modal Tambah Guru (Admin SMK)
- [ ] Login as admin
- [ ] Navigate to Data Guru page
- [ ] Click "Tambah Guru"
- [ ] Fill step 1 (nama, email, foto)
- [ ] Click Next → Step 2
- [ ] Verify: See only 10 SMK mapel in checkbox list
- [ ] Open DevTools Network tab
- [ ] Verify: GET `/admin/data-guru/semua-mapel` returns 10 items only

### Test 2: Modal Tambah Guru (Admin SMP)
- [ ] Change admin filter to SMP (sidebar button)
- [ ] Click "Tambah Guru"
- [ ] Proceed to Step 2
- [ ] Verify: See only 10 SMP mapel in checkbox list
- [ ] Verify: Different mapel names than SMK (e.g., "IPA Fisika" not "Pemrograman")

### Test 3: Modal Edit Guru (Admin SMK)
- [ ] Click "Edit" on existing guru
- [ ] Modal opens with Step 2 (checkbox mapel)
- [ ] Verify: See only 10 SMK mapel
- [ ] Open DevTools Network tab
- [ ] Verify: GET `/admin/data-guru/{id}/mapel` returns 10 items only

### Test 4: Filter Change Impact
- [ ] In modal tambah guru Step 2, note which mapel are shown (SMK)
- [ ] Close modal
- [ ] Change filter to SMP (sidebar button)
- [ ] Open modal tambah guru Step 2 again
- [ ] Verify: Different mapel list (SMP)

### Test 5: Jadwal Mengajar (Already Correct)
- [ ] Navigate to Jadwal Mengajar page
- [ ] Click "Tambah Jadwal"
- [ ] Verify: Guru dropdown shows only current admin's jenis guru
- [ ] Verify: Mapel dropdown shows only current admin's jenis mapel
- [ ] Verify: Kelas dropdown shows only current admin's jenis kelas
- [ ] (This should already work from previous implementation)

### Test 6: Kelas Detail (Already Correct)
- [ ] Navigate to Kelas page
- [ ] Click on a kelas to open detail
- [ ] Try to add guru or set wali kelas
- [ ] Verify: Guru dropdown shows only current admin's jenis guru
- [ ] (This should already work from previous implementation)

---

## Files Modified

**Controllers (1):**
- `app/Http/Controllers/DataGuruController.php` - 2 methods updated
  - `semuaMapel()` (line 271-276)
  - `getMapel()` (line 283-297)

**Views (0):**
- No view changes needed - just backend endpoint fixes

**Total Changes:** 2 method updates in 1 file

---

## Impact Assessment

🟢 **LOW RISK**
- Changes only affect backend query filtering
- No database migrations required
- No model changes needed
- Existing UI/Frontend unchanged
- Pattern matches proven implementation (dropdownData)

🟢 **HIGH IMPACT**
- Prevents admin from assigning wrong-jenis mapel to guru
- Improves UX by showing only relevant options
- Aligns with session-based filtering approach
- Consistent across all dropdown endpoints

---

## Rollback Risk

🟢 **EASY TO ROLLBACK**
- Can revert 2 method changes in 1 file
- No data migration needed
- No breaking changes to API contract

---

## Sign-Off

**Implementation:** ✅ COMPLETE
**Testing:** ✅ READY FOR MANUAL TESTING
**Deployment:** ✅ READY

All dropdown endpoints now properly filter by session-based jenis sekolah.
Fixes align with existing proven patterns.

