<nav>
    <div class="logo-container">
        <img src="{{ asset('images/logo/logo.jpeg') }}" alt="ANARCYX Logo">
        <span class="brand-name">ANARCYXREPTILE</span>
    </div>
    <div class="menu-toggle" id="mobile-menu"><span></span><span></span><span></span></div>
    <ul class="nav-links" id="nav-list">
        <li><a href="{{ route('home') }}" @class(['active-nav' => request()->routeIs('home')])>Home</a></li>
        <li><a href="{{ route('shop') }}" @class(['active-nav' => request()->routeIs('shop', 'products.show')])>Shop</a></li>
        <li><a href="{{ route('education') }}" @class(['active-nav' => request()->routeIs('education')])>Education</a></li>
        <li>
            <a href="{{ route('cart') }}" @class(['cart-link', 'active-nav' => request()->routeIs('cart')])>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                Cart <span class="cart-count" id="cartCount">0</span>
            </a>
        </li>
        <li>
            <a href="{{ route('wishlist') }}" @class(['active-nav' => request()->routeIs('wishlist')]) style="position: relative; display: flex; align-items: center;">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                <span id="wishlistCount" style="position: absolute; top: -8px; right: -10px; background: #ef4444; color: white; font-size: 0.6rem; font-weight: 800; padding: 2px 5px; border-radius: 10px; line-height: 1; min-width: 16px; text-align: center;">0</span>
            </a>
        </li>
        @auth
            <li>
                <a href="{{ route('user.profile') }}" @class(['login-link', 'active-nav' => request()->routeIs('user.profile')])>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    {{ auth()->user()->name }}
                </a>
            </li>
            <li>
                <form action="{{ route('auth.handleLogout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="login-link" style="background:none;border:none;cursor:pointer;font:inherit;color:inherit;display:flex;align-items:center;gap:6px;padding:0;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                        Logout
                    </button>
                </form>
            </li>
        @else
            <li>
                <a href="{{ route('login') }}" @class(['login-link', 'active-nav' => request()->routeIs('login', 'auth.handleLogin')])>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                    Login
                </a>
            </li>
        @endauth
    </ul>
</nav>
