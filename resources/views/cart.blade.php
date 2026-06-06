@extends('layouts.public')

@section('title', 'Keranjang Belanja - AnarcyxReptile')

@push('styles')
    <style>
        .cart-empty-wrapper {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 100px 20px;
            width: 100%;
            min-height: 55vh;
        }
        .cart-empty-icon-circle {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: #d4edda;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 24px;
        }
        .cart-empty-icon-circle svg {
            width: 48px;
            height: 48px;
            color: #155724;
        }
        .cart-empty-headline {
            font-size: 1.6rem;
            font-weight: 700;
            color: #111111;
            margin-bottom: 8px;
            letter-spacing: -0.3px;
        }
        .cart-empty-subtitle {
            font-size: 0.95rem;
            color: #6c757d;
            margin-bottom: 30px;
        }
        .btn-cart-empty-browse {
            background-color: #283221;
            color: #FFFFFF;
            padding: 16px 50px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 700;
            font-size: 0.95rem;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(40, 50, 33, 0.15);
        }
        .btn-cart-empty-browse:hover {
            background-color: #3b4930;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(40, 50, 33, 0.25);
        }
        .cart-header-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .btn-clear-all {
            background-color: rgba(220, 38, 38, 0.1);
            border: none;
            color: #dc2626;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 700;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .btn-clear-all:hover {
            background-color: #dc2626;
            color: #ffffff;
            transform: translateY(-1px);
        }
    </style>
@endpush

