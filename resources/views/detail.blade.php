@extends('layouts.public')

@section('title', $reptile->name . ' - Detail Sahabat')

@push('styles')
    <style>
        .detail-wrapper {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
            padding: 60px 8%;
        }
        .detail-image-box {
            width: 100%;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }
        .detail-image-box img {
            width: 100%;
            height: auto;
            display: block;
        }
        .detail-info-box h2 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 10px;
        }
        .detail-price {
            font-size: 1.8rem;
            color: var(--title-color);
            font-weight: 700;
            margin-bottom: 20px;
        }
        .detail-desc-text {
            font-size: 1rem;
            line-height: 1.7;
            color: #555;
            margin-bottom: 30px;
        }
        @media (max-width: 768px) {
            .detail-wrapper { grid-template-columns: 1fr; gap: 30px; padding: 40px 5%; }
        }
        .btn-add-cart {
            background-color: #6B8E4E;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-add-cart:hover { background-color: #55723e; }

        .desc-section-wrap {
            width: calc(100% - 40px);
            max-width: 100% !important;
            margin: 0 auto 30px auto;
            padding: 0 30px;
        }
        .desc-card {
            background: #FFFFFF;
            border: 1px solid #ECECEC;
            border-radius: 20px;
            padding: 45px 50px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.04);
        }
        .desc-header {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 8px;
        }
        .desc-header-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: linear-gradient(135deg, #6B8E4E 0%, #4A5C3A 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            flex-shrink: 0;
        }
        .desc-header h2 {
            margin: 0;
            font-size: 1.6rem;
            font-weight: 800;
            color: #111;
        }
        .desc-header p {
            margin: 4px 0 0;
            font-size: 0.85rem;
            color: #888;
            font-weight: 500;
        }
        .desc-divider {
            height: 1px;
            background: #EFEFEF;
            margin: 25px 0 30px;
        }
        .desc-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
            margin-bottom: 35px;
        }
        .desc-info-card {
            background: #FAFBF7;
            border: 1px solid #EFF2E9;
            border-radius: 14px;
            padding: 20px 22px;
            display: flex;
            align-items: flex-start;
            gap: 14px;
        }
        .desc-info-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .desc-info-icon.green { background: rgba(107,142,78,0.12); color: #4A5C3A; }
        .desc-info-icon.amber { background: rgba(217,158,74,0.15); color: #B07A20; }
        .desc-info-icon.blue  { background: rgba(76,128,167,0.13); color: #3D6B91; }
        .desc-info-icon.purple{ background: rgba(122,90,167,0.13); color: #6A4CA0; }
        .desc-info-icon.rose  { background: rgba(196,90,120,0.13); color: #B04A66; }
        .desc-info-icon.teal  { background: rgba(58,138,131,0.14); color: #2F7A75; }
        .desc-info-content { min-width: 0; flex: 1; }
        .desc-info-label {
            font-size: 0.72rem;
            font-weight: 700;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }
        .desc-info-value {
            font-size: 0.95rem;
            font-weight: 700;
            color: #1F1F1F;
            line-height: 1.4;
            word-wrap: break-word;
        }
        .desc-info-value.muted { color: #B0B0B0; font-weight: 600; font-style: italic; }

        .desc-content-block { margin-bottom: 28px; }
        .desc-content-block:last-child { margin-bottom: 0; }
        .desc-content-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.05rem;
            font-weight: 800;
            color: #111;
            margin-bottom: 12px;
        }
        .desc-content-title .tag {
            font-size: 0.65rem;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 20px;
            background: rgba(107,142,78,0.12);
            color: #4A5C3A;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .desc-content-text {
            font-size: 0.95rem;
            line-height: 1.85;
            color: #444;
            background: #FAFBF7;
            border-left: 3px solid #6B8E4E;
            padding: 18px 22px;
            border-radius: 0 12px 12px 0;
            white-space: pre-line;
        }
        .desc-content-text.empty {
            background: #F7F7F4;
            border-left-color: #D1D1C7;
            color: #A5A597;
            font-style: italic;
        }

        @media (max-width: 900px) {
            .desc-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 600px) {
            .desc-grid { grid-template-columns: 1fr; }
            .desc-card { padding: 30px 22px; }
        }
        .custom-toast {
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%) translateY(100px);
            background: #111111;
            color: #ffffff;
            padding: 14px 28px;
            border-radius: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            display: flex;
            align-items: center;
            gap: 12px;
            z-index: 99999;
            opacity: 0;
            transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275), opacity 0.3s ease;
            font-weight: 600;
            font-size: 0.95rem;
            white-space: nowrap;
            pointer-events: none;
        }
        .custom-toast.show {
            transform: translateX(-50%) translateY(0);
            opacity: 1;
        }
        .toast-icon {
            background: #6B8E4E;
            color: white;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 0.75rem;
            font-weight: bold;
        }
        .review-pagination nav svg { width: 20px; height: 20px; }
        .review-pagination span, .review-pagination a { background: rgba(255,255,255,0.05) !important; color: white !important; border: 1px solid rgba(255,255,255,0.1) !important; padding: 6px 12px; border-radius: 6px; margin: 0 4px; text-decoration: none; }
        .review-pagination .active span { background: #6B8E4E !important; border-color: #6B8E4E !important; }
    </style>
@endpush

@section('content')

    <div class="detail-wrapper">
        <div class="detail-image-box">
            <img src="/images/products/{{ $reptile->image }}" alt="{{ $reptile->name }}">
        </div>
        <div class="detail-info-box">
            <div class="badge-container">
                <span class="badge">{{ $reptile->category }}</span>
            </div>
            <h2>{{ $reptile->name }}</h2>
            <div class="detail-price">Rp.{{ number_format($reptile->price, 0, ',', '.') }}</div>
            
            <h3 style="margin-bottom: 10px; color: #111;">Deskripsi</h3>
            <p class="detail-desc-text">
                {{ $reptile->description ?? $reptile->desc ?? 'Reptil premium sehat, aktif, makan lancar, dan sudah melalui proses karantina ketat oleh tim ahli kami.' }}
            </p>

            <div style="max-width: 350px;">
                <button class="btn-action btn-quick-order" style="width: 100%; padding: 15px;" onclick="orderSpesifik('{{ $reptile->name }}', '{{ $reptile->price }}', '{{ $reptile->id }}')">
                    Beli Lewat WhatsApp Owner &rarr;
                </button>
                <button class="btn-action btn-add-cart" style="width: 100%; padding: 15px; margin-top: 12px;" onclick="addToCartFromDetail('{{ $reptile->id }}', '{{ $reptile->name }}', '{{ $reptile->price }}', '{{ $reptile->image }}')">
                    Add to Cart 🛒
                </button>
            </div>
        </div>
    </div>

    {{-- Deskripsi Produk Lengkap --}}
    @php
        $attrs     = is_array($reptile->attributes) ? $reptile->attributes : [];
        $ageVal    = $attrs['age']    ?? $reptile->age    ?? null;
        $morphVal  = $attrs['morph']  ?? $reptile->morph  ?? null;
        $weightVal = $attrs['weight'] ?? $reptile->weight ?? null;
        $descSingkat = $reptile->short_description ?? $reptile->desc ?? $reptile->description ?? null;
    @endphp

    <div class="desc-section-wrap">
        <div class="desc-card">
            <div class="desc-header">
                <div class="desc-header-icon">
                    <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                </div>
                <div>
                    <h2>Deskripsi Produk</h2>
                    <p>Informasi lengkap tentang {{ $reptile->name }} — karakteristik, habitat, pakan, jenis, umur, dan cara perawatan.</p>
                </div>
            </div>

            <div class="desc-divider"></div>

            @if($descSingkat)
                <p style="margin: 0 0 25px; font-size: 1rem; line-height: 1.8; color: #333; font-weight: 500;">
                    {{ $descSingkat }}
                </p>
            @endif

            <div class="desc-grid">
                <div class="desc-info-card">
                    <div class="desc-info-icon green">
                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
                    </div>
                    <div class="desc-info-content">
                        <div class="desc-info-label">Kategori</div>
                        <div class="desc-info-value">{{ $reptile->category ?? 'Tidak diketahui' }}</div>
                    </div>
                </div>

                <div class="desc-info-card">
                    <div class="desc-info-icon purple">
                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
                    </div>
                    <div class="desc-info-content">
                        <div class="desc-info-label">Jenis / Spesies</div>
                        <div class="desc-info-value {{ empty($reptile->type_info) ? 'muted' : '' }}">{{ $reptile->type_info ?: 'Belum tersedia' }}</div>
                    </div>
                </div>

                <div class="desc-info-card">
                    <div class="desc-info-icon amber">
                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div class="desc-info-content">
                        <div class="desc-info-label">Umur</div>
                        <div class="desc-info-value {{ empty($ageVal) ? 'muted' : '' }}">{{ $ageVal ?: 'Belum tersedia' }}</div>
                    </div>
                </div>

                <div class="desc-info-card">
                    <div class="desc-info-icon blue">
                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" /></svg>
                    </div>
                    <div class="desc-info-content">
                        <div class="desc-info-label">Berat</div>
                        <div class="desc-info-value {{ empty($weightVal) ? 'muted' : '' }}">{{ $weightVal ?: 'Belum tersedia' }}</div>
                    </div>
                </div>

                <div class="desc-info-card">
                    <div class="desc-info-icon rose">
                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" /></svg>
                    </div>
                    <div class="desc-info-content">
                        <div class="desc-info-label">Ukuran Maks</div>
                        <div class="desc-info-value {{ empty($reptile->max_size) ? 'muted' : '' }}">{{ $reptile->max_size ?: 'Belum tersedia' }}</div>
                    </div>
                </div>

                <div class="desc-info-card">
                    <div class="desc-info-icon teal">
                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.871 4A17.926 17.926 0 003 12c0 2.874.673 5.59 1.871 8m14.258 0A17.926 17.926 0 0021 12c0-2.874-.673-5.59-1.871-8M9 9h1.246a1 1 0 01.961.725l1.586 5.55a1 1 0 00.961.725H15m1-7h-.08a2 2 0 00-1.519.698L9.6 15.302A2 2 0 018.08 16H8" /></svg>
                    </div>
                    <div class="desc-info-content">
                        <div class="desc-info-label">Umur Hidup</div>
                        <div class="desc-info-value {{ empty($reptile->lifespan) ? 'muted' : '' }}">{{ $reptile->lifespan ?: 'Belum tersedia' }}</div>
                    </div>
                </div>
            </div>

            <div class="desc-content-block">
                <div class="desc-content-title">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#6B8E4E" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" /></svg>
                    Karakteristik
                    <span class="tag">Ciri & Sifat</span>
                </div>
                <div class="desc-content-text {{ empty($reptile->characteristic) ? 'empty' : '' }}">
                    {{ $reptile->characteristic ?: 'Informasi karakteristik untuk unit ini belum tersedia. Silakan hubungi admin via WhatsApp untuk detail lebih lanjut.' }}
                </div>
            </div>

            <div class="desc-content-block">
                <div class="desc-content-title">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#6B8E4E" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    Habitat Asli
                    <span class="tag">Asal & Lingkungan</span>
                </div>
                <div class="desc-content-text {{ empty($reptile->habitat) ? 'empty' : '' }}">
                    {{ $reptile->habitat ?: 'Informasi habitat untuk unit ini belum tersedia. Silakan hubungi admin via WhatsApp untuk detail lebih lanjut.' }}
                </div>
            </div>

            <div class="desc-content-block">
                <div class="desc-content-title">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#6B8E4E" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3l1.664 1.664M21 21l-1.5-1.5m-5.485-1.242L12 17.25 4.5 21l1.5-7.5L1.5 9l7.5-.75L12 1.5l3 6.75L22.5 9l-5.5 4.5z" /></svg>
                    Makanan (Pakan)
                    <span class="tag">Diet & Nutrisi</span>
                </div>
                <div class="desc-content-text {{ empty($reptile->food) ? 'empty' : '' }}">
                    {{ $reptile->food ?: 'Informasi pakan untuk unit ini belum tersedia. Silakan hubungi admin via WhatsApp untuk detail lebih lanjut.' }}
                </div>
            </div>

            <div class="desc-content-block">
                <div class="desc-content-title">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#6B8E4E" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                    Cara Perawatan
                    <span class="tag">Setup & Handling</span>
                </div>
                <div class="desc-content-text {{ empty($reptile->care_instructions) ? 'empty' : '' }}">
                    {{ $reptile->care_instructions ?: 'Informasi cara perawatan untuk unit ini belum tersedia. Silakan hubungi admin via WhatsApp untuk konsultasi setup & handling.' }}
                </div>
            </div>
        </div>
    </div>

    {{-- Ulasan Pelanggan --}}
    <div style="width: calc(100% - 40px); max-width: 100% !important; margin: 0 auto 30px auto; padding: 30px;">
        <div style="background: #0F1A0F; border-radius: 24px; padding: 50px; color: #fff;">
            <div style="margin-bottom: 35px;">
                <h3 style="font-size: 1.6rem; font-weight: 800; margin: 0 0 5px; color: #C9E4A4;">Ulasan Pelanggan</h3>
                <p style="margin: 0; font-size: 0.85rem; color: #A3C293;">Berikan penilaian dan baca pengalaman pembeli lain.</p>
            </div>

            <div style="display: grid; grid-template-columns: 35% 65%; gap: 40px; align-items: start;">
                {{-- KOLOM KIRI: Ringkasan Rating + Auth --}}
                <div>
                    {{-- Ringkasan Rating --}}
                    <div style="background: #1A2A1A; border: 1px solid #2A3F2A; border-radius: 16px; padding: 30px; text-align: center; margin-bottom: 25px;">
                        <div style="font-size: 3rem; font-weight: 800; color: #C9E4A4; line-height: 1; margin-bottom: 5px;">{{ number_format($averageRating, 1) }}</div>
                        <div style="font-size: 1.1rem; color: #C9E4A4; margin-bottom: 8px; letter-spacing: 3px;">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= round($averageRating)) ★ @else ☆ @endif
                            @endfor
                        </div>
                        <div style="font-size: 0.85rem; color: #6B8E4E;">/ 5 &bull; {{ $totalReviews }} ulasan</div>
                    </div>

                    {{-- Form/Tombol Tulis Ulasan berdasarkan Auth --}}
                    <div style="background: #1A2A1A; border: 1px solid #2A3F2A; border-radius: 16px; padding: 25px;">
                        <h4 style="margin: 0 0 15px; font-size: 1rem; font-weight: 800; color: #C9E4A4;">Tulis Ulasan</h4>
                        @if(session('review_success'))
                            <div style="background: #DEF7EC; color: #03543F; border: 1px solid #BBE5C5; padding: 10px 14px; border-radius: 8px; font-size: 0.8rem; font-weight: 600; margin-bottom: 15px;">{{ session('review_success') }}</div>
                        @endif
                        @auth
                            <form action="{{ route('products.review', $reptile->id) }}" method="POST">
                                @csrf
                                <div style="margin-bottom: 14px;">
                                    <label style="display: block; margin-bottom: 4px; font-size: 0.8rem; font-weight: 600; color: #A3C293;">Nama Anda</label>
                                    <input type="text" name="customer_name" value="{{ Auth::user()->name }}" readonly style="width: 100%; padding: 10px 12px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #888; font-size: 0.85rem; outline: none; cursor: not-allowed;">
                                </div>
                                <div style="margin-bottom: 14px;">
                                    <label style="display: block; margin-bottom: 4px; font-size: 0.8rem; font-weight: 600; color: #A3C293;">Rating</label>
                                    <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                                        @for($i = 1; $i <= 5; $i++)
                                            <label style="display: flex; align-items: center; gap: 3px; cursor: pointer; font-size: 1rem; color: #C9E4A4; background: #0F1A0F; border: 1px solid #2A3F2A; border-radius: 6px; padding: 4px 10px;">
                                                <input type="radio" name="rating" value="{{ $i }}" {{ $i === 5 ? 'checked' : '' }} style="accent-color: #6B8E4E; margin: 0;">
                                                {{ $i }}
                                            </label>
                                        @endfor
                                    </div>
                                </div>
                                <div style="margin-bottom: 14px;">
                                    <label style="display: block; margin-bottom: 4px; font-size: 0.8rem; font-weight: 600; color: #A3C293;">Komentar</label>
                                    <textarea name="comment" required maxlength="2000" rows="3" placeholder="Bagikan pengalaman Anda..." style="width: 100%; padding: 10px 12px; background: #0F1A0F; border: 1px solid #2A3F2A; border-radius: 8px; color: #fff; font-size: 0.85rem; outline: none; resize: vertical; font-family: inherit;"></textarea>
                                </div>
                                <button type="submit" style="width: 100%; background: #6B8E4E; color: #fff; border: none; padding: 11px; border-radius: 8px; font-weight: 700; font-size: 0.85rem; cursor: pointer;">Kirim Ulasan</button>
                            </form>
                        @else
                            <div style="background: rgba(255,255,255,0.05); border: 1px dashed rgba(255,255,255,0.2); padding: 25px; border-radius: 12px; text-align: center; margin-top: 5px;">
                                <p style="color: #BBE5C5; font-size: 0.9rem; margin-bottom: 15px; font-weight: 600;">Log in untuk membagikan pengalaman Anda dengan produk ini.</p>
                                <a href="{{ route('login') }}" style="background: #6B8E4E; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 700; font-size: 0.85rem; display: inline-block;">→ Masuk Akun</a>
                            </div>
                        @endauth
                    </div>
                </div>

                {{-- KOLOM KANAN: Daftar Ulasan --}}
                <div style="display: flex; flex-direction: column; gap: 14px; padding-right: 10px; padding-bottom: 10px;">
                    @forelse($reviews as $review)
                        <div style="background: #1A2A1A; border: 1px solid #2A3F2A; border-radius: 14px; padding: 22px; border-bottom: 3px solid #2A3F2A;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                                <div style="font-weight: 700; color: #C9E4A4; font-size: 0.95rem;">{{ $review->customer_name }}</div>
                                <div style="font-size: 0.75rem; color: #6B8E4E;">{{ \Carbon\Carbon::parse($review->created_at)->format('d M Y') }}</div>
                            </div>
                            <div style="font-size: 0.95rem; color: #C9E4A4; margin-bottom: 10px; letter-spacing: 2px;">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $review->rating) ★ @else ☆ @endif
                                @endfor
                            </div>
                            <p style="margin: 0; font-size: 0.88rem; color: #B0C4A0; line-height: 1.7;">{{ $review->comment }}</p>
                        </div>
                    @empty
                        <div style="background: #1A2A1A; border: 1px solid #2A3F2A; border-radius: 14px; padding: 35px; text-align: center; color: #6B8E4E; font-size: 0.9rem;">
                            Belum ada ulasan untuk produk ini. Jadilah yang pertama memberikan ulasan!
                        </div>
                    @endforelse
                    @if ($reviews->hasPages())
                        <div style="display: flex; align-items: center; justify-content: center; gap: 15px; margin-top: 25px;" class="custom-review-pagination">

                            @if ($reviews->onFirstPage())
                                <span style="color: rgba(255,255,255,0.2); background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; border-radius: 50%; cursor: not-allowed; font-weight: bold;">&lt;</span>
                            @else
                                <a href="{{ $reviews->previousPageUrl() }}" style="color: white; background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; border-radius: 50%; text-decoration: none; font-weight: bold; transition: all 0.2s;" onmouseover="this.style.background='#6B8E4E'" onmouseout="this.style.background='rgba(255,255,255,0.08)'">&lt;</a>
                            @endif

                            <div style="display: flex; align-items: center; gap: 8px;">
                                @for ($i = 1; $i <= $reviews->lastPage(); $i++)
                                    @if ($i == $reviews->currentPage())
                                        <span style="display: inline-block; width: 10px; height: 10px; background: #6B8E4E; border-radius: 50%; box-shadow: 0 0 8px #6B8E4E;" title="Halaman {{ $i }}"></span>
                                    @else
                                        <a href="{{ $reviews->url($i) }}" style="display: inline-block; width: 8px; height: 8px; background: rgba(255,255,255,0.25); border-radius: 50%; transition: all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.6)'" onmouseout="this.style.background='rgba(255,255,255,0.25)'" title="Ke halaman {{ $i }}"></a>
                                    @endif
                                @endfor
                            </div>

                            @if ($reviews->hasMorePages())
                                <a href="{{ $reviews->nextPageUrl() }}" style="color: white; background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; border-radius: 50%; text-decoration: none; font-weight: bold; transition: all 0.2s;" onmouseover="this.style.background='#6B8E4E'" onmouseout="this.style.background='rgba(255,255,255,0.08)'">&gt;</a>
                            @else
                                <span style="color: rgba(255,255,255,0.2); background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; border-radius: 50%; cursor: not-allowed; font-weight: bold;">&gt;</span>
                            @endif

                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        const OWNER_PHONE = "62895613369443";

        function orderSpesifik(name, price, productId) {
            const payload = {
                product_name: name,
                price: price,
                qty: 1,
                product_id: productId || ''
            };
            fetch('{{ route("checkout.quick") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify(payload)
            }).then(res => res.json()).then(data => {
                if (data.success && data.wa_url) {
                    window.open(data.wa_url, '_blank');
                }
            }).catch(() => {
                const formatted = Number(price).toLocaleString('id-ID');
                const msg = `Halo Kak, saya sudah membaca detail unitnya di website. Saya berminat memesan:\n\n*Unit:* ${name}\n*Harga:* Rp.${formatted}\n\nBagaimana metode pembayarannya?`;
                window.open(`https://wa.me/${OWNER_PHONE}?text=${encodeURIComponent(msg)}`, '_blank');
            });
        }

        function addToCartFromDetail(id, name, price, image) {
            let localCart = localStorage.getItem('anarcyx_cart') ? JSON.parse(localStorage.getItem('anarcyx_cart')) : [];
            const existingIndex = localCart.findIndex(item => item.id === id);

            if (existingIndex > -1) {
                localCart[existingIndex].qty += 1;
            } else {
                localCart.push({
                    id: id,
                    name: name,
                    price: parseFloat(price),
                    qty: 1,
                    image: image
                });
            }

            localStorage.setItem('anarcyx_cart', JSON.stringify(localCart));

            const cartBadge = document.getElementById('cartCount') || document.querySelector('.cart-count');
            if (cartBadge) {
                const totalQty = localCart.reduce((acc, item) => acc + item.qty, 0);
                cartBadge.innerText = totalQty;
            }

            showToastFromDetail('"' + name + '" berhasil dimasukkan ke keranjang belanja!');
        }

        function showToastFromDetail(message) {
            const toast = document.createElement('div');
            toast.className = 'custom-toast';
            toast.innerHTML = `<span class="toast-icon">✓</span> <span>${message}</span>`;
            document.body.appendChild(toast);

            setTimeout(() => toast.classList.add('show'), 50);
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 400);
            }, 3000);
        }
    </script>
@endpush