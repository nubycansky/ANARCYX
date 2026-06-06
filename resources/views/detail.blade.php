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