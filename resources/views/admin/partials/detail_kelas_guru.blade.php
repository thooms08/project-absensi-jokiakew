@if($peserta->isEmpty())
    <div class="anggota-empty">
        <i class="bi bi-person-lines-fill" style="font-size:1.8rem;display:block;margin-bottom:0.5rem;opacity:0.18;"></i>
        Belum ada guru di kelas ini. Klik "+ Guru" untuk menambahkan.
    </div>
@else
    <table class="anggota-table" aria-label="Daftar guru kelas">
        <thead>
            <tr>
                <th style="width:44px;">#</th>
                <th>Nama Guru</th>
                <th class="dk-col-hide-xs">Peran</th>
                <th style="width:60px; text-align:right; padding-right:1.1rem;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($peserta as $i => $p)
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
                            @if($p->user && $p->user->mapel->count())
                                <div style="display:flex;flex-wrap:wrap;gap:0.25rem;margin-top:0.25rem;">
                                    @foreach($p->user->mapel as $mp)
                                        <span style="display:inline-flex;align-items:center;
                                                     background:rgba(251,191,36,0.1);
                                                     border:1px solid rgba(251,191,36,0.2);
                                                     border-radius:50px;padding:0.1rem 0.45rem;
                                                     font-size:0.65rem;font-weight:600;color:#fbbf24;">
                                            {{ $mp->nama_mapel }}
                                        </span>
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
                <td>
                    <div class="d-flex justify-content-end" style="padding-right:0.1rem;">
                        <button type="button" class="btn-hapus-anggota"
                                data-hapus-peserta="{{ $p->id }}"
                                data-nama="{{ $p->user->name ?? '-' }}"
                                title="Keluarkan dari kelas"
                                aria-label="Keluarkan {{ $p->user->name ?? '' }}">
                            <i class="bi bi-person-dash-fill"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endif
