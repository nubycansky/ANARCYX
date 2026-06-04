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
</script>
