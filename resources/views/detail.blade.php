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
            
            <h3 style="margin-bottom: 10px; color: #111;">Deskripsi Singkat</h3>
            <p class="detail-desc-text">
                {{ $reptile->description ?? 'Reptil premium sehat, aktif, makan lancar, dan sudah melalui proses karantina ketat oleh tim ahli ahli kami.' }}
            </p>

            <div style="max-width: 350px;">
                <button class="btn-action btn-quick-order" style="width: 100%; padding: 15px;" onclick="orderSpesifik('{{ $reptile->name }}', '{{ $reptile->price }}')">
                    Beli Lewat WhatsApp Owner &rarr;
                </button>
                <button class="btn-action btn-add-cart" style="width: 100%; padding: 15px; margin-top: 12px;" onclick="addToCartFromDetail('{{ $reptile->id }}', '{{ $reptile->name }}', '{{ $reptile->price }}', '{{ $reptile->image }}')">
                    Add to Cart 🛒
                </button>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        const OWNER_PHONE = "6281234567890";

        function orderSpesifik(name, price) {
            const formatted = Number(price).toLocaleString('id-ID');
            const msg = `Halo Kak, saya sudah membaca detail unitnya di website. Saya berminat memesan:\n\n*Unit:* ${name}\n*Harga:* Rp.${formatted}\n\nBagaimana metode pembayarannya?`;
            window.open(`https://wa.me/${OWNER_PHONE}?text=${encodeURIComponent(msg)}`, '_blank');
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