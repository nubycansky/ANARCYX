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
        const OWNER_PHONE = "62895613369443";

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

        // === WISHLIST GLOBAL ===
        // Fungsi toggleWishlist akan menggunakan fungsi global dari navbar-scripts

        // Fungsi Utama Render Produk (Sudah Dilengkapi Formula Urutan Favorit Teratas)
        function renderProducts(productsList) {
            const container = document.getElementById('productsContainer');
            if (productsList.length === 0) {
                container.innerHTML = `<div class="no-products">Reptil tidak ditemukan. Coba reset filter pencarianmu.</div>`;
                return;
            }

            // FORMULA SORTING: Produk yang di-love dipaksa naik ke index paling atas
            const wishlistData = getLocalWishlist();
            const sortedProducts = [...productsList].sort((a, b) => {
                const aFav = wishlistData.some(item => item.id === a.id) ? 1 : 0;
                const bFav = wishlistData.some(item => item.id === b.id) ? 1 : 0;
                return bFav - aFav;
            });

            container.innerHTML = sortedProducts.map(product => {
                const isFavorite = wishlistData.some(item => item.id === product.id) ? 'active' : '';

                return `
                    <div class="product-card" onclick="window.location='/products/${product.id}'" style="cursor: pointer;">
                        <button class="wishlist-btn ${isFavorite}" data-product-id="${product.id}" onclick="toggleWishlistGlobal({ id: '${product.id}', name: '${product.name}', price: ${product.price}, image: '${product.image}' }); applyFilters();">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="${isFavorite ? '#ef4444' : 'none'}" stroke="${isFavorite ? '#ef4444' : 'currentColor'}" stroke-width="2"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        </button>
                        <div class="product-img-wrapper">
                            <img src="${product.image.startsWith('http') ? product.image : '/images/products/' + product.image}" alt="${product.name}">
                        </div>
                        <div class="product-info">
                            <a href="/products/${product.id}" class="product-name" style="text-decoration: none; color: #111111; font-weight: 800; cursor: pointer;" onclick="event.stopPropagation()">${product.name}</a>
                            <span class="product-price">Rp ${product.price.toLocaleString('id-ID')}</span>
                        </div>
                            <div class="product-sciname">${product.sciname || 'Exotic Pet'}</div>
                        
                        <div class="badge-container">
                            <span class="badge">${product.category}</span>
                            <span class="badge empty"></span>
                        </div>

                        <p class="product-desc-snippet">${product.short_description || (product.desc ? product.desc.substring(0, 90) + (product.desc.length > 90 ? '...' : '') : 'Reptil eksotis pilihan dengan kondisi sehat dan terawat.')}</p>
                        
                        <div class="card-actions">
                            <button class="btn-action btn-add-cart" onclick="event.stopPropagation(); addToCart('${product.id}')">Add to Cart</button>
                            <button class="btn-action btn-quick-order" onclick="quickOrder('${product.name}', ${product.price}, '${product.id}')">Quick Order</button>
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

        function quickOrder(name, price, productId) {
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
                const formattedPrice = Number(price).toLocaleString('id-ID');
                const msg = `Halo AnarcyxReptile, saya ingin memesan unit ini:\n\n• *Nama Unit:* ${name}\n• *Harga:* Rp ${formattedPrice}`;
                window.open(`https://wa.me/${OWNER_PHONE}?text=${encodeURIComponent(msg)}`, '_blank');
            });
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
            syncHeartIconsUI();
        });

        function toggleFilter() {
            document.getElementById('filterSidebar').classList.toggle('open');
            document.getElementById('filterToggle').classList.toggle('active');
        }
    </script>
@endpush