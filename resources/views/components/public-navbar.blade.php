<nav>
    <div class="logo-container">
        <img src="{{ asset('images/logo/logo.jpeg') }}" alt="ANARCYX Logo">
        <span class="brand-name">ANARCYXREPTILE</span>
    </div>
    <div class="menu-toggle" id="mobile-menu"><span></span><span></span><span></span></div>
    <ul class="nav-links" id="nav-list">
        <li><a href="{{ route('home') }}" @class(['active-nav' => request()->routeIs('home')])>Home</a></li>
        <li><a href="{{ route('shop') }}" @class(['active-nav' => request()->routeIs('shop', 'product.detail')])>Shop</a></li>
        <li><a href="{{ route('education') }}" @class(['active-nav' => request()->routeIs('education')])>Education</a></li>
        <li>
            <a href="{{ route('cart') }}" @class(['cart-link', 'active-nav' => request()->routeIs('cart')])>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                Cart <span class="cart-count" id="cartCount">0</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.login') }}" @class(['login-link', 'active-nav' => request()->routeIs('admin.login', 'admin.handleLogin')])>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                Login
            </a>
        </li>
    </ul>
</nav>
