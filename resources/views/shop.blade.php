@extends('layouts.public')

@section('title', 'Shop - AnarcyxReptile')

@push('styles')
@endpush

@section('content')

    <div class="search-section-wrapper">
        <div class="search-box-flex">
            <input type="text" id="shopSearchInput" placeholder="Cari sahabat reptil eksotismu di sini..." onkeyup="handleSearchLive()">
            <button class="btn-search-trigger" onclick="handleSearchLive()">Search</button>
        </div>
    </div>

    <div class="shop-container">
        
        <button class="filter-toggle-btn" id="filterToggle" onclick="toggleFilter()">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="6" x2="20" y2="6"/><line x1="8" y1="12" x2="20" y2="12"/><line x1="12" y1="18" x2="20" y2="18"/></svg>
            Filter
        </button>
        
        <aside class="filter-sidebar" id="filterSidebar">
            <div class="filter-section">
                <div class="filter-section-title">Jenis Reptil</div>
                <div class="filter-box-group">
                    <div class="filter-box-item" data-type="category" data-value="Iguana" onclick="toggleFilterBox(this)">
                        <span>Iguana</span>
                        <div class="indicator"></div>
                    </div>
                    <div class="filter-box-item" data-type="category" data-value="Gecko" onclick="toggleFilterBox(this)">
                        <span>Gecko</span>
                        <div class="indicator"></div>
                    </div>
                    <div class="filter-box-item" data-type="category" data-value="Snake" onclick="toggleFilterBox(this)">
                        <span>Snake</span>
                        <div class="indicator"></div>
                    </div>
                    <div class="filter-box-item" data-type="category" data-value="Tortoise" onclick="toggleFilterBox(this)">
                        <span>Tortoise</span>
                        <div class="indicator"></div>
                    </div>
                </div>
            </div>

            <div class="filter-section">
                <div class="filter-section-title">Harga Range</div>
                <div class="filter-box-group">
                    <div class="filter-box-item" data-type="price" data-min="0" data-max="500000" onclick="toggleFilterBox(this)">
                        <span>Under Rp 500k</span>
                        <div class="indicator"></div>
                    </div>
                    <div class="filter-box-item" data-type="price" data-min="500000" data-max="2000000" onclick="toggleFilterBox(this)">
                        <span>Rp 500k - Rp 2M</span>
                        <div class="indicator"></div>
                    </div>
                    <div class="filter-box-item" data-type="price" data-min="2000000" data-max="99999999" onclick="toggleFilterBox(this)">
                        <span>Above Rp 2M</span>
                        <div class="indicator"></div>
                    </div>
                </div>
            </div>

            <div class="filter-action-buttons">
                <button class="btn-filter-done" onclick="applyFilters()">Done</button>
                <button class="btn-filter-cancel" onclick="clearFilters()">Cancel Filtering</button>
            </div>
        </aside>

        <main class="products-grid-side">
            <div class="shop-product-grid-fix" id="productsContainer">
                </div>
        </main>

    </div>

@endsection

@push('scripts')
    <script>
        const OWNER_PHONE = "6281234567890";

        // Database Unit Reptil
        const ALL_PRODUCTS = [
            { id: "REP001", name: "Rhinoceros Iguana", sciname: "Cyclura cornuta", category: "Iguana", price: 350000, desc: "Karakter jinak khas badak, memiliki tanduk unik kecil di bagian hidung depan.", image: "https://images.unsplash.com/photo-1601758124510-52d02ddb7cbd?auto=format&fit=crop&q=80&w=400" },
            { id: "REP002", name: "Leopard Gecko Hypo", sciname: "Eublepharis macularius", category: "Gecko", price: 650000, desc: "Sangat cocok untuk pemula, warna kuning cerah bersih minim bintik hitam.", image: "https://images.unsplash.com/photo-1504450758481-7338eba7524a?auto=format&fit=crop&q=80&w=400" },
            { id: "REP003", name: "Ball Python Normal", sciname: "Python regius", category: "Snake", price: 1200000, desc: "Ular peliharaan paling tenang di dunia, bermotif eksotis alami.", image: "https://images.unsplash.com/photo-1531386151447-fd76ad50012f?auto=format&fit=crop&q=80&w=400" },
            { id: "REP004", name: "Sulcata Tortoise Baby", sciname: "Centrochelys sulcata", category: "Tortoise", price: 2500000, desc: "Kura-kura darat raksasa gurun, aktif berjemur dan napsu makan rakus.", image: "https://images.unsplash.com/photo-1518467166778-b88f373ffec7?auto=format&fit=crop&q=80&w=400" },
            { id: "REP005", name: "Green Iguana Premium", sciname: "Iguana iguana", category: "Iguana", price: 450000, desc: "Warna hijau daun pekat mengkilap, kondisi spike rapi tegak berdiri.", image: "https://images.unsplash.com/photo-1548247416-ec66f4900b2e?auto=format&fit=crop&q=80&w=400" },
            { id: "REP006", name: "Corn Snake Amel", sciname: "Pantherophis guttatus", category: "Snake", price: 1800000, desc: "Warna merah jingga menyala tanpa pigmen hitam, lincah dan mulus.", image: "https://images.unsplash.com/photo-1629191122802-990ff5b09569?auto=format&fit=crop&q=80&w=400" }
        ];

        let activeCategory = null;
        let activePriceRange = null;
        let searchQuery = "";

        function toggleFilterBox(element) {
            const type = element.getAttribute('data-type');
            const parentGroup = element.parentElement;
            const siblingItems = parentGroup.querySelectorAll('.filter-box-item');

            if (element.classList.contains('active')) {
                element.classList.remove('active');
                if (type === 'category') activeCategory = null;
                if (type === 'price') activePriceRange = null;
            } else {
                siblingItems.forEach(item => item.classList.remove('active'));
                element.classList.add('active');
                if (type === 'category') activeCategory = element.getAttribute('data-value');
                if (type === 'price') {
                    activePriceRange = {
                        min: parseInt(element.getAttribute('data-min')),
                        max: parseInt(element.getAttribute('data-max'))
                    };
                }
            }
        }

        function applyFilters() {
            let filtered = ALL_PRODUCTS;
            if (searchQuery) filtered = filtered.filter(p => p.name.toLowerCase().includes(searchQuery.toLowerCase()));
            if (activeCategory) filtered = filtered.filter(p => p.category === activeCategory);
            if (activePriceRange) filtered = filtered.filter(p => p.price >= activePriceRange.min && p.price <= activePriceRange.max);
            renderProducts(filtered);
        }

        function handleSearchLive() {
            searchQuery = document.getElementById('shopSearchInput').value;
            applyFilters();
        }

        function clearFilters() {
            activeCategory = null;
            activePriceRange = null;
            searchQuery = "";
            document.getElementById('shopSearchInput').value = "";
            document.querySelectorAll('.filter-box-item').forEach(item => item.classList.remove('active'));
            renderProducts(ALL_PRODUCTS);
        }

        // Inisialisasi basis data wishlist dari localStorage agar permanen saat reload
        let localWishlist = localStorage.getItem('anarcyx_wishlist')
            ? JSON.parse(localStorage.getItem('anarcyx_wishlist'))
            : [];

        // Fungsi klik tombol love (Favorit)
        function toggleWishlist(productId) {
            const index = localWishlist.indexOf(productId);
            
            if (index > -1) {
                // Jika sudah ada di favorit, hapus dari list
                localWishlist.splice(index, 1);
            } else {
                // Jika belum ada, masukkan ID produk ke daftar favorit
                localWishlist.push(productId);
            }
            
            // Simpan perubahan ke memori browser
            localStorage.setItem('anarcyx_wishlist', JSON.stringify(localWishlist));
            
            // Panggil ulang applyFilters untuk menyortir ulang posisi card secara real-time
            applyFilters();
        }

        // Fungsi Utama Render Produk (Sudah Dilengkapi Formula Urutan Favorit Teratas)
        function renderProducts(productsList) {
            const container = document.getElementById('productsContainer');
            if (productsList.length === 0) {
                container.innerHTML = `<div class="no-products">Reptil tidak ditemukan. Coba reset filter pencarianmu.</div>`;
                return;
            }

            // FORMULA SORTING: Produk yang di-love (ada di localWishlist) dipaksa naik ke index paling atas
            const sortedProducts = [...productsList].sort((a, b) => {
                const aFav = localWishlist.includes(a.id) ? 1 : 0;
                const bFav = localWishlist.includes(b.id) ? 1 : 0;
                return bFav - aFav; // Nilai 1 (Favorit) akan didorong ke atas Nilai 0
            });

            container.innerHTML = sortedProducts.map(product => {
                // Cek apakah produk ini sedang berstatus favorit atau tidak
                const isFavorite = localWishlist.includes(product.id) ? 'active' : '';

                return `
                    <div class="product-card">
                        <button class="wishlist-btn ${isFavorite}" onclick="toggleWishlist('${product.id}')">
                            <svg width="18" height="18" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.5 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                        </button>
                        <div class="product-img-wrapper">
                            <img src="${product.image}" alt="${product.name}">
                        </div>
                        <div class="product-info">
                            <span class="product-name">${product.name}</span>
                            <span class="product-price">Rp ${product.price.toLocaleString('id-ID')}</span>
                        </div>
                        <div class="product-sciname">${product.sciname}</div>
                        
                        <div class="badge-container">
                            <span class="badge">${product.category}</span>
                            <span class="badge empty"></span>
                        </div>

                        <p class="product-desc-snippet">${product.desc}</p>
                        
                        <div class="card-actions">
                            <button class="btn-action btn-add-cart" onclick="addToCart('${product.id}')">Add to Cart</button>
                            <button class="btn-action btn-quick-order" onclick="quickOrder('${product.name}', ${product.price})">Quick Order</button>
                        </div>
                    </div>
                `;
            }).join('');
        }

        // Fungsi Add to Cart - menyimpan data ke keranjang tanpa berpindah halaman
        function addToCart(productId) {
            // 1. Cari data objek produk berdasarkan ID yang diklik
            const product = ALL_PRODUCTS.find(p => p.id === productId);

            // 2. Ambil data keranjang lokal saat ini dari browser memory
            let localCart = localStorage.getItem('anarcyx_cart') ? JSON.parse(localStorage.getItem('anarcyx_cart')) : [];

            // 3. Cek apakah produk ini sudah pernah dimasukkan sebelumnya
            const existingIndex = localCart.findIndex(item => item.id === productId);

            if (existingIndex > -1) {
                // Jika sudah ada, cukup tambahkan jumlah unitnya (kuantitas)
                localCart[existingIndex].qty += 1;
            } else {
                // Jika belum ada, suntikkan data unit baru ke dalam array
                localCart.push({
                    id: product.id,
                    name: product.name,
                    sciname: product.sciname,
                    price: product.price,
                    qty: 1,
                    image: product.image
                });
            }

            // 4. Kunci dan simpan kembali state array terbaru ke localStorage permanen
            localStorage.setItem('anarcyx_cart', JSON.stringify(localCart));

            // 5. Perbarui angka badge counter di navbar atas secara real-time
            updateCartBadge();

            // 6. Beri feedback singkat kepada user tanpa berpindah halaman
            showAddToCartFeedback(product.name);
        }

        // Feedback visual toast mini setelah Add to Cart berhasil
        function showAddToCartFeedback(productName) {
            const existing = document.getElementById('cartFeedbackToast');
            if (existing) existing.remove();

            const toast = document.createElement('div');
            toast.id = 'cartFeedbackToast';
            toast.innerText = `"${productName}" ditambahkan ke keranjang`;
            toast.style.cssText = 'position:fixed;bottom:30px;right:30px;background:#283221;color:#fff;padding:14px 22px;border-radius:10px;font-weight:600;font-size:0.9rem;z-index:9999;box-shadow:0 8px 20px rgba(0,0,0,0.15);opacity:0;transition:opacity 0.25s ease;';
            document.body.appendChild(toast);

            requestAnimationFrame(() => { toast.style.opacity = '1'; });

            setTimeout(() => {
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 300);
            }, 2200);
        }

        function quickOrder(name, price) {
            const formattedPrice = Number(price).toLocaleString('id-ID');
            const textMessage = `Halo AnarcyxReptile, saya ingin memesan unit ini:\n\n• *Nama Unit:* ${name}\n• *Harga:* Rp ${formattedPrice}`;
            window.open(`https://wa.me/${OWNER_PHONE}?text=${encodeURIComponent(textMessage)}`, '_blank');
        }

        document.addEventListener("DOMContentLoaded", () => {
            renderProducts(ALL_PRODUCTS);
        });

        function toggleFilter() {
            document.getElementById('filterSidebar').classList.toggle('open');
            document.getElementById('filterToggle').classList.toggle('active');
        }
    </script>
@endpush