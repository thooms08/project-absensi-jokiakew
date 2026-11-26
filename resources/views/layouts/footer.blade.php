<footer class="site-footer">
    <div class="footer-top">
        <div class="container">
            <div class="row g-5">

                <!-- Identitas Sekolah -->
                <div class="col-lg-4 col-md-6">
                    <div class="footer-brand d-flex align-items-center gap-3 mb-3">
                        @php
                            $logoPath = $profileSekolah->logo
                                ? asset('uploads/logo/' . basename($profileSekolah->logo))
                                : asset('logo.jpeg');
                        @endphp
                        <img src="{{ $logoPath }}" alt="Logo {{ $profileSekolah->nama_sekolah ?? 'Sekolah' }}" class="footer-logo">
                        <div>
                            <span class="footer-school-name">{{ strtoupper($profileSekolah->nama_sekolah ?? 'NAMA SEKOLAH') }}</span>
                        </div>
                    </div>
                    <p class="footer-desc">
                        {{ $profileSekolah->deskripsi ?? 'Sekolah yang berkomitmen mencetak generasi terampil, berakhlak mulia, dan siap bersaing di era modern.' }}
                    </p>
                </div>

                <!-- Kontak -->
                <div class="col-lg-4 col-md-6">
                    <h5 class="footer-heading">Kontak Kami</h5>
                    <ul class="footer-contact-list">
                        @if($profileSekolah->no_hp)
                        <li>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                <path d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.6 17.6 0 0 0 4.168 6.608 17.6 17.6 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.68.68 0 0 0-.58-.122l-2.19.547a1.745 1.745 0 0 1-1.657-.459L5.482 8.062a1.745 1.745 0 0 1-.46-1.657l.548-2.19a.68.68 0 0 0-.122-.58z"/>
                            </svg>
                            <a href="tel:{{ $profileSekolah->no_hp }}">{{ $profileSekolah->no_hp }}</a>
                        </li>
                        @endif
                        @if($profileSekolah->email)
                        <li>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555ZM0 4.697v7.104l5.803-3.558L0 4.697ZM6.761 8.83l-6.57 4.026A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757Zm3.436-.586L16 11.801V4.697l-5.803 3.546Z"/>
                            </svg>
                            <a href="mailto:{{ $profileSekolah->email }}">{{ $profileSekolah->email }}</a>
                        </li>
                        @endif
                        @if($profileSekolah->alamat)
                        <li>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                            </svg>
                            <span>{{ $profileSekolah->alamat }}</span>
                        </li>
                        @endif
                    </ul>
                </div>

                <!-- Google Maps -->
                <div class="col-lg-4 col-md-12">
                    <h5 class="footer-heading">Lokasi Sekolah</h5>
                    <div class="footer-map-wrapper">
                        @if($profileSekolah->maps_link)
                        <iframe
                            src="{{ $profileSekolah->maps_link }}"
                            width="100%"
                            height="180"
                            style="border:0;"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            title="Lokasi {{ $profileSekolah->nama_sekolah ?? 'Sekolah' }}">
                        </iframe>
                        @else
                        <div style="width:100%;height:180px;background:rgba(255,255,255,0.04);border:1px solid rgba(74,222,128,0.2);border-radius:10px;display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,0.3);font-size:0.8rem;">
                            Lokasi belum dikonfigurasi
                        </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container">
            <p class="mb-0 text-center">
                &copy; {{ date('Y') }} {{ $profileSekolah->nama_sekolah ?? 'Sekolah' }}. Seluruh hak cipta dilindungi.
            </p>
        </div>
    </div>
</footer>
