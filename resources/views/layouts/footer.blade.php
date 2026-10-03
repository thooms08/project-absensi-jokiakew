<footer class="site-footer">
    <div class="footer-top">
        <div class="container">
            <div class="row g-5">

                <!-- Identitas Sekolah -->
                <div class="col-lg-4 col-md-6">
                    <div class="footer-brand d-flex align-items-center gap-3 mb-3">
                        <img src="{{ asset('logo.jpeg') }}" alt="Logo SMKS Ibnu Sina" class="footer-logo">
                        <div>
                            <span class="footer-school-name">SMKS IBNU SINA</span>
                        </div>
                    </div>
                    <p class="footer-desc">
                        SMKS Ibnu Sina adalah sekolah menengah kejuruan yang berkomitmen mencetak generasi terampil,
                        berakhlak mulia, dan siap bersaing di dunia industri modern berbasis teknologi.
                    </p>
                </div>

                <!-- Kontak -->
                <div class="col-lg-4 col-md-6">
                    <h5 class="footer-heading">Kontak Kami</h5>
                    <ul class="footer-contact-list">
                        <li>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                <path d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.6 17.6 0 0 0 4.168 6.608 17.6 17.6 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.68.68 0 0 0-.58-.122l-2.19.547a1.745 1.745 0 0 1-1.657-.459L5.482 8.062a1.745 1.745 0 0 1-.46-1.657l.548-2.19a.68.68 0 0 0-.122-.58z"/>
                            </svg>
                            <a href="tel:+6285212345678">+62 852-1234-5678</a>
                        </li>
                        <li>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555ZM0 4.697v7.104l5.803-3.558L0 4.697ZM6.761 8.83l-6.57 4.026A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757Zm3.436-.586L16 11.801V4.697l-5.803 3.546Z"/>
                            </svg>
                            <a href="mailto:info@smksibnusina.sch.id">info@smksibnusina.sch.id</a>
                        </li>
                        <li>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                            </svg>
                            <span>Jl. Raya Ibnu Sina No. 1, Kec. Contoh, Kab. Contoh, Provinsi, 12345</span>
                        </li>
                    </ul>
                </div>

                <!-- Google Maps -->
                <div class="col-lg-4 col-md-12">
                    <h5 class="footer-heading">Lokasi Sekolah</h5>
                    <div class="footer-map-wrapper">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.0!2d106.8!3d-6.2!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNsKwMTInMDAuMCJTIDEwNsKwNDgnMDAuMCJF!5e0!3m2!1sid!2sid!4v1234567890"
                            width="100%"
                            height="180"
                            style="border:0;"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            title="Lokasi SMKS Ibnu Sina">
                        </iframe>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container">
            <p class="mb-0 text-center">
                &copy; {{ date('Y') }} SMKS Ibnu Sina. Seluruh hak cipta dilindungi.
            </p>
        </div>
    </div>
</footer>
