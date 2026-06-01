@extends('layouts.public')

@section('title', 'Keranjang Belanja - AnarcyxReptile')

@push('styles')
    <style>
        /* --- PERBAIKAN VISUAL MINIMALIS: CART KOSONG SESUAI REVISI FIGMA --- */
        .cart-empty-wrapper {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 100px 20px;
            width: 100%;
            min-height: 55vh; /* Membuat konten otomatis berada pas di tengah-tengah layar */
        }

        /* Desain Ikon Keranjang Tipis (Outline Abu-abu) */
        .cart-empty-icon-box {
            color: #A3A3A3; /* Abu-abu tipis elegan */
            margin-bottom: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .cart-empty-icon-box svg {
            width: 80px;
            height: 80px;
            stroke-width: 1.2; /* Ketebalan garis tipis sesuai figma baru */
        }

        /* Teks Pemberitahuan "Your cart is empty" */
        .cart-empty-headline {
            font-size: 1.6rem;
            font-weight: 700;
            color: #111111;
            margin-bottom: 30px;
            letter-spacing: -0.3px;
        }

        /* Tombol "Shop Now" Kaku Persegi Premium */
        .btn-cart-empty-shop {
            background-color: #283221; /* Hijau bumi gelap solid */
            color: #FFFFFF;
            padding: 16px 50px;
            border-radius: 6px; /* Sudut sedikit melengkung kaku profesional */
            text-decoration: none;
            font-weight: 700;
            font-size: 0.95rem;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(40, 50, 33, 0.15);
        }

        .btn-cart-empty-shop:hover {
            background-color: #3b4930;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(40, 50, 33, 0.25);
        }
    </style>
@endpush

@section('content')

    <main>
        <div class="cart-page-wrapper" id="mainCartPageLayout">
            
            <section class="cart-items-side" id="cartItemsSection">
                <h2 class="cart-title">Your Order Cart</h2>
                <div id="cartItemsContainer">
                    </div>
            </section>

            <section class="cart-summary-side" id="cartSummarySection">
                <div class="cart-summary-block">
                    <h3 class="cart-summary-title">Summary</h3>
                    <div class="cart-subtotal-row">
                        <span>Subtotal</span>
                        <span id="summarySubtotal">Rp 0</span>
                    </div>
                    <div class="cart-subtotal-row">
                        <span>Biaya Pengiriman</span>
                        <span id="summaryShipping">Rp 20.000</span>
                    </div>
                    
                    <div class="cart-coupon-flex">
                        <input type="text" class="cart-coupon-input" placeholder="Kode Kupon / Voucher">
                        <button class="btn-coupon-apply">Apply</button>
                    </div>
                    
                    <div class="cart-subtotal-row total">
                        <span>Total Keseluruhan</span>
                        <span id="summaryTotal">Rp 0</span>
                    </div>
                    
                    <button class="btn-main" style="width: 100%; padding: 15px; margin-top: 25px;" onclick="checkoutWhatsApp()">
                        Checkout to WhatsApp &rarr;
                    </button>
                </div>
            </section>

        </div>
    </main>

    <div class="confirm-overlay" id="deleteConfirmOverlay">
        <div class="confirm-box">
            <div class="confirm-icon-circle">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </div>
            <div class="confirm-title-text">Apakah yakin ingin menghapus produk ini dari keranjangmu?</div>
            <div class="confirm-buttons-flex">
                <button class="btn-confirm-yes" id="btnConfirmDeleteYes">Ya, Saya Yakin</button>
                <button class="btn-confirm-cancel" onclick="closeDeleteModal()">Batalkan</button>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        const OWNER_PHONE = "6281234567890";
        const SHIPPING_COST = 20000;

        // localCart sudah di-deklarasikan global di navbar-scripts.blade.php
        // Sinkronkan ulang dengan localStorage untuk memastikan data terbaru (misal setelah Add to Cart)
        localCart = localStorage.getItem('anarcyx_cart') 
            ? JSON.parse(localStorage.getItem('anarcyx_cart')) 
            : [];

        let itemIndexToDelete = null;

        // Fungsi Render Layout Dinamis
        function renderCart() {
            localStorage.setItem('anarcyx_cart', JSON.stringify(localCart));

            const mainLayout = document.getElementById('mainCartPageLayout');
            const itemsContainer = document.getElementById('cartItemsContainer');
            const summarySection = document.getElementById('cartSummarySection');
            const totalBadge = document.getElementById('cartCount');
            
            const totalQty = localCart.reduce((acc, item) => acc + item.qty, 0);
            totalBadge.innerText = totalQty;

            // JIKA KERANJANG KOSONG -> RENDER DESAIN MINIMALIS BARU (GAMBAR REVISI)
            if (localCart.length === 0) {
                mainLayout.className = ""; // Copot grid 2 kolom
                mainLayout.innerHTML = `
                    <div class="cart-empty-wrapper">
                        <div class="cart-empty-icon-box">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H3.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 014.513 7.5h14.974c.577 0 1.058.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                            </svg>
                        </div>
                        <h2 class="cart-empty-headline">Your cart is empty</h2>
                        <a href="{{ route('shop') }}" class="btn-cart-empty-shop">Shop Now</a>
                    </div>
                `;
                return;
            }

            // JIKA KERANJANG BERISI ITEM
            let htmlContent = '';
            let subtotal = 0;

            localCart.forEach((item, index) => {
                const itemTotal = item.price * item.qty;
                subtotal += itemTotal;

                htmlContent += `
                    <div class="cart-item-card">
                        <img src="${item.image}" class="cart-item-img" alt="${item.name}">
                        <div class="cart-item-info-block">
                            <span class="cart-item-name">${item.name}</span>
                            <span class="cart-item-id">Species: ${item.sciname}</span>
                        </div>
                        <div class="cart-quantity-block">
                            <button onclick="updateQty(${index}, -1)">-</button>
                            <span>${item.qty}</span>
                            <button onclick="updateQty(${index}, 1)">+</button>
                        </div>
                        <div class="cart-item-price-block">Rp ${(itemTotal).toLocaleString('id-ID')}</div>
                        <button class="cart-remove-btn" onclick="removeItem(${index})">&times;</button>
                    </div>
                `;
            });

            itemsContainer.innerHTML = htmlContent;
            document.getElementById('summarySubtotal').innerText = `Rp ${subtotal.toLocaleString('id-ID')}`;
            document.getElementById('summaryTotal').innerText = `Rp ${(subtotal + SHIPPING_COST).toLocaleString('id-ID')}`;
        }

        function updateQty(index, change) {
            localCart[index].qty += change;
            if (localCart[index].qty < 1) {
                removeItem(index);
                return;
            }
            renderCart();
        }

        function removeItem(index) {
            itemIndexToDelete = index;
            document.getElementById('deleteConfirmOverlay').classList.add('show');
        }

        function closeDeleteModal() {
            document.getElementById('deleteConfirmOverlay').classList.remove('show');
            itemIndexToDelete = null;
        }

        document.getElementById('btnConfirmDeleteYes').addEventListener('click', function() {
            if (itemIndexToDelete !== null) {
                localCart.splice(itemIndexToDelete, 1);
                closeDeleteModal();
                renderCart();
            }
        });

        function checkoutWhatsApp() {
            let listItemsText = localCart.map((item, idx) => 
                `${idx + 1}. *${item.name}* (${item.qty} ekor) -> Rp ${(item.price * item.qty).toLocaleString('id-ID')}`
            ).join('\n');
            let totalAkhir = localCart.reduce((acc, item) => acc + (item.price * item.qty), 0) + SHIPPING_COST;

            const message = `Halo AnarcyxReptile, saya ingin memesan unit reptile berikut:\n\n` +
                            `${listItemsText}\n\n` +
                            `• Ongkos Kirim: Rp ${SHIPPING_COST.toLocaleString('id-ID')}\n` +
                            `• *Total Tagihan:* Rp ${totalAkhir.toLocaleString('id-ID')}\n\n` +
                            `Mohon dibantu infokan langkah pembayarannya. Terima kasih!`;

            window.open(`https://wa.me/${OWNER_PHONE}?text=${encodeURIComponent(message)}`, '_blank');
        }

        document.addEventListener("DOMContentLoaded", renderCart);
    </script>
@endpush