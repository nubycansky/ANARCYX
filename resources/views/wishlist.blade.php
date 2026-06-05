@extends('layouts.public')

@section('title', 'Wishlist - AnarcyxReptile')

@push('styles')
<style>
    .wishlist-empty-wrapper {
        display: flex; flex-direction: column;
        justify-content: center; align-items: center;
        text-align: center; padding: 100px 20px;
        width: 100%; min-height: 55vh;
    }
    .wishlist-empty-icon {
        width: 90px; height: 90px; border-radius: 50%;
        background: #fce4e4; display: flex;
        justify-content: center; align-items: center;
        margin-bottom: 24px;
    }
    .wishlist-empty-icon svg { width: 44px; height: 44px; color: #ef4444; }
    .wishlist-empty-headline {
        font-size: 1.6rem; font-weight: 700;
        color: #111; margin-bottom: 8px;
    }
    .wishlist-empty-subtitle {
        font-size: 0.95rem; color: #6c757d;
        margin-bottom: 30px;
    }
    .btn-wishlist-browse {
        background: #283221; color: #fff;
        padding: 16px 50px; border-radius: 6px;
        text-decoration: none; font-weight: 700;
        font-size: 0.95rem; transition: all 0.3s ease;
    }
    .btn-wishlist-browse:hover {
        background: #3b4930; transform: translateY(-2px);
    }
    .wishlist-page-title {
        font-size: 2rem; font-weight: 800; color: #111;
        margin: 60px 4% 10px;
        max-width: 1200px; margin-left: auto; margin-right: auto;
    }
    .wishlist-page-subtitle {
        font-size: 0.95rem; color: #6c757d;
        margin: 0 4% 30px;
        max-width: 1200px; margin-left: auto; margin-right: auto;
    }
    .wishlist-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 24px;
        max-width: 1200px;
        margin: 0 auto 80px;
        padding: 0 4%;
    }
    .wishlist-card {
        background: #fff; border-radius: 16px;
        border: 1px solid #E5E5E5; overflow: hidden;
        transition: all 0.3s ease;
    }
    .wishlist-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.08);
    }
    .wishlist-card-img {
        width: 100%; height: 200px;
        object-fit: cover; display: block;
    }
    .wishlist-card-body {
        padding: 16px 18px 20px;
    }
    .wishlist-card-name {
        font-weight: 800; font-size: 1.05rem;
        color: #111; margin-bottom: 4px;
    }
    .wishlist-card-price {
        font-weight: 700; font-size: 1rem;
        color: #283221; margin-bottom: 14px;
    }
    .wishlist-card-actions {
        display: flex; gap: 8px;
    }
    .wishlist-card-actions .btn-action {
        flex: 1; padding: 10px 0; border: none;
        border-radius: 8px; font-weight: 700;
        font-size: 0.82rem; cursor: pointer;
        transition: all 0.3s ease;
        text-align: center; text-decoration: none;
        display: inline-flex; align-items: center;
        justify-content: center; gap: 6px;
    }
    .btn-remove-wishlist {
        background: rgba(239, 68, 68, 0.1); color: #ef4444;
    }
    .btn-remove-wishlist:hover {
        background: #ef4444; color: #fff;
    }
    .wishlist-empty-sub {
        color: #999; font-size: 0.9rem; margin-top: 4px;
    }
</style>
@endpush

@section('content')
    <div id="wishlistPageContainer">
        <div class="wishlist-empty-wrapper" id="wishlistEmptyState">
            <div class="wishlist-empty-icon">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
            </div>
            <h2 class="wishlist-empty-headline">Your Wishlist is Empty</h2>
            <p class="wishlist-empty-subtitle">Save your favorite reptiles here by tapping the heart icon on any product.</p>
            <a href="{{ route('shop') }}" class="btn-wishlist-browse">Browse Animal</a>
        </div>
        <div id="wishlistContent" style="display: none;">
            <h2 class="wishlist-page-title">Your Wishlist</h2>
            <p class="wishlist-page-subtitle">Produk favorit yang kamu simpan <span id="wishlistItemCount">(0)</span></p>
            <div class="wishlist-grid" id="wishlistGrid"></div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const OWNER_PHONE = "62895613369443";

    function renderWishlistPage() {
        const wishlist = getLocalWishlist();
        const emptyState = document.getElementById('wishlistEmptyState');
        const content = document.getElementById('wishlistContent');
        const grid = document.getElementById('wishlistGrid');
        const countSpan = document.getElementById('wishlistItemCount');

        if (wishlist.length === 0) {
            emptyState.style.display = 'flex';
            content.style.display = 'none';
            return;
        }

        emptyState.style.display = 'none';
        content.style.display = 'block';
        if (countSpan) countSpan.innerText = `(${wishlist.length})`;

        grid.innerHTML = wishlist.map(item => `
            <div class="wishlist-card" data-wishlist-id="${item.id}">
                <img class="wishlist-card-img" src="${item.image && item.image.startsWith('http') ? item.image : '/images/products/' + (item.image || '')}" alt="${item.name}" loading="lazy"
                    onerror="this.src='https://images.unsplash.com/photo-1601758124510-52d02ddb7cbd?auto=format&fit=crop&q=80&w=500'">
                <div class="wishlist-card-body">
                    <div class="wishlist-card-name">${item.name}</div>
                    <div class="wishlist-card-price">Rp ${Number(item.price || 0).toLocaleString('id-ID')}</div>
                    <div class="wishlist-card-actions">
                        <button class="btn-action btn-add-cart" onclick="addToCartFromWishlist('${item.id}')">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                            Add to Cart
                        </button>
                        <button class="btn-action btn-remove-wishlist" onclick="removeFromWishlist('${item.id}')">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                            Remove
                        </button>
                    </div>
                </div>
            </div>
        `).join('');
    }

    function removeFromWishlist(productId) {
        let wishlist = getLocalWishlist();
        wishlist = wishlist.filter(item => item.id !== productId);
        localStorage.setItem('anarcyx_wishlist', JSON.stringify(wishlist));
        updateWishlistCounter();
        renderWishlistPage();
    }

    function addToCartFromWishlist(productId) {
        const wishlist = getLocalWishlist();
        const item = wishlist.find(p => p.id === productId);
        if (!item) return;

        let localCart = localStorage.getItem('anarcyx_cart') ? JSON.parse(localStorage.getItem('anarcyx_cart')) : [];
        const existingIndex = localCart.findIndex(c => c.id === productId);

        if (existingIndex > -1) {
            localCart[existingIndex].qty += 1;
        } else {
            localCart.push({
                id: item.id,
                name: item.name,
                price: Number(item.price || 0),
                qty: 1,
                image: item.image || ''
            });
        }

        localStorage.setItem('anarcyx_cart', JSON.stringify(localCart));
        updateCartBadge();

        const toast = document.createElement('div');
        toast.style.cssText = 'position: fixed; bottom: 20px; right: 20px; background: #283221; color: white; padding: 14px 24px; border-radius: 8px; font-weight: 600; font-size: 0.9rem; z-index: 9999; transform: translateY(100px); opacity: 0; transition: all 0.4s cubic-bezier(0.68, -0.55, 0.27, 1.55);';
        toast.innerHTML = `"${item.name}" berhasil dimasukkan ke keranjang belanja!`;
        document.body.appendChild(toast);
        setTimeout(() => { toast.style.transform = 'translateY(0)'; toast.style.opacity = '1'; }, 100);
        setTimeout(() => {
            toast.style.transform = 'translateY(100px)'; toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 400);
        }, 3000);
    }

    document.addEventListener('DOMContentLoaded', renderWishlistPage);
</script>
@endpush
