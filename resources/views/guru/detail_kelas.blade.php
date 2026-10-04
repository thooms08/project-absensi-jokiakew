@extends('guru.layouts.app')

@section('title', 'Detail Kelas - ' . $kelas->nama_kelas)
@section('page-title', 'Detail Kelas')

@push('styles')
<style>
    /* ============================================================
       BACK LINK
    ============================================================ */
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        font-size: 0.82rem;
        color: rgba(255,255,255,0.45);
        text-decoration: none;
        margin-bottom: 1.25rem;
        transition: color 0.18s;
    }

    .back-link:hover { color: #4ade80; }

    /* ============================================================
       KELAS HEADER CARD
    ============================================================ */
    .kelas-header-card {
        background: linear-gradient(135deg, rgba(10,124,74,0.22) 0%, rgba(6,78,46,0.12) 100%);
        border: 1px solid rgba(74,222,128,0.18);
        border-radius: 20px 0 20px 0;
        padding: 1.5rem 1.75rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
        margin-bottom: 1.75rem;
        position: relative;
        overflow: hidden;
    }

    .kelas-header-card::before {
        content: '';
        position: absolute; inset: 0;
        background: linear-gradient(135deg, rgba(74,222,128,0.05) 0%, transparent 60%);
        pointer-events: none;
    }

    .kelas-header-info { display: flex; align-items: center; gap: 1.1rem; }

    .kelas-icon-wrap {
        width: 56px; height: 56px;
        border-radius: 16px;
        background: rgba(74,222,128,0.12);
        border: 1px solid rgba(74,222,128,0.22);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.6rem; color: #4ade80; flex-shrink: 0;
    }

    .kelas-nama-besar {
        font-size: 1.25rem;
        font-weight: 800;
        color: #ffffff;
        margin: 0 0 0.25rem;
    }

    .kelas-wali-info {
        font-size: 0.82rem;
        color: rgba(255,255,255,0.5);
        display: flex; align-items: center; gap: 0.4rem;
    }

    .kelas-wali-info i { color: #4ade80; }

    .kelas-header-stats {
        display: flex;
        gap: 1.25rem;
        flex-wrap: wrap;
    }

    .stat-chip {
        display: flex;
        flex-direction: column;
        align-items: center;
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(74,222,128,0.12);
        border-radius: 12px;
        padding: 0.65rem 1.1rem;
        min-width: 70px;
    }

    .stat-chip-val {
        font-size: 1.35rem;
        font-weight: 800;
        color: #4ade80;
        line-height: 1;
    }

    .stat-chip-label {
        font-size: 0.68rem;
        color: rgba(255,255,255,0.45);
        margin-top: 0.25rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
    }

    /* ============================================================
       SECTION HEADING
    ============================================================ */
    .section-heading {
        font-size: 0.75rem;
        font-weight: 700;
        color: rgba(74,222,128,0.7);
        text-transform: uppercase;
        letter-spacing: 0.09em;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.55rem;
    }

    .section-heading::after {
        content: ''; flex: 1; height: 1px;
        background: rgba(74,222,128,0.12);
    }

    /* ============================================================
       ANGGOTA PANEL
    ============================================================ */
    .anggota-panel {
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(74,222,128,0.12);
        border-radius: 16px 0 16px 0;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .anggota-panel-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.9rem 1.25rem;
        border-bottom: 1px solid rgba(74,222,128,0.1);
        background: rgba(255,255,255,0.02);
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .anggota-panel-title {
        font-size: 0.85rem;
        font-weight: 700;
        color: #ffffff;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .anggota-count-badge {
        font-size: 0.65rem;
        font-weight: 700;
        padding: 0.12rem 0.5rem;
        border-radius: 50px;
        background: rgba(74,222,128,0.12);
        color: #4ade80;
        border: 1px solid rgba(74,222,128,0.2);
    }

    /* Tabel anggota */
    .anggota-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.845rem;
    }

    .anggota-table thead th {
        font-size: 0.66rem;
        font-weight: 700;
        color: rgba(74,222,128,0.6);
        text-transform: uppercase;
        letter-spacing: 0.08em;
        padding: 0.75rem 1.1rem;
        border-bottom: 1px solid rgba(74,222,128,0.08);
        white-space: nowrap;
        background: rgba(255,255,255,0.02);
    }

    .anggota-table tbody td {
        padding: 0.72rem 1.1rem;
        border-bottom: 1px solid rgba(255,255,255,0.04);
        vertical-align: middle;
    }

    .anggota-table tbody tr:last-child td { border-bottom: none; }
    .anggota-table tbody tr:hover td { background: rgba(74,222,128,0.03); }

    .anggota-avatar {
        width: 34px; height: 34px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid rgba(74,222,128,0.22);
        flex-shrink: 0;
    }

    .anggota-avatar-init {
        width: 34px; height: 34px;
        border-radius: 50%;
        background: linear-gradient(135deg, #0a7c4a, #4ade80);
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 0.78rem; font-weight: 700; color: #064e2e;
        flex-shrink: 0;
    }

    .anggota-name { font-weight: 600; color: #fff; font-size: 0.855rem; }
    .anggota-username { font-size: 0.72rem; color: rgba(74,222,128,0.65); }

    .label-badge {
        display: inline-flex; align-items: center; gap: 0.25rem;
        font-size: 0.68rem; font-weight: 600; padding: 0.15rem 0.55rem;
        border-radius: 50px; white-space: nowrap;
    }

    .label-wali  { background: rgba(251,191,36,0.12); color: #fbbf24; border: 1px solid rgba(251,191,36,0.25); }
    .label-guru  { background: rgba(74,222,128,0.12); color: #4ade80; border: 1px solid rgba(74,222,128,0.22); }
    .label-murid { background: rgba(34,211,238,0.1); color: #22d3ee; border: 1px solid rgba(34,211,238,0.2); }

    .anggota-empty {
        text-align: center;
        padding: 2rem 1rem;
        color: rgba(255,255,255,0.28);
        font-size: 0.82rem;
    }

    /* Mapel badges (mapel yang diajar guru di kelas ini) */
    .mapel-badge-sm {
        display: inline-flex; align-items: center;
        background: rgba(251,191,36,0.1);
        border: 1px solid rgba(251,191,36,0.2);
        border-radius: 50px; padding: 0.1rem 0.45rem;
        font-size: 0.65rem; font-weight: 600; color: #fbbf24;
    }

    /* ============================================================
       READONLY BADGE
    ============================================================ */
    .readonly-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.72rem;
        font-weight: 600;
        color: rgba(255,255,255,0.35);
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 8px;
        padding: 0.28rem 0.7rem;
    }

    /* ============================================================
       RESPONSIVE
    ============================================================ */
    @media (max-width: 767.98px) {
        .kelas-header-card { flex-direction: column; align-items: flex-start; }
    }

    @media (max-width: 575.98px) {
        .dk-col-hide-xs { display: none; }
    }
</style>
@endpush

@section('content')

{{-- Tombol kembali --}}
<a href="{{ route('guru.data_kelas') }}" class="back-link">
    <i class="bi bi-arrow-left"></i> Kembali ke Kelas Saya
</a>

{{-- ============================================================
     HEADER KELAS
============================================================ --}}
@php
    $jumlahGuru  = $kelas->peserta->whereIn('label', ['wali_kelas','guru_kelas'])->count();
    $jumlahMurid = $kelas->peserta->where('label', 'murid')->count();
    $wali        = $kelas->peserta->firstWhere('label', 'wali_kelas');
@endphp

<div class="kelas-header-card">
    <div class="kelas-header-info">
        <div class="kelas-icon-wrap">
            <i class="bi bi-door-open-fill"></i>
        </div>
        <div>
            <h2 class="kelas-nama-besar">{{ $kelas->nama_kelas }}</h2>
            <div class="kelas-wali-info">
                <i class="bi bi-person-fill"></i>
                Wali Kelas:
                {{ $wali && $wali->user ? $wali->user->name : 'Belum ditentukan' }}
            </div>
        </div>
    </div>

    <div class="kelas-header-stats">
        <div class="stat-chip">
            <span class="stat-chip-val">{{ $jumlahGuru }}</span>
            <span class="stat-chip-label">Guru</span>
        </div>
        <div class="stat-chip">
            <span class="stat-chip-val">{{ $jumlahMurid }}</span>
            <span class="stat-chip-label">Murid</span>
        </div>
        <div class="stat-chip">
            <span class="stat-chip-val">{{ $jumlahGuru + $jumlahMurid }}</span>
            <span class="stat-chip-label">Total</span>
        </div>
    </div>
</div>

{{-- ============================================================
     PANEL GURU
============================================================ --}}
<p class="section-heading">Daftar Guru</p>

<div class="anggota-panel">
    <div class="anggota-panel-header">
        <div class="anggota-panel-title">
            <i class="bi bi-person-lines-fill" style="color:#4ade80;"></i>
            Guru Kelas
            <span class="anggota-count-badge">{{ $jumlahGuru }}</span>
        </div>
        {{-- Read-only: tidak ada tombol tambah guru --}}
        <span class="readonly-badge">
            <i class="bi bi-eye-fill"></i> Hanya Lihat
        </span>
    </div>

    @php
        $pesertaGuru = $kelas->peserta->whereIn('label', ['wali_kelas','guru_kelas'])->values();
    @endphp

    @if($pesertaGuru->isEmpty())
        <div class="anggota-empty">
            <i class="bi bi-person-lines-fill" style="font-size:1.8rem;display:block;margin-bottom:0.5rem;opacity:0.18;"></i>
            Belum ada guru di kelas ini.
        </div>
    @else
        <table class="anggota-table" aria-label="Daftar guru kelas">
            <thead>
                <tr>
                    <th style="width:44px;">#</th>
                    <th>Nama Guru</th>
                    <th class="dk-col-hide-xs">Peran</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pesertaGuru as $i => $p)
                <tr>
                    <td style="color:rgba(255,255,255,0.3); font-size:0.75rem;">{{ $i + 1 }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            @if($p->user && $p->user->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($p->user->image))
                                <img src="{{ asset('storage/'.$p->user->image) }}"
                                     alt="Foto {{ $p->user->name }}" class="anggota-avatar">
                            @else
                                <span class="anggota-avatar-init">
                                    {{ strtoupper(mb_substr($p->user->name ?? 'G', 0, 1)) }}
                                </span>
                            @endif
                            <div style="min-width:0;">
                                <div class="anggota-name text-truncate" style="max-width:180px;">
                                    {{ $p->user->name ?? '-' }}
                                </div>
                                <div class="anggota-username">{{ '@'.($p->user->username ?? '-') }}</div>
                                {{-- Mapel yang diajar --}}
                                @if($p->user && $p->user->mapel->count())
                                    <div style="display:flex;flex-wrap:wrap;gap:0.25rem;margin-top:0.25rem;">
                                        @foreach($p->user->mapel as $mp)
                                            <span class="mapel-badge-sm">{{ $mp->nama_mapel }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="dk-col-hide-xs">
                        @if($p->label === 'wali_kelas')
                            <span class="label-badge label-wali">
                                <i class="bi bi-star-fill" style="font-size:0.55rem;"></i> Wali Kelas
                            </span>
                        @else
                            <span class="label-badge label-guru">
                                <i class="bi bi-person-fill" style="font-size:0.55rem;"></i> Guru
                            </span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

{{-- ============================================================
     PANEL MURID
============================================================ --}}
<p class="section-heading">Daftar Murid</p>

<div class="anggota-panel">
    <div class="anggota-panel-header">
        <div class="anggota-panel-title">
            <i class="bi bi-people-fill" style="color:#22d3ee;"></i>
            Murid Kelas
            <span class="anggota-count-badge"
                  style="background:rgba(34,211,238,0.1);color:#22d3ee;border-color:rgba(34,211,238,0.2);">
                {{ $jumlahMurid }}
            </span>
        </div>
        {{-- Read-only: tidak ada tombol tambah murid --}}
        <span class="readonly-badge">
            <i class="bi bi-eye-fill"></i> Hanya Lihat
        </span>
    </div>

    @php
        $pesertaMurid = $kelas->peserta->where('label', 'murid')->values();
    @endphp

    @if($pesertaMurid->isEmpty())
        <div class="anggota-empty">
            <i class="bi bi-people-fill" style="font-size:1.8rem;display:block;margin-bottom:0.5rem;opacity:0.18;"></i>
            Belum ada murid di kelas ini.
        </div>
    @else
        <table class="anggota-table" aria-label="Daftar murid kelas">
            <thead>
                <tr>
                    <th style="width:44px;">#</th>
                    <th>Nama Murid</th>
                    <th class="dk-col-hide-xs">Email</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pesertaMurid as $i => $p)
                <tr>
                    <td style="color:rgba(255,255,255,0.3); font-size:0.75rem;">{{ $i + 1 }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            @if($p->user && $p->user->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($p->user->image))
                                <img src="{{ asset('storage/'.$p->user->image) }}"
                                     alt="Foto {{ $p->user->name }}" class="anggota-avatar">
                            @else
                                <span class="anggota-avatar-init"
                                      style="background:linear-gradient(135deg,#0a5878,#22d3ee);color:#0a2040;">
                                    {{ strtoupper(mb_substr($p->user->name ?? 'M', 0, 1)) }}
                                </span>
                            @endif
                            <div style="min-width:0;">
                                <div class="anggota-name text-truncate" style="max-width:180px;">
                                    {{ $p->user->name ?? '-' }}
                                </div>
                                <div class="anggota-username"
                                     style="color:rgba(34,211,238,0.65);">
                                    {{ '@'.($p->user->username ?? '-') }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="dk-col-hide-xs">
                        <span style="font-size:0.8rem;color:rgba(255,255,255,0.5);">
                            {{ $p->user->email ?? '-' }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

@endsection
