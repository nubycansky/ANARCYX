<script>
    document.getElementById('mobile-menu').addEventListener('click', () => {
        document.getElementById('nav-list').classList.toggle('active');
    });

    const cartBadge = document.getElementById('cartCount');
    if (cartBadge) {
        const items = localStorage.getItem('anarcyx_cart') ? JSON.parse(localStorage.getItem('anarcyx_cart')) : [];
        cartBadge.innerText = items.reduce((acc, item) => acc + item.qty, 0);
    }

    // Fungsi global untuk memperbarui badge keranjang di navbar (dipanggil dari halaman shop)
    function updateCartBadge() {
        const badge = document.getElementById('cartCount');
        if (badge) {
            const stored = localStorage.getItem('anarcyx_cart') ? JSON.parse(localStorage.getItem('anarcyx_cart')) : [];
            badge.innerText = stored.reduce((acc, item) => acc + item.qty, 0);
        }
    }

    // === CART GLOBAL FUNCTIONS ===
    function addToCart(productId) {
        try {
            const allProducts = window.ALL_PRODUCTS || [];
            const product = allProducts.find(p => String(p.id) === String(productId));
            if (!product) return;

            let localCart = localStorage.getItem('anarcyx_cart') ? JSON.parse(localStorage.getItem('anarcyx_cart')) : [];
            const existingIndex = localCart.findIndex(item => String(item.id) === String(productId));

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

            const totalQty = localCart.reduce((acc, item) => acc + item.qty, 0);
            const cartBadge = document.getElementById('cartCount') || document.querySelector('.cart-count');
            if (cartBadge) cartBadge.innerText = totalQty;

            showToast(`"${product.name}" berhasil dimasukkan ke keranjang belanja!`);
        } catch (e) {
            console.error('addToCart error:', e);
        }
    }

    function showToast(message) {
        const existingToast = document.querySelector('.custom-toast');
        if (existingToast) existingToast.remove();

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

    // === WISHLIST GLOBAL FUNCTIONS ===
    function getLocalWishlist() {
        return localStorage.getItem('anarcyx_wishlist') ? JSON.parse(localStorage.getItem('anarcyx_wishlist')) : [];
    }

    function updateWishlistCounter() {
        const countBadge = document.getElementById('wishlistCount');
        if (countBadge) {
            countBadge.innerText = getLocalWishlist().length;
        }
    }

    function toggleWishlistGlobal(product) {
        let wishlist = getLocalWishlist();
        const index = wishlist.findIndex(item => item.id === product.id);
        if (index > -1) {
            wishlist.splice(index, 1);
        } else {
            wishlist.push(product);
        }
        localStorage.setItem('anarcyx_wishlist', JSON.stringify(wishlist));
        updateWishlistCounter();
        syncHeartIconsUI();
    }

    function syncHeartIconsUI() {
        const wishlist = getLocalWishlist();
        document.querySelectorAll('.wishlist-btn').forEach(btn => {
            const pid = btn.getAttribute('data-product-id');
            const isFav = wishlist.some(item => item.id === pid);
            btn.classList.toggle('active', isFav);
            const svg = btn.querySelector('svg');
            if (svg) {
                svg.setAttribute('fill', isFav ? '#ef4444' : 'none');
                svg.setAttribute('stroke', isFav ? '#ef4444' : 'currentColor');
            }
        });
    }

    document.addEventListener('DOMContentLoaded', updateWishlistCounter);
</script>
