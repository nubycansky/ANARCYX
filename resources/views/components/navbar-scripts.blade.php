<script>
    document.getElementById('mobile-menu').addEventListener('click', () => {
        document.getElementById('nav-list').classList.toggle('active');
    });

    let localCart = localStorage.getItem('anarcyx_cart') ? JSON.parse(localStorage.getItem('anarcyx_cart')) : [];
    const cartBadge = document.getElementById('cartCount');
    if (cartBadge) cartBadge.innerText = localCart.reduce((acc, item) => acc + item.qty, 0);
</script>
