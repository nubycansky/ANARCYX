@extends('layouts.public')

@section('title', 'AnarcyxReptile - Premium Exotic Shop')

@push('styles')
@endpush

@section('content')

    <header>
        <h1 class="hero-title">Selamat Datang</h1>
        <h2 class="hero-subtitle">AnarcyxReptile</h2>
        <p class="hero-desc">Destinasi utama bagi para kolektor dan pencinta reptil eksotis. Temukan beragam spesies langka yang sehat, terawat, dan siap menjadi sahabat baru Anda.</p>
        <div class="hero-buttons">
            <a href="{{ route('shop') }}" class="btn-main">Beli Sekarang &rarr;</a>
            <a href="{{ route('education') }}" class="btn-secondary">Learn More</a>
        </div>
    </header>

    <section class="section-container category-section" id="kategori">
        <h3 class="section-title">Pilih Sahabatmu</h3>
        <div class="category-wrapper">
            @forelse($categories as $cat)
                @php
                    $imageMap = [
                        'iguana'   => 'iguanaFamily.jpg',
                        'snake'    => 'ularFamily.png',
                        'tortoise' => 'turtoiseFamily.jpg',
                        'gecko'    => 'geckoFamily.jpg',
                    ];
                    $imageFile = $imageMap[strtolower($cat)] ?? strtolower($cat) . '.jpg';
                @endphp
                <a href="{{ route('shop') }}?category={{ urlencode($cat) }}" class="category-circle">
                    <img src="{{ asset('images/categories/' . $imageFile) }}" alt="{{ $cat }}" loading="lazy">
                </a>
            @empty
                <p style="color:#666;">Kategori belum tersedia.</p>
            @endforelse
        </div>
    </section>

    <section class="section-container" id="produk">
        <div class="product-header-flex">
            <div>
                <span class="section-subtitle">Beli Sekarang</span>
                <h3 class="section-title" style="margin-bottom: 0;">Temukan Sahabatmu</h3>
            </div>
            <a href="{{ route('shop') }}" class="view-all">View All &rarr;</a>
        </div>

        <div class="product-grid">
            @forelse($reptiles as $rep)
                <div class="product-card">
                    <button class="wishlist-btn" onclick="toggleWishlist(this)" aria-label="Tambah ke wishlist">
                        <svg width="18" height="18" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.5 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                    </button>
                    <div class="product-img-wrapper">
                        <img src="{{ $rep->image ? asset('images/products/' . $rep->image) : 'https://images.unsplash.com/photo-1601758124510-52d02ddb7cbd?auto=format&fit=crop&q=80&w=500' }}" alt="{{ $rep->name }}" loading="lazy">
                    </div>
                    <div class="product-info">
                        <a href="/products/{{ $rep->id }}" class="product-name" style="text-decoration: none; color: inherit; font-weight: 800; cursor: pointer;">{{ $rep->name }}</a>
                        <span class="product-price">Rp.{{ number_format($rep->price, 0, ',', '.') }}</span>
                    </div>
                    <div class="product-sciname">{{ $rep->attributes['morph'] ?? 'Exotic Species' }}</div>

                    <div class="product-desc-snippet">
                        {{ \Illuminate\Support\Str::limit($rep->desc ?? 'Reptil eksotis pilihan dengan kondisi sehat dan terawat.', 90) }}
                    </div>

                    <div class="card-actions">
                        <a href="{{ route('products.show', $rep->id) }}" class="btn-action btn-add-cart" style="text-decoration:none;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                            Add to Cart
                        </a>
                        <button class="btn-action btn-quick-order" onclick="quickOrder('{{ $rep->name }}', '{{ $rep->price }}')">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                            Quick Order
                        </button>
                    </div>
                </div>
            @empty
                <p style="grid-column: 1/-1; text-align: center; color: #666; padding: 60px 0;">Produk belum tersedia.</p>
            @endforelse
        </div>
    </section>

    <section class="section-container connect-us-section">
        <h3 class="section-title center">Connect with Us</h3>
        <div class="sosmed-wrapper">
            <a href="https://instagram.com/anarcyxreptile" target="_blank" rel="noopener" class="sosmed-card ig">
                <div class="sosmed-icon-circle"><svg fill="white" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.919-.058-1.265-.069-1.646-.069-4.849 0-3.204.012-3.583.069-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg></div>
                <div class="sosmed-card-info"><h4>Instagram</h4><span>@anarcyxreptile</span></div>
            </a>
            <a href="https://tiktok.com/@anarcyxreptile.official" target="_blank" rel="noopener" class="sosmed-card tiktok">
                <div class="sosmed-icon-circle"><svg fill="white" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5.8 20.1a6.34 6.34 0 0 0 10.86-4.43V8.18a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1.84-.61z"/></svg></div>
                <div class="sosmed-card-info"><h4>TikTok</h4><span>anarcyxreptile.official</span></div>
            </a>
            <a href="https://wa.me/6281234567890" target="_blank" rel="noopener" class="sosmed-card wa">
                <div class="sosmed-icon-circle"><svg fill="white" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg></div>
                <div class="sosmed-card-info"><h4>WhatsApp</h4><span>+62 812-3456-7890</span></div>
            </a>
        </div>
    </section>

@endsection

@push('scripts')
<script>
    const OWNER_PHONE = "6281234567890";

    let currentCart = localStorage.getItem('anarcyx_cart')
        ? JSON.parse(localStorage.getItem('anarcyx_cart'))
        : [];

    function addToCart(name, id, sciname, price, image) {
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
@endpush