@section('content')

    <div id="authStatusData" data-authenticated="{{ auth()->check() ? 'true' : 'false' }}" data-name="{{ auth()->check() ? auth()->user()->name : '' }}" data-phone="{{ auth()->check() ? (auth()->user()->phone_number ?? '') : '' }}" data-address="{{ auth()->check() ? (auth()->user()->address ?? '') : '' }}" style="display: none;"></div>

    <main>
        <div class="cart-page-wrapper" id="mainCartPageLayout">
            
            <section class="cart-items-side" id="cartItemsSection">
                    <div id="cartItemsContainer"></div>
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
                    
                    @auth
                        <button class="btn-main" style="width: 100%; padding: 15px; margin-top: 25px;" onclick="checkoutWhatsApp()">
                            Checkout to WhatsApp &rarr;
                        </button>
                    @else
                        <button type="button" class="btn-main" style="width: 100%; padding: 15px; margin-top: 25px;" onclick="showLoginModal()">
                            Checkout to WhatsApp &rarr;
                        </button>
                    @endauth
                </div>
            </section>

        </div>
    </main>

    {{-- Login Confirmation Modal --}}
    <div id="checkoutLoginModal" style="display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.75); backdrop-filter: blur(4px); align-items: center; justify-content: center;">
        <div style="background: #1A2315; border: 2px solid #6B8E4E; border-radius: 16px; padding: 30px; width: 90%; max-width: 420px; text-align: center; box-shadow: 0 10px 30px rgba(0,0,0,0.5); font-family: sans-serif;">
            <div style="margin-bottom: 15px;">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#6B8E4E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: inline-block;">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
            </div>
            <h3 style="color: #FFFFFF; font-size: 1.25rem; margin: 0 0 10px 0; font-weight: 800;">Konfirmasi Login</h3>
            <p style="color: #BBE5C5; font-size: 0.9rem; line-height: 1.5; margin: 0 0 25px 0;">Anda harus masuk ke akun Anda terlebih dahulu untuk melanjutkan proses transaksi dan mengamankan data pesanan.</p>
            
            <div style="display: flex; gap: 12px; justify-content: center;">
                <button onclick="closeLoginModal()" style="flex: 1; background: transparent; color: #BBE5C5; border: 1px solid rgba(255,255,255,0.2); padding: 12px; border-radius: 8px; font-weight: 700; font-size: 0.85rem; cursor: pointer; transition: all 0.2s;">Batal</button>
                <a href="{{ route('login') }}" style="flex: 1; background: #6B8E4E; color: white; border: none; padding: 12px; border-radius: 8px; font-weight: 700; font-size: 0.85rem; text-decoration: none; display: flex; align-items: center; justify-content: center; transition: all 0.2s;" onmouseover="this.style.background='#55723E'">Login Sekarang</a>
            </div>
        </div>
    </div>

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
        const OWNER_PHONE = "62895613369443";
        const SHIPPING_COST = 20000;

        // Sinkronkan data keranjang dari localStorage
        let localCart = localStorage.getItem('anarcyx_cart') 
            ? JSON.parse(localStorage.getItem('anarcyx_cart')) 
            : [];

        let itemIndexToDelete = null;

        // Fungsi Render Layout Dinamis
        function renderCart() {
            localStorage.setItem('anarcyx_cart', JSON.stringify(localCart));

            const totalQty = localCart.reduce((acc, item) => acc + item.qty, 0);
            const totalBadge = document.getElementById('cartCount') || document.querySelector('.cart-count');
            if (totalBadge) totalBadge.innerText = totalQty;

            const mainLayout = document.getElementById('mainCartPageLayout');
            if (!mainLayout) return;

            // 1. JIKA KERANJANG KOSONG -> TAMPILKAN DESAIN REVISI KOSONG
            if (localCart.length === 0) {
                mainLayout.className = "";
                mainLayout.innerHTML = `
                    <div class="cart-empty-wrapper">
                        <div class="cart-empty-icon-circle">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H3.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 014.513 7.5h14.974c.577 0 1.058.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                            </svg>
                        </div>
                        <h2 class="cart-empty-headline">Your Cart is Empty</h2>
                        <p class="cart-empty-subtitle">Add some animal to your cart to get started</p>
                        <a href="/shop" class="btn-cart-empty-browse">Browse Animal</a>
                    </div>
                `;
                return;
            }

            // 2. JIKA KERANJANG ADA ISINYA -> CETAK STRUKTUR HALAMAN UTAMANYA
            mainLayout.className = "cart-page-wrapper";
            mainLayout.innerHTML = `
                <section class="cart-items-side" id="cartItemsSection">
                    <div id="cartItemsContainer"></div>
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
                        
                        <button class="btn-main" style="width: 100%; padding: 15px; margin-top: 25px;" onclick="handleCheckout()">
                            Checkout to WhatsApp &rarr;
                        </button>
                    </div>
                </section>
            `;

            // 3. SEKARANG AMBIL ELEMEN WADAH YANG BARU DICETAK DI ATAS UNTUK LOOPING ITEM
            const itemsContainer = document.getElementById('cartItemsContainer');
            if (!itemsContainer) return;

            let htmlContent = `
                    <div class="cart-header-row" style="margin-bottom: 25px;">
                    <h2 class="cart-title" style="margin:0;">Your Order Cart</h2>
                    <button class="btn-clear-all" onclick="clearAllCart()">Hapus Semua Produk</button>
                </div>
            `;
            let subtotal = 0;

            localCart.forEach((item, index) => {
                const itemTotal = item.price * item.qty;
                subtotal += itemTotal;

                htmlContent += `
                    <div class="cart-item-card">
                        <img src="${item.image.startsWith('http') ? item.image : '/images/products/' + item.image}" class="cart-item-img" alt="${item.name}">
                        <div class="cart-item-info-block">
                            <span class="cart-item-name">${item.name}</span>
                            <span class="cart-item-id">Species: ${item.sciname || 'Exotic Pet'}</span>
                        </div>
                        <div class="cart-quantity-block">
                            <button onclick="updateQty(${index}, -1)">-</button>
                            <span>${item.qty}</span>
                            <button onclick="updateQty(${index}, 1)">+</button>
                        </div>
                        <div class="cart-item-price-block">Rp ${itemTotal.toLocaleString('id-ID')}</div>
                        <button class="cart-remove-btn" onclick="removeItem(${index})">&times;</button>
                    </div>
                `;
            });

            // Suntikkan list htmlContent baru ke dalam wadah item container
            itemsContainer.innerHTML = htmlContent;

            // Perbarui teks harga ringkasan nota belanjaan
            const subtotalElement = document.getElementById('summarySubtotal');
            const totalElement = document.getElementById('summaryTotal');
            
            if (subtotalElement) subtotalElement.innerText = `Rp ${subtotal.toLocaleString('id-ID')}`;
            if (totalElement) totalElement.innerText = `Rp ${(subtotal + SHIPPING_COST).toLocaleString('id-ID')}`;
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

        function handleCheckout() {
            const authStatus = document.getElementById('authStatusData');
            const isAuthenticated = authStatus && authStatus.getAttribute('data-authenticated') === 'true';
            if (!isAuthenticated) {
                showLoginModal();
                return;
            }
            checkoutWhatsApp();
        }

        function showLoginModal() {
            const modal = document.getElementById('checkoutLoginModal');
            if (modal) modal.style.display = 'flex';
        }

        function closeLoginModal() {
            const modal = document.getElementById('checkoutLoginModal');
            if (modal) modal.style.display = 'none';
        }

        window.onclick = function(event) {
            const modal = document.getElementById('checkoutLoginModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }

        function checkoutWhatsApp() {
            let listItemsText = localCart.map((item, idx) => 
                `${idx + 1}. *${item.name}* (${item.qty} ekor) -> Rp ${(item.price * item.qty).toLocaleString('id-ID')}`
            ).join('\n');
            let totalAkhir = localCart.reduce((acc, item) => acc + (item.price * item.qty), 0) + SHIPPING_COST;

            const message = `Halo AnarcyxReptile, saya ingin memesan unit reptile berikut:\n\n` +
                            `${listItemsText}\n\n` +
                            `� Ongkos Kirim: Rp ${SHIPPING_COST.toLocaleString('id-ID')}\n` +
                            `� *Total Tagihan:* Rp ${totalAkhir.toLocaleString('id-ID')}\n\n` +
                            `Mohon dibantu infokan langkah pembayarannya. Terima kasih!`;

            const authData = document.getElementById('authStatusData');
            const isAuth = authData && authData.getAttribute('data-authenticated') === 'true';
            let customerName, customerPhone, customerAddress;
            if (isAuth) {
                customerName = authData.getAttribute('data-name') || 'Guest User';
                customerPhone = authData.getAttribute('data-phone') || '';
                customerAddress = authData.getAttribute('data-address') || '';
            } else {
                customerName = localStorage.getItem('anarcyx_customer_name') || 'Guest User';
                customerPhone = localStorage.getItem('anarcyx_customer_phone') || '';
                customerAddress = localStorage.getItem('anarcyx_customer_address') || '';
            }
            const payload = {
                customer_name: customerName,
                customer_phone: customerPhone,
                customer_address: customerAddress,
                shipping_cost: SHIPPING_COST,
                items: localCart.map(item => ({
                    product_id: item.id,
                    product_name: item.name,
                    qty: item.qty,
                    price: item.price
                }))
            };

            fetch('{{ route("checkout.submit") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify(payload)
            }).then(res => res.json()).then(data => {
                if (data.success) {
                    window.open(`https://wa.me/${OWNER_PHONE}?text=${encodeURIComponent(message)}`, '_blank');
                    localStorage.removeItem('anarcyx_cart');
                    localStorage.removeItem('anarcyx_customer_name');
                    localStorage.removeItem('anarcyx_customer_phone');
                    localStorage.removeItem('anarcyx_customer_address');
                    window.location.href = '{{ route("order.success") }}';
                }
            }).catch(() => {
                window.open(`https://wa.me/${OWNER_PHONE}?text=${encodeURIComponent(message)}`, '_blank');
                localStorage.removeItem('anarcyx_cart');
                window.location.href = '{{ route("order.success") }}';
            });
        }

        function clearAllCart() {
            const overlay = document.getElementById('deleteConfirmOverlay');
            if (overlay) {
                overlay.classList.add('show');

                const titleText = overlay.querySelector('.confirm-title-text');
                if (titleText) titleText.innerText = "Apakah yakin ingin mengosongkan seluruh keranjang belanjamu?";

                const btnYes = document.getElementById('btnConfirmDeleteYes');
                if (btnYes) {
                    btnYes.onclick = function() {
                        localStorage.removeItem('anarcyx_cart');
                        localCart = [];
                        closeDeleteModal();
                        renderCart();
                    };
                }
            }
        }

        document.addEventListener("DOMContentLoaded", renderCart);
    </script>
@endpush