<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AnarcyxReptile - Premium Exotic Shop</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

    <nav>
        <div class="logo-container">
            <img src="https://via.placeholder.com/40" alt="Logo">
            <span class="brand-name">ANARCYXREPTILE</span>
        </div>
        <div class="menu-toggle" id="mobile-menu"><span></span><span></span><span></span></div>
        <ul class="nav-links" id="nav-list">
            <li><a href="{{ route('home') }}" class="active-nav">Home</a></li>
            <li><a href="{{ route('shop') }}">Shop</a></li>
            <li><a href="{{ route('education') }}">Education</a></li>
            <li>
                <a href="{{ route('cart') }}" class="cart-link">
                    Cart <span class="cart-count" id="cartCount">0</span>
                </a>
            </li>
        </ul>
    </nav>

    <header>
        <h1 class="hero-title">Selamat Datang</h1>
        <h2 class="hero-subtitle">AnarcyxReptile</h2>
        <p class="hero-desc">Temukan berbagai jenis reptil eksotis berkualitas tinggi.</p>
        <div class="hero-buttons">
            <a href="{{ route('shop') }}" class="btn-main">Beli Sekarang &rarr;</a>
            <a href="#" class="btn-secondary">Learn More</a>
        </div>
    </header>

    <section class="section-container" id="produk">
        <div class="product-header-flex">
            <h3 class="section-title" style="margin-bottom: 0;">Temukan Sahabatmu</h3>
            <a href="{{ route('shop') }}" class="view-all">View All &rarr;</a>
        </div>

        <div class="product-grid">
            @forelse($reptiles as $rep)
                <div class="product-card">
                    <button class="wishlist-btn" onclick="toggleWishlist(this)">
                        <svg width="18" height="18" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.5 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                    </button>
                    <div class="product-img-wrapper">
                        <img src="{{ $rep->image ? asset('storage/' . $rep->image) : 'https://images.unsplash.com/photo-1601758124510-52d02ddb7cbd?auto=format&fit=crop&q=80&w=500' }}" alt="{{ $rep->name }}">
                    </div>
                    <div class="product-info">
                        <span class="product-name">{{ $rep->name }}</span>
                        <span class="product-price">Rp.{{ number_format($rep->price, 0, ',', '.') }}</span>
                    </div>
                    <div class="product-sciname">{{ $rep->scientific_name ?? 'Exotic Species' }}</div>
                    
                    <div class="card-actions">
                        <a href="{{ route('product.detail', $rep->id) }}" class="btn-action btn-add-cart" style="text-decoration:none;">Lihat Detail</a>
                        <button class="btn-action btn-quick-order" onclick="quickOrder('{{ $rep->name }}', '{{ $rep->price }}')">Quick Order</button>
                    </div>
                </div>
            @empty
                <p style="grid-column: 1/-1; text-align: center; color: #666;">Produk belum tersedia.</p>
            @endforelse
        </div>
    </section>

    <section class="section-container" style="background-color: #F4F6F2; margin-top: 20px;">
        <h3 class="section-title center">Connect with Us</h3>
        <div class="sosmed-wrapper">
            <a href="#" class="sosmed-card ig">
                <div class="sosmed-icon-circle"><svg fill="white" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.919-.058-1.265-.069-1.646-.069-4.849 0-3.204.012-3.583.069-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg></div>
                <div class="sosmed-card-info"><h4>Instagram</h4><span>@anarcyxreptile</span></div>
            </a>
            <a href="#" class="sosmed-card tiktok">
                <div class="sosmed-icon-circle"><svg fill="white" viewBox="0 0 24 24"><path d="M12.525.965c-.3 0-.6.015-.89.043v4.613a7.352 7.352 0 011.87-.247c1.78-.06 3.447.662 4.603 1.936a7.195 7.195 0 011.666 5.161c-.347 3.528-3.442 6.27-6.938 6.071-3.39-.196-6.113-2.91-6.126-6.305v-10.9h-4.71v11.026c.01 5.922 4.808 10.706 10.73 10.706 5.86 0 10.63-4.683 10.72-10.542.083-5.263-3.666-9.743-8.815-10.373a6.578 6.578 0 00-2.12-.187zM5.38 7.323c.36.19.742.348 1.13.473-.3.123-.62.247-.93.37-.3-.11-.6-.225-.91-.322.39-.18.783-.344 1.18-.521zm2.36-.61c.4.155.824.28 1.25.37-.29.088-.58.17-.87.25-.32-.1-.65-.195-.97-.282.4-.11.785-.215 1.18-.338z"/></svg></div>
                <div class="sosmed-card-info"><h4>TikTok</h4><span>anarcyxreptile.official</span></div>
            </a>
            <a href="#" class="sosmed-card yt">
                <div class="sosmed-icon-circle"><svg fill="white" viewBox="0 0 24 24"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 4-8 4z"/></svg></div>
                <div class="sosmed-card-info"><h4>YouTube</h4><span>AnarcyxReptile Official</span></div>
            </a>
        </div>
    </section>

    <footer>
        <div class="footer-grid">
            <div class="footer-brand">
                <h3>ANARCYXREPTILE</h3>
                <p class="footer-desc">
                    Penyedia reptil eksotis terpercaya. Menghubungkan pecinta hewan dengan partner reptil terbaik yang sehat, legal, dan terawat dengan penuh kasih sayang.
                </p>
            </div>
            
            <div class="footer-links">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('shop') }}">Shop</a></li>
                    <li><a href="{{ route('education') }}">Education</a></li>
                </ul>
            </div>
            
            <div class="footer-contact">
                <h4>Contact Info</h4>
                <p>
                    WhatsApp: +62 812-3456-7890<br>
                    Email: support@anarcyxreptile.com<br>
                    Lokasi: Jakarta, Indonesia
                </p>
            </div>
        </div>
        
        <div class="footer-bottom-copyright">
            &copy; 2026 AnarcyxReptile. All Rights Reserved.
        </div>
    </footer>

    <script>
        const OWNER_PHONE = "6281234567890"; 
        
        // Membaca data storage agar jumlah badge navbar sinkron saat pertama kali dimuat
        let currentCart = localStorage.getItem('anarcyx_cart') 
            ? JSON.parse(localStorage.getItem('anarcyx_cart')) 
            : [];
            
        // Set jumlah badge di navbar saat halaman home dibuka
        document.getElementById('cartCount').innerText = currentCart.reduce((acc, item) => acc + item.qty, 0);

        // Hamburger Menu Mobile Toggle
        document.getElementById('mobile-menu').addEventListener('click', () => {
            document.getElementById('nav-list').classList.toggle('active');
        });

        // Fungsi Add to Cart yang terintegrasi dengan LocalStorage
        function addToCart(name, id, sciname, price, image) {
            // Cek apakah produk sudah ada di keranjang
            let existingItem = currentCart.find(item => item.id === id);
            
            if (existingItem) {
                existingItem.qty += 1;
            } else {
                currentCart.push({
                    id: id || "REP_NEW",
                    name: name,
                    sciname: sciname || "Exotic Breed",
                    price: Number(price) || 350000,
                    qty: 1,
                    image: image || "https://images.unsplash.com/photo-1601758124510-52d02ddb7cbd?auto=format&fit=crop&q=80&w=150"
                });
            }
            
            // Simpan ke storage dan update badge
            localStorage.setItem('anarcyx_cart', JSON.stringify(currentCart));
            document.getElementById('cartCount').innerText = currentCart.reduce((acc, item) => acc + item.qty, 0);
            
            alert(`"${name}" berhasil dimasukkan ke keranjang belanja!`);
        }

        function quickOrder(name, price) {
            const formattedPrice = Number(price).toLocaleString('id-ID');
            const textMessage = `Halo Owner AnarcyxReptile, saya memesan unit ini:\n\n• *Nama Unit:* ${name}\n• *Harga:* Rp.${formattedPrice}`;
            window.open(`https://wa.me/${OWNER_PHONE}?text=${encodeURIComponent(textMessage)}`, '_blank');
        }

        function toggleWishlist(btn) {
            btn.classList.toggle('active');
        }
    </script>

</body>
</html>