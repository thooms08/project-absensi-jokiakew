@if($peserta->isEmpty())
    <div class="anggota-empty">
        <i class="bi bi-people-fill" style="font-size:1.8rem;display:block;margin-bottom:0.5rem;opacity:0.18;"></i>
        Belum ada murid di kelas ini. Klik "+ Murid" untuk menambahkan.
    </div>
@else
    <table class="anggota-table" aria-label="Daftar murid kelas">
        <thead>
            <tr>
                <th style="width:44px;">#</th>
                <th>Nama Murid</th>
                <th class="dk-col-hide-xs">Status</th>
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
                                 alt="Foto {{ $p->user->name }}" class="anggota-avatar"
                                 style="border-color:rgba(34,211,238,0.25);">
                        @else
                            <span class="anggota-avatar-init"
                                  style="background:linear-gradient(135deg,#0e4a7a,#22d3ee);color:#0a2040;">
                                {{ strtoupper(mb_substr($p->user->name ?? 'M', 0, 1)) }}
                            </span>
                        @endif
                        <div style="min-width:0;">
                            <div class="anggota-name text-truncate" style="max-width:180px;">
                                {{ $p->user->name ?? '-' }}
                            </div>
                            <div class="anggota-username" style="color:rgba(34,211,238,0.65);">
                                {{ '@'.($p->user->username ?? '-') }}
                            </div>
                        </div>
                    </div>
                </td>
                <td class="dk-col-hide-xs">
                    <span class="label-badge label-murid">
                        <i class="bi bi-mortarboard-fill" style="font-size:0.55rem;"></i> Murid
                    </span>
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
