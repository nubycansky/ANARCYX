@extends('layouts.public')

@section('title', 'Education - AnarcyxReptile')

@push('scripts')
<script>
    // Fungsi JavaScript untuk memicu submit form saat kategori diklik
    function filterByCategory(categoryName) {
        document.getElementById('filter-category-input').value = categoryName;
        document.getElementById('eduSearchFilterForm').submit();
    }
</script>
@endpush

@push('styles')
    <style>
        /* --- STYLE INTERN KHUSUS HALAMAN EDUCATION --- */
        .edu-wrapper {
            max-width: 1200px;
            margin: 0 auto 60px auto;
            padding: 0 4%;
        }

        .edu-section-title {
            font-size: 1.8rem;
            font-weight: 800;
            color: #111111;
            margin-bottom: 25px;
        }

        /* --- SECTION 1: UPDATE HEADER HERO FULL LAYAR (SINKRON HOME) --- */
        .edu-hero-header {
            background: linear-gradient(-45deg, #1e2518, #283221, #3b4930, #232d1d);
            background-size: 400% 400%;
            animation: gradientMove 12s ease infinite;
            padding: 100px 8% 120px 8%;
            color: #FFFFFF;
            text-align: center;
            width: 100%;
            margin-bottom: 40px;
        }

        .edu-main-title {
            font-size: 3.2rem;
            font-weight: 800;
            margin-bottom: 15px;
            letter-spacing: -0.5px;
            color: #FFFFFF;
        }

        .edu-sub-title {
            font-size: 1.2rem;
            color: #C9E4A4; /* Menggunakan warna pastel aksen #C9E4A4 dari style.css global */
            font-weight: 600;
            max-width: 800px;
            margin: 0 auto;
            line-height: 1.6;
        }

        /* Toolbar Pencarian Menyesuaikan Layout */
        .search-bar-container {
            max-width: 500px;
            margin: 0 auto 40px auto;
            position: relative;
            display: flex;
            align-items: center;
        }
        .search-bar-container svg {
            position: absolute;
            left: 16px;
            color: #888888;
        }
        .edu-search-input {
            width: 100%;
            padding: 14px 16px 14px 48px;
            border: 1px solid #E5E5E5;
            border-radius: 30px;
            font-size: 0.95rem;
            font-weight: 600;
            outline: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.02);
            transition: border-color 0.2s;
        }
        .edu-search-input:focus {
            border-color: #6B8E4E;
        }

        /* Grid 4 Kolom Menu Kategori */
        .category-menu-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 60px;
        }

        .category-menu-card {
            background-color: #4A5C3A; /* Hijau Olive sesuai palet global */
            border-radius: 14px;
            padding: 25px 20px;
            text-align: center;
            color: #FFFFFF;
            cursor: pointer;
            transition: transform 0.2s, background-color 0.2s, border 0.2s;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 12px;
            border: 2px solid transparent;
        }

        .category-menu-card:hover, .category-menu-card.active-filter {
            transform: translateY(-4px);
            background-color: #3b4a2e;
            border-color: #C9E4A4;
        }

        .category-icon-circle {
            width: 50px;
            height: 50px;
            background-color: #FFFFFF;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .category-icon-circle svg {
            width: 24px;
            height: 24px;
            color: #4A5C3A;
            stroke-width: 2;
        }

        .category-card-text {
            font-size: 1rem;
            font-weight: 700;
            letter-spacing: 0.3px;
        }

        /* SECTION 2: VERTICAL STACK ARTIKEL */
        .article-vertical-stack {
            display: flex;
            flex-direction: column;
            gap: 16px;
            margin-bottom: 60px;
        }

        .article-list-card {
            background: #FFFFFF;
            border: 1px solid #E5E5E5;
            border-radius: 16px;
            padding: 20px 24px;
            display: flex;
            align-items: center;
            gap: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.015);
            text-decoration: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .article-list-card:hover {
            border-color: #6B8E4E;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.03);
        }

        .article-icon-left {
            width: 48px;
            height: 48px;
            background-color: #4A5C3A;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-shrink: 0;
        }

        .article-icon-left svg {
            width: 22px;
            height: 22px;
            color: #FFFFFF;
            stroke-width: 2;
        }

        .article-text-right {
            display: flex;
            flex-direction: column;
            gap: 4px;
            text-align: left;
        }

        .article-card-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: #111111;
            margin: 0;
        }

        .article-card-desc {
            font-size: 0.9rem;
            color: #888888;
            line-height: 1.5;
            margin: 0;
        }

        .badge-inline-category {
            background-color: #F3F4F0;
            color: #4A5C3A;
            font-size: 0.7rem;
            font-weight: 800;
            padding: 2px 8px;
            border-radius: 6px;
            width: fit-content;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        /* SECTION 3: BANNER KONSULTASI CTA */
        .cta-consultation-banner {
            background-color: #283221;
            border-radius: 20px;
            padding: 45px 50px;
            margin-bottom: 60px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 20px;
            box-shadow: 0 10px 30px rgba(40, 50, 33, 0.1);
        }

        .cta-banner-title {
            font-size: 1.8rem;
            font-weight: 800;
            color: #FFFFFF;
            margin: 0;
        }

        .cta-banner-desc {
            font-size: 1rem;
            color: #E0E0E0;
            max-width: 700px;
            line-height: 1.6;
            margin: 0;
        }

        .btn-cta-whatsapp {
            background-color: #25D366;
            color: #FFFFFF;
            text-decoration: none;
            padding: 14px 30px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.95rem;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 15px rgba(37, 211, 102, 0.2);
            transition: background-color 0.2s, transform 0.2s;
        }

        .btn-cta-whatsapp:hover {
            background-color: #1ebd54;
            transform: translateY(-2px);
        }

        .btn-cta-whatsapp svg {
            width: 20px;
            height: 20px;
            fill: currentColor;
        }

        /* SECTION 4: FOOTER CHECKLIST */
        .checklist-footer-card {
            background: #FFFFFF;
            border: 1px solid #E5E5E5;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 6px 25px rgba(0,0,0,0.01);
        }

        .checklist-columns-layout {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
            margin-top: 25px;
        }

        .checklist-sub-section h3 {
            font-size: 1.15rem;
            font-weight: 700;
            color: #4A5C3A;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .checklist-ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .checklist-li {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            font-size: 0.95rem;
            color: #444444;
            line-height: 1.5;
        }

        .checklist-icon-check {
            color: #6B8E4E;
            font-weight: 800;
            flex-shrink: 0;
            font-size: 1.1rem;
        }

        /* --- RESPONSIVITAS DEVICE --- */
        @media (max-width: 992px) {
            .category-menu-grid { grid-template-columns: repeat(2, 1fr); }
            .checklist-columns-layout { grid-template-columns: 1fr; gap: 35px; }
        }
        @media (max-width: 600px) {
            .category-menu-grid { grid-template-columns: 1fr; }
            .cta-consultation-banner { padding: 30px; }
            .article-list-card { flex-direction: column; text-align: center; padding: 25px 20px; }
            .edu-main-title { font-size: 2.3rem; }
        }
    </style>
@endpush

@section('content')

    <header class="edu-hero-header">
        <h1 class="edu-main-title">ANARCYXREPTILE Care Education</h1>
        <p class="edu-sub-title">Pusat panduan edukasi tepercaya untuk menciptakan ekosistem hidup reptil peliharaan yang sehat, aman, dan ideal.</p>
    </header>

    <div class="edu-wrapper">
        
        <!-- Form Pengendali Pencarian dan Filter ke MongoDB -->
        <form action="{{ route('education') }}" method="GET" id="eduSearchFilterForm">
            <div class="search-bar-container">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                <input type="text" name="search" class="edu-search-input" placeholder="Cari artikel edukasi di sini..." value="{{ request('search') }}" onkeyup="if(event.key === 'Enter') this.form.submit();">
            </div>
            <input type="hidden" name="category" id="filter-category-input" value="{{ request('category', 'all') }}">
        </form>

        <!-- Grid Menu Kategori Dinamis Komunikasi Antarmuka -->
        <section class="category-menu-grid">
            <div class="category-menu-card {{ !request('category') || request('category') == 'all' ? 'active-filter' : '' }}" onclick="filterByCategory('all')">
                <div class="category-icon-circle">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                </div>
                <span class="category-card-text">Semua Artikel</span>
            </div>

            <div class="category-menu-card {{ request('category') == 'Habitat' ? 'active-filter' : '' }}" onclick="filterByCategory('Habitat')">
                <div class="category-icon-circle">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                </div>
                <span class="category-card-text">Habitat</span>
            </div>

            <div class="category-menu-card {{ request('category') == 'Diet' || request('category') == 'Makanan' ? 'active-filter' : '' }}" onclick="filterByCategory('Diet')">
                <div class="category-icon-circle">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.344l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z" /></svg>
                </div>
                <span class="category-card-text">Makanan (Diet)</span>
            </div>

            <div class="category-menu-card {{ request('category') == 'General' || request('category') == 'Umum' ? 'active-filter' : '' }}" onclick="filterByCategory('General')">
                <div class="category-icon-circle">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                </div>
                <span class="category-card-text">Umum / Kesehatan</span>
            </div>
        </section>

        <!-- SECTION 2: VERTICAL STACK ARTIKEL (Membaca Data Nyata MongoDB Atlas) -->
        <section class="article-section-block">
            <h2 class="edu-section-title">Daftar Panduan Perawatan</h2>

            <div class="article-vertical-stack">
                @forelse($articles as $art)
                    <a href="{{ route('education.show', $art->id) }}" class="article-list-card">
                        <div class="article-icon-left">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                        </div>
                        <div class="article-text-right">
                            <span class="badge-inline-category">{{ $art->category }}</span>
                            <h3 class="article-card-title">{{ $art->title }}</h3>
                            <p class="article-card-desc">{{ $art->preview }}</p>
                        </div>
                    </a>
                @empty
                    <div style="text-align: center; color: #888; padding: 40px; font-weight: 700; background: white; border: 1px dashed #E5E5E5; border-radius: 16px;">
                        Belum ada artikel edukasi yang sesuai dengan pencarian atau filter Anda di MongoDB Cloud Atlas.
                    </div>
                @endforelse
            </div>
        </section>

        <!-- BANNER KONSULTASI CTA -->
        <section class="cta-consultation-banner">
            <h2 class="cta-banner-title">Butuh saran / konsultasi lebih lanjut?</h2>
            <p class="cta-banner-desc">Tim ahli AnarcyxReptile siap membantu menjawab kendala teknis perawatan, pemilihan asupan gizi, hingga setup kandang terbaik bagi reptil kesayanganmu.</p>
            <a href="https://wa.me/6281234567890?text=Halo%20AnarcyxReptile,%20saya%20butuh%20konsultasi%20mengenai%20perawatan%20reptil" target="_blank" class="btn-cta-whatsapp">
                <svg viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.513 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.713-1.457L0 24zm6.135-3.414l.354.21c1.614.957 3.477 1.463 5.39 1.464 5.568 0 10.1-4.529 10.104-10.097.002-2.698-1.045-5.234-2.95-7.14C17.135 3.117 14.6 2.07 11.9 2.07 6.42 2.07 1.957 6.53 1.954 12.099c-.001 1.95.511 3.854 1.481 5.514l.23.393-1.01 3.693 3.791-.994z"/></svg>
                Hubungi kami
            </a>
        </section>

        <!-- ESSENTIAL CARE CHECKLIST -->
        <section class="checklist-footer-card">
            <h2 class="edu-section-title" style="margin-bottom: 5px;">Essential Care Checklist</h2>
            
            <div class="checklist-columns-layout">
                <div class="checklist-sub-section">
                    <h3>Sebelum Anda Membeli</h3>
                    <ul class="checklist-ul">
                        <li class="checklist-li"><span class="checklist-icon-check">&#10003;</span><span>Siapkan enclosure/kandang lengkap sebelum unit reptil tiba di rumah.</span></li>
                        <li class="checklist-li"><span class="checklist-icon-check">&#10003;</span><span>Pasang alat pengukur suhu dan kelembaban (Thermometer & Hygrometer) digital.</span></li>
                        <li class="checklist-li"><span class="checklist-icon-check">&#10003;</span><span>Pastikan pasokan pakan hidup (jangkrik/ulat) sudah tersedia sesuai ukuran mulut hewan.</span></li>
                        <li class="checklist-li"><span class="checklist-icon-check">&#10003;</span><span>Cari tahu info kontak dokter hewan khusus reptil terdekat dari tempat tinggalmu.</span></li>
                    </ul>
                </div>

                <div class="checklist-sub-section">
                    <h3>Daily Care</h3>
                    <ul class="checklist-ul">
                        <li class="checklist-li"><span class="checklist-icon-check">&#10003;</span><span>Cek temperatur lampu basking spot dan kelembaban udara setiap pagi hari.</span></li>
                        <li class="checklist-li"><span class="checklist-icon-check">&#10003;</span><span>Ganti wadah air minum dengan air bersih yang segar secara berkala untuk menjaga kebersihan.</span></li>
                        <li class="checklist-li"><span class="checklist-icon-check">&#10003;</span><span>Bersihkan kotoran/feses instan (Spot Cleaning) untuk mencegah timbulnya bakteri kandang.</span></li>
                        <li class="checklist-li"><span class="checklist-icon-check">&#10003;</span><span>Berikan pakan berkualitas yang telah dibaluri (dusting) kalsium sesuai jadwal makan.</span></li>
                    </ul>
                </div>
            </div>
        </section>

    </div>
@endsection