@extends('layouts.public')

@section('title', 'Shop - AnarcyxReptile')

@push('styles')
<style>
    .custom-toast {
        position: fixed; bottom: 20px; right: 20px;
        background-color: #283221; color: white;
        padding: 14px 24px; border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        display: flex; align-items: center; gap: 10px;
        font-weight: 600; font-size: 0.9rem; z-index: 9999;
        transform: translateY(100px); opacity: 0;
        transition: all 0.4s cubic-bezier(0.68, -0.55, 0.27, 1.55);
    }
    .custom-toast.show { transform: translateY(0); opacity: 1; }
    .toast-icon { color: #81c784; font-size: 1.2rem; }
</style>
@endpush

@section('content')

    <div class="search-section-wrapper">
        <div class="search-box-flex">
            <input type="text" id="shopSearchInput" placeholder="Cari sahabat reptil eksotismu di sini..." onkeyup="handleSearchLive()">
            <button class="btn-search-trigger" onclick="handleSearchLive()">Search</button>
        </div>
    </div>
    <div id="mongodb-products-data" data-json="{{ $products->toJson() }}" style="display: none;"></div>

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

        // Database Unit Reptil (dinamis dari MongoDB Atlas)
        const dataElement = document.getElementById('mongodb-products-data');
        const ALL_PRODUCTS = dataElement && dataElement.getAttribute('data-json')
            ? JSON.parse(dataElement.getAttribute('data-json')).map(p => {
                let productId = p.id || '';
                if (p._id) {
                    productId = typeof p._id === 'object' && p._id.$oid ? p._id.$oid : p._id.toString();
                }
                return { ...p, id: productId, sciname: p.sciname || 'Exotic Pet' };
            })
            : [];

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
                            <img src="${product.image.startsWith('http') ? product.image : '/images/products/' + product.image}" alt="${product.name}">
                        </div>
                        <div class="product-info">
                            <a href="/products/${product.id}" class="product-name" style="text-decoration: none; color: #111111; font-weight: 800; cursor: pointer;">${product.name}</a>
                            <span class="product-price">Rp ${product.price.toLocaleString('id-ID')}</span>
                        </div>
                            <div class="product-sciname">${product.sciname || 'Exotic Pet'}</div>
                        
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

        function addToCart(productId) {
            const product = ALL_PRODUCTS.find(p => p.id === productId);
            if (!product) return;

            let localCart = localStorage.getItem('anarcyx_cart') ? JSON.parse(localStorage.getItem('anarcyx_cart')) : [];
            const existingIndex = localCart.findIndex(item => item.id === productId);

            if (existingIndex > -1) {
                localCart[existingIndex].qty += 1;
            } else {
                localCart.push({
                    id: product.id,
                    name: product.name,
                    price: parseFloat(product.price),
                    qty: 1,
                    image: product.image
                });
            }

            localStorage.setItem('anarcyx_cart', JSON.stringify(localCart));

            // Perbarui Counter Navbar
            const totalQty = localCart.reduce((acc, item) => acc + item.qty, 0);
            const cartBadge = document.getElementById('cartCount') || document.querySelector('.cart-count');
            if (cartBadge) cartBadge.innerText = totalQty;

            // Munculkan Notifikasi Berhasil
            showToast(`"${product.name}" berhasil dimasukkan ke keranjang belanja!`);
        }

        function showToast(message) {
            const toast = document.createElement('div');
            toast.className = 'custom-toast';
            toast.innerHTML = `<span class="toast-icon">✓</span> <span>${message}</span>`;
            document.body.appendChild(toast);
            setTimeout(() => toast.classList.add('show'), 100);
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 400);
            }, 3000);
        }

        function quickOrder(name, price) {
            const formattedPrice = Number(price).toLocaleString('id-ID');
            const textMessage = `Halo AnarcyxReptile, saya ingin memesan unit ini:\n\n• *Nama Unit:* ${name}\n• *Harga:* Rp ${formattedPrice}`;
            window.open(`https://wa.me/${OWNER_PHONE}?text=${encodeURIComponent(textMessage)}`, '_blank');
        }

        document.addEventListener("DOMContentLoaded", () => {
            const urlParams = new URLSearchParams(window.location.search);
            const categoryParam = urlParams.get('category');

            if (categoryParam) {
                activeCategory = categoryParam;

                const targetBox = document.querySelector(`.filter-box-item[data-value="${categoryParam}"]`);
                if (targetBox) {
                    targetBox.classList.add('active');
                }

                applyFilters();
            } else {
                renderProducts(ALL_PRODUCTS);
            }
        });

        function toggleFilter() {
            document.getElementById('filterSidebar').classList.toggle('open');
            document.getElementById('filterToggle').classList.toggle('active');
        }
    </script>
@endpush