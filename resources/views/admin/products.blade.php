<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management - Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* --- MEMASTIKAN SEMUA FONT SAMA (MANDAT 4) --- */
        * { font-family: 'Plus Jakarta Sans', sans-serif !important; box-sizing: border-box; }
        
        /* --- STYLING HEADER & INTERAKSI NAVBAR --- */
        .admin-navbar { background: #FFFFFF; display: flex; align-items: center; padding: 15px 4%; border-bottom: 1px solid #E5E5E5; position: sticky; top:0; z-index: 90; }
        .nav-left-side { display: flex; align-items: center; gap: 20px; }
        .btn-hamburger { background: none; border: none; font-size: 2rem; cursor: pointer; color: #111; font-weight: 800; display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 8px; transition: background 0.2s; }
        .btn-hamburger:hover { background-color: #F5F5F5; }
        
        /* Navigasi Kanan Berjejer Memanjang Rapi */
        .nav-right-side { display: flex; align-items: center; gap: 25px; margin-left: auto; font-weight: 700; position: relative; }
        .admin-profile-wrapper { display: flex; align-items: center; gap: 10px; }
        .profile-img-circle { width: 38px; height: 38px; border-radius: 50%; border: 2px solid #283221; background-color: #4A5C3A; }
        .btn-logout-red { background-color: #dc2626 !important; color: #ffffff !important; border: none; padding: 10px 18px; border-radius: 6px; font-weight: 700; font-size: 0.85rem; cursor: pointer; text-decoration: none; transition: background 0.2s; }
        .btn-logout-red:hover { background-color: #b91c1c !important; }

        /* Lonceng & Dropdown Notifikasi Rapi */
        .noti-bell-container { position: relative; cursor: pointer; color: #333; display: flex; align-items: center; }
        .bell-badge-red { position: absolute; top: -4px; right: -4px; background-color: #ef4444; color: white; font-size: 0.65rem; font-weight: 800; padding: 2px 6px; border-radius: 10px; border: 2px solid #FFFFFF; }
        
        .floating-noti-dropdown { position: absolute; top: 50px; right: 170px; width: 340px; background: #FFFFFF; border: 1px solid #E5E5E5; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.08); display: none; flex-direction: column; z-index: 100; overflow: hidden; }
        .floating-noti-dropdown.show { display: flex; }
        .noti-dropdown-header { padding: 14px 16px; font-size: 0.9rem; font-weight: 800; border-bottom: 1px solid #F5F5F5; text-align: left; color: #111; }
        .noti-dropdown-body { max-height: 250px; overflow-y: auto; }
        .noti-dropdown-item { padding: 14px 16px; font-size: 0.85rem; font-weight: 600; color: #444; border-bottom: 1px solid #F9F9F9; text-align: left; display: flex; gap: 12px; align-items: flex-start; }
        .noti-dropdown-item svg { color: #6B8E4E; flex-shrink: 0; margin-top: 2px; }
        .btn-read-more-noti { display: block; text-align: center; padding: 12px; background: #F9FAF7; font-size: 0.85rem; font-weight: 700; color: #4A5C3A; text-decoration: none; border-top: 1px solid #E5E5E5; }
        .btn-read-more-noti:hover { background: #F3F4F0; }

        /* SIDEBAR DRAWER PANEL RATA KIRI PENUH */
        .admin-sidebar-drawer { position: fixed; top: 0; left: -300px; width: 300px; height: 100%; background: #000000; color: white; z-index: 200; padding: 35px 24px; transition: left 0.3s ease; }
        .admin-sidebar-drawer.open { left: 0; }
        .sidebar-brand-title { font-size: 1.3rem; font-weight: 800; margin-bottom: 40px; color: #FFFFFF; text-align: left; }
        .sidebar-menu-ul { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 12px; }
        .sidebar-menu-ul a { display: flex; align-items: center; justify-content: flex-start; gap: 15px; color: #A3A3A3; text-decoration: none; padding: 14px 18px; border-radius: 10px; font-weight: 700; font-size: 0.95rem; width: 100%; text-align: left; }
        .sidebar-menu-ul a:hover, .sidebar-menu-ul .active-menu { background: #283221; color: #FFFFFF; }
        .sidebar-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 150; display: none; backdrop-filter: blur(3px); }
        .sidebar-overlay.show { display: block; }

        /* CONTENT LAYOUT */
        .dashboard-main-content { max-width: 100%; width: 100%; margin: 40px auto; padding: 0 4%; }
        
        /* METRIK PRODUK ATAS ATAS */
        .product-stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 35px; }
        .stat-card-box { background: #FFFFFF; border: 1px solid #E5E5E5; border-radius: 12px; padding: 20px; text-align: left; box-shadow: 0 4px 12px rgba(0,0,0,0.01); }
        .stat-card-title { font-size: 0.85rem; color: #888; font-weight: 600; margin-bottom: 5px; }
        .stat-card-number { font-size: 1.6rem; font-weight: 800; color: #111; }

        /* ALAT KONTROL TOOLBAR - REVISI INPUT DENGAN IKON OUTLINE TIPIS */
        .toolbar-flex-control { display: flex; justify-content: space-between; align-items: center; gap: 20px; margin-bottom: 25px; }
        .search-filter-left { display: flex; gap: 15px; flex: 1; }
        
        /* Wrapper Input Search */
        .search-wrapper-relative { position: relative; width: 300px; display: flex; align-items: center; }
        .search-wrapper-relative svg { position: absolute; left: 16px; color: #888888; pointer-events: none; }
        .search-input-box { width: 100%; padding: 12px 16px 12px 46px; border: 1px solid #E5E5E5; border-radius: 8px; font-size: 0.9rem; font-weight: 600; outline: none; transition: border-color 0.2s; }
        .search-input-box:focus { border-color: #6B8E4E; }

        /* Wrapper Select Dropdown Kategori Rapi di Tengah */
        .select-wrapper-relative { position: relative; display: flex; align-items: center; }
        .filter-select-box { padding: 12px 40px 12px 16px; border: 1px solid #E5E5E5; border-radius: 8px; background: white; font-size: 0.9rem; font-weight: 600; cursor: pointer; outline: none; appearance: none; -webkit-appearance: none; -moz-appearance: none; }
        .filter-select-box:focus { border-color: #6B8E4E; }
        .select-wrapper-relative svg { position: absolute; right: 16px; color: #666666; pointer-events: none; }

        .btn-green-add { background-color: #6B8E4E; color: white; border: none; padding: 12px 24px; border-radius: 8px; font-weight: 700; font-size: 0.9rem; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: background 0.2s; }
        .btn-green-add:hover { background-color: #55723e; }

        /* TABEL PRODUK CRUD FUNGSI MONGODB DENGAN IKON OUTLINE POLOS */
        .table-container-white { background: #FFFFFF; border: 1px solid #E5E5E5; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.01); width: 100%; }
        .crud-table { width: 100%; border-collapse: collapse; text-align: left; }
        .crud-table th { background: #F9FAF7; padding: 16px 20px; font-size: 0.85rem; font-weight: 800; color: #4A5C3A; text-transform: uppercase; border-bottom: 1px solid #E5E5E5; }
        .crud-table td { padding: 16px 20px; border-bottom: 1px solid #F5F5F5; font-size: 0.95rem; font-weight: 600; color: #333; vertical-align: middle; }
        .table-thumb-img { width: 45px; height: 45px; border-radius: 8px; object-fit: cover; background: #eee; }
        .badge-stock-green { background-color: #DEF7EC; color: #03543F; padding: 5px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; display: inline-block; }
        .badge-stock-red { background-color: #FEE2E2; color: #9B1C1C; padding: 5px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; display: inline-block; }
        
        .action-buttons-flex { display: flex; gap: 12px; align-items: center; }
        .btn-icon-action { width: 36px; height: 36px; border-radius: 8px; border: 1px solid #E5E5E5; background: white; cursor: pointer; display: flex; justify-content: center; align-items: center; transition: all 0.2s; color: #555555; }
        .btn-icon-action:hover { border-color: #6B8E4E; background: #F9FAF7; color: #6B8E4E; }
        .btn-icon-action.delete:hover { border-color: #EF4444; background: #FEE2E2; color: #EF4444; }

        /* MODAL POP-UP FORM TAMBAH/EDIT MONGODB COMPASS */
        .form-modal-overlay { position: fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.4); backdrop-filter: blur(5px); display: none; justify-content: center; align-items: center; z-index: 500; }
        .form-modal-overlay.show { display: flex; }
        .form-modal-box { background: white; border-radius: 16px; padding: 35px; width: 100%; max-width: 500px; box-shadow: 0 15px 40px rgba(0,0,0,0.15); animation: popScale 0.25s cubic-bezier(0.34, 1.56, 0.64, 1); }
        @keyframes popScale { from { transform: scale(0.8); opacity:0; } to { transform: scale(1); opacity:1; } }
        
        .form-grid-inputs { display: flex; flex-direction: column; gap: 16px; margin-top: 20px; }
        .input-block-group { display: flex; flex-direction: column; gap: 6px; text-align: left; }
        .input-block-group label { font-size: 0.85rem; font-weight: 700; color: #111; }
        .input-block-group input, .input-block-group select, .input-block-group textarea { padding: 12px; border: 1px solid #E5E5E5; border-radius: 8px; font-size: 0.9rem; font-weight: 600; outline: none; width: 100%; }
        
        /* MANDAT 4: VALIDASI INPUT ERROR KELAS BORDER MERAH PENYENGAT */
        .input-block-group.error-validate input, .input-block-group.error-validate select, .input-block-group.error-validate textarea { border-color: #EF4444 !important; background-color: #FFF5F5; }
        .error-message-text { color: #EF4444; font-size: 0.75rem; font-weight: 700; margin-top: 2px; display: none; text-align: left; }
        .input-block-group.error-validate .error-message-text { display: block; }

        .modal-action-buttons { display: flex; gap: 12px; margin-top: 10px; }
        .btn-modal-save { background: #283221; color: white; border: none; padding: 12px 24px; border-radius: 8px; font-weight: 700; cursor: pointer; flex: 1; }
        .btn-modal-cancel { background: #F3F4F6; color: #4B5563; border: 1px solid #E5E7EB; padding: 12px 24px; border-radius: 8px; font-weight: 700; cursor: pointer; flex:1; }

        /* POP-UP CONFIRMATION GLOBAL (MANDAT 4) */
        .confirm-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.4); backdrop-filter: blur(5px); display: none; justify-content: center; align-items: center; z-index: 600; opacity: 0; transition: opacity 0.3s; }
        .confirm-overlay.show { display: flex; opacity: 1; }
        .confirm-box { background: white; border-radius: 16px; padding: 35px; width: 100%; max-width: 420px; text-align: center; box-shadow: 0 15px 40px rgba(0,0,0,0.15); animation: popScale 0.25s cubic-bezier(0.34, 1.56, 0.64, 1); }
        .confirm-icon-circle { width: 60px; height: 60px; border-radius: 50%; display: flex; justify-content: center; align-items: center; margin: 0 auto 20px auto; font-size: 1.5rem; }
        .confirm-title-text { font-size: 1.2rem; font-weight: 800; color: #111; margin-bottom: 25px; line-height: 1.4; }
        .confirm-buttons-flex { display: flex; gap: 12px; justify-content: center; }
        .btn-confirm-yes { background-color: #EF4444; color: white; border: none; padding: 12px 24px; border-radius: 8px; font-weight: 700; cursor: pointer; flex: 1; }
        .btn-confirm-cancel { background-color: #F3F4F6; color: #4B5563; border: 1px solid #E5E7EB; padding: 12px 24px; border-radius: 8px; font-weight: 700; cursor: pointer; flex: 1; }
        
        .btn-filter-done { background: #283221; color: white; border: none; padding: 12px; border-radius: 8px; font-weight: 700; cursor: pointer; font-size: 0.9rem; }
        .btn-filter-done:hover { background-color: #4A5C3A; }
    </style>
</head>
<body>

    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>
    
    <aside class="admin-sidebar-drawer" id="adminSidebar">
        <div class="sidebar-brand-title">ANARCYXREPTILE <span style="font-size:0.75rem; color:#888; display:block; margin-top:4px; font-weight:600;">Admin Panel</span></div>
        <nav class="sidebar-menu-ul">
            <a href="{{ route('admin.dashboard') }}">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" /></svg> Dashboard Overview
            </a>
            <a href="#" class="active-menu">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4L4 7m0 0v10l8 4" /></svg> Products
            </a>
            <a href="{{ route('admin.orders') }}">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg> Order Management
            </a>
            <a href="{{ route('admin.education') }}">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg> Education Management
            </a>
        </nav>
    </aside>

    <div class="admin-navbar">
        <div class="nav-left-side">
            <button class="btn-hamburger" onclick="toggleSidebar()">&equiv;</button>
        </div>
        
        <div class="nav-right-side">
            <div class="noti-bell-container" onclick="toggleNotiPanel(event)">
                <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#111" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                @if($unreadNotificationCount > 0)
                    <span class="bell-badge-red">{{ $unreadNotificationCount }}</span>
                @endif
            </div>

            <div class="floating-noti-dropdown" id="notiPopoverPanel">
                <div class="noti-dropdown-header">Notifications Overview</div>
                <div class="noti-dropdown-body">
                    @forelse($dropdownNotifications as $noti)
                        <div class="noti-dropdown-item">
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                            <div>{{ $noti->message }}</div>
                        </div>
                    @empty
                        <div class="noti-dropdown-item" style="color: #888;">Tidak ada notifikasi baru.</div>
                    @endforelse
                </div>
                <a href="{{ route('admin.notifications') }}" class="btn-read-more-noti">Read More Notification</a>
            </div>

            <div class="admin-profile-wrapper">
                <span>Admin</span>
                <div class="profile-img-circle"></div>
            </div>

            <button class="btn-logout-red" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Log Out</button>
            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">@csrf</form>
        </div>
    </div>

    <main class="dashboard-main-content">
        <h1 class="page-headline" style="font-size: 2.2rem; font-weight: 800; color: #000; margin-bottom: 5px;">Product Management</h1>
        <p class="page-subheadline" style="font-size: 1rem; color: #777; margin-bottom: 35px; font-weight: 600;">Manage your reptile inventory</p>

        <section class="product-stats-grid">
            <div class="stat-card-box"><div class="stat-card-title">Total Products</div><div class="stat-card-number">{{ $totalQty }}</div></div>
            <div class="stat-card-box"><div class="stat-card-title">In Stock</div><div class="stat-card-number" style="color: #6B8E4E;">{{ $inStockQty }}</div></div>
            <div class="stat-card-box"><div class="stat-card-title">Out of Stock</div><div class="stat-card-number" style="color: #EF4444;">{{ $outStockQty }}</div></div>
            <div class="stat-card-box"><div class="stat-card-title">Total Value</div><div class="stat-card-number" style="font-size: 1.4rem; padding-top: 4px;">Rp {{ number_format($totalValue, 0, ',', '.') }}</div></div>
        </section>

        <section class="toolbar-flex-control">
            <form action="{{ route('admin.products') }}" method="GET" class="search-filter-left" id="filterForm">
                <div class="search-wrapper-relative">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    <input type="text" name="search" class="search-input-box" placeholder="Search Products..." value="{{ request('search') }}" onkeyup="if(event.key === 'Enter') this.form.submit();">
                </div>
                
                <div class="select-wrapper-relative">
                    <select name="category" class="filter-select-box" onchange="document.getElementById('filterForm').submit();">
                        <option value="All Categories" {{ request('category') == 'All Categories' ? 'selected' : '' }}>All Categories</option>
                        <option value="Iguana" {{ request('category') == 'Iguana' ? 'selected' : '' }}>Iguana</option>
                        <option value="Gecko" {{ request('category') == 'Gecko' ? 'selected' : '' }}>Gecko</option>
                        <option value="Snake" {{ request('category') == 'Snake' ? 'selected' : '' }}>Snake</option>
                        <option value="Tortoise" {{ request('category') == 'Tortoise' ? 'selected' : '' }}>Tortoise</option>
                    </select>
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                </div>
            </form>
            <button class="btn-green-add" onclick="openAddModal()">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                Add Product
            </button>
        </section>

        <section class="table-container-white">
            <table class="crud-table">
                <thead>
                    <tr>
                        <th style="width: 320px;">Products</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th style="text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $p)
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 14px;">
                                <img src="{{ asset('images/products/'.$p->image) }}" class="table-thumb-img" alt="">
                                <div>
                                    <div style="font-weight: 800; color: #111;">{{ $p->name }}</div>
                                    <div style="font-size: 0.78rem; color: #888; font-style: italic;">{{ $p->attributes['morph'] ?? 'Normal Morph' }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $p->category }}</td>
                        <td style="font-weight: 800; color: #283221;">Rp {{ number_format($p->price, 0, ',', '.') }}</td>
                        <td>
                            @if($p->stock > 0)
                                <span class="badge-stock-green">in-stock</span>
                            @else
                                <span class="badge-stock-red">out-stock</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-buttons-flex" style="justify-content: center;">
                                <button class="btn-icon-action" onclick="openEditModal('{{ $p->id }}', '{{ $p->name }}', '{{ $p->category }}', '{{ $p->price }}', '{{ $p->stock }}', '{{ $p->desc }}')">
                                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                </button>
                                <button class="btn-icon-action delete" onclick="triggerDeleteProduct('{{ $p->id }}')">
                                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-16v1M4 7h16" /></svg>
                                </button>
                                <form id="delete-form-{{ $p->id }}" action="{{ route('admin.products.delete', $p->id) }}" method="POST" style="display:none;">@csrf @method('DELETE')</form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" style="text-align: center; color: #888; padding: 40px 0;">Tidak ada produk reptil di database MongoDB Compass.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </section>
    </main>

    <div class="form-modal-overlay" id="productFormModal">
        <div class="form-modal-box">
            <div class="form-card-title" id="modalTitle" style="text-align: left; margin-bottom: 25px; font-weight: 800; font-size: 1.25rem;">Add New Reptile</div>
            <form id="mainCrudForm" method="POST" enctype="multipart/form-data" onsubmit="return validateFormAction(event)">
                @csrf
                <div class="form-grid-inputs">
                    <div class="input-block-group" id="group-name">
                        <label>Reptile Name</label>
                        <input type="text" name="name" id="input-name" placeholder="e.g. Rhinoceros Iguana">
                        <span class="error-message-text">Kolom ini wajib diisi!</span>
                    </div>
                    <div class="input-block-group" id="group-category">
                        <label>Category</label>
                        <div class="select-wrapper-relative">
                            <select name="category" id="input-category" class="filter-select-box" style="padding: 12px 16px;">
                                <option value="">Choose Category</option>
                                <option value="Iguana">Iguana</option>
                                <option value="Gecko">Gecko</option>
                                <option value="Snake">Snake</option>
                                <option value="Tortoise">Tortoise</option>
                            </select>
                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                        </div>
                        <span class="error-message-text">Silakan pilih kategori!</span>
                    </div>
                    <div class="input-block-group" id="group-price">
                        <label>Price (IDR)</label>
                        <input type="text" name="price" id="input-price" placeholder="e.g. 1500000">
                        <span class="error-message-text" id="err-price">Harga wajib diisi angka!</span>
                    </div>
                    <div class="input-block-group" id="group-stock">
                        <label>Stock Qty</label>
                        <input type="number" name="stock" id="input-stock" placeholder="e.g. 5">
                        <span class="error-message-text">Jumlah stok wajib diisi!</span>
                    </div>
                    <div class="input-block-group" id="group-desc">
                        <label>Description</label>
                        <textarea name="desc" id="input-desc" rows="3" placeholder="Kondisi kesehatan reptil mulus..."></textarea>
                        <span class="error-message-text">Deskripsi produk wajib diisi!</span>
                    </div>
                    <div class="input-block-group" id="group-image">
                        <label>Upload Image File</label>
                        <input type="file" name="image" id="input-image">
                        <span class="error-message-text">File foto produk wajib dilampirkan!</span>
                    </div>
                </div>
                <div class="modal-action-buttons">
                    <button type="submit" class="btn-modal-save">Save Data to MongoDB</button>
                    <button type="button" class="btn-modal-cancel" onclick="closeFormModal()">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <div class="confirm-overlay" id="feedbackPopupOverlay">
        <div class="confirm-box" style="max-width: 400px;">
            <div class="confirm-icon-circle" id="feedbackIconBox" style="background-color: #DEF7EC; margin-bottom: 20px;">
                <span id="feedbackEmoji" style="font-size: 1.5rem;">✅</span>
            </div>
            <div class="confirm-title-text" id="feedbackTitle" style="font-size: 1.2rem; margin-bottom: 25px; line-height: 1.4;">Umpan Balik</div>
            <div class="confirm-buttons-flex" id="confirmButtonsArea">
                <button class="btn-confirm-yes" id="btnYesDelete">Ya, Hapus Produk</button>
                <button class="btn-confirm-cancel" onclick="closeFeedbackAlert()">Batalkan</button>
            </div>
            <button class="btn-filter-done" id="btnCloseAlert" style="width: 100%; display: none; padding: 12px;" onclick="closeFeedbackAlert()">OK, Mengerti</button>
        </div>
    </div>

    <script>
        let isEditMode = false;
        let deleteTargetFormId = null;

        function toggleSidebar() {
            document.getElementById('adminSidebar').classList.toggle('open');
            document.getElementById('sidebarOverlay').classList.toggle('show');
        }

        function toggleNotiPanel(event) {
            event.stopPropagation();
            document.getElementById('notiPopoverPanel').classList.toggle('show');
        }

        window.onclick = function(event) {
            if (!event.target.matches('.noti-bell-container') && !event.target.closest('.noti-bell-container')) {
                const panel = document.getElementById('notiPopoverPanel');
                if (panel && panel.classList.contains('show')) {
                    panel.classList.remove('show');
                }
            }
        }

        function openAddModal() {
            isEditMode = false;
            document.getElementById('modalTitle').innerText = "Add New Reptile";
            document.getElementById('mainCrudForm').action = "{{ route('admin.products.store') }}";
            resetFormErrors();
            
            document.getElementById('input-name').value = "";
            document.getElementById('input-category').value = "";
            document.getElementById('input-price').value = "";
            document.getElementById('input-stock').value = "";
            document.getElementById('input-desc').value = "";
            document.getElementById('input-image').value = "";
            
            document.getElementById('productFormModal').classList.add('show');
        }

        function openEditModal(id, name, category, price, stock, desc) {
            isEditMode = true;
            document.getElementById('modalTitle').innerText = "Edit Product Unit";
            document.getElementById('mainCrudForm').action = "/admin/products/update/" + id;
            resetFormErrors();

            document.getElementById('input-name').value = name;
            document.getElementById('input-category').value = category;
            document.getElementById('input-price').value = price;
            document.getElementById('input-stock').value = stock;
            document.getElementById('input-desc').value = desc;
            
            document.getElementById('productFormModal').classList.add('show');
        }

        function closeFormModal() {
            document.getElementById('productFormModal').classList.remove('show');
            
            const iconBox = document.getElementById('feedbackIconBox');
            const emoji = document.getElementById('feedbackEmoji');
            const title = document.getElementById('feedbackTitle');
            const confirmArea = document.getElementById('confirmButtonsArea');
            const closeBtn = document.getElementById('btnCloseAlert');

            confirmArea.style.display = "none";
            closeBtn.style.display = "block";

            iconBox.style.backgroundColor = "#F3F4F6";
            emoji.innerText = "❌";
            title.innerText = isEditMode ? "Perubahan Data Dibatalkan" : "Gagal/Batal Memasukkan Data";
            document.getElementById('feedbackPopupOverlay').classList.add('show');
        }

        function resetFormErrors() {
            document.querySelectorAll('.input-block-group').forEach(group => group.classList.remove('error-validate'));
        }

        function validateFormAction(event) {
            resetFormErrors();
            let isValid = true;

            const name = document.getElementById('input-name');
            const category = document.getElementById('input-category');
            const price = document.getElementById('input-price');
            const stock = document.getElementById('input-stock');
            const desc = document.getElementById('input-desc');
            const image = document.getElementById('input-image');

            if (!name.value.trim()) { document.getElementById('group-name').classList.add('error-validate'); isValid = false; }
            if (!category.value) { document.getElementById('group-category').classList.add('error-validate'); isValid = false; }
            
            if (!price.value.trim()) {
                document.getElementById('err-price').innerText = "Kolom ini wajib diisi!";
                document.getElementById('group-price').classList.add('error-validate');
                isValid = false;
            } else if (isNaN(price.value)) {
                document.getElementById('err-price').innerText = "Harga wajib diisi menggunakan angka numerik!";
                document.getElementById('group-price').classList.add('error-validate');
                isValid = false;
            }

            if (!stock.value.trim()) { document.getElementById('group-stock').classList.add('error-validate'); isValid = false; }
            if (!desc.value.trim()) { document.getElementById('group-desc').classList.add('error-validate'); isValid = false; }
            
            if (!isEditMode && !image.value) { document.getElementById('group-image').classList.add('error-validate'); isValid = false; }

            if (!isValid) {
                event.preventDefault();
                return false;
            }
            return true;
        }

        function triggerDeleteProduct(id) {
            deleteTargetFormId = "delete-form-" + id;
            
            document.getElementById('feedbackIconBox').style.backgroundColor = "#fee2e2";
            document.getElementById('feedbackEmoji').innerText = "⚠️";
            document.getElementById('feedbackTitle').innerText = "Apakah Anda yakin ingin menghapus produk ini secara permanen dari MongoDB?";
            
            document.getElementById('confirmButtonsArea').style.display = "flex";
            document.getElementById('btnCloseAlert').style.display = "none";
            document.getElementById('feedbackPopupOverlay').classList.add('show');
        }

        document.getElementById('btnYesDelete').addEventListener('click', () => {
            if (deleteTargetFormId) {
                document.getElementById(deleteTargetFormId).submit();
            }
        });

        function closeFeedbackAlert() {
            document.getElementById('feedbackPopupOverlay').classList.remove('show');
        }

        document.addEventListener("DOMContentLoaded", () => {
            let flashSuccessMessage = "{{ session('flash_success') }}";
            let flashErrorMessage = "{{ session('flash_error') }}";

            if (flashSuccessMessage && flashSuccessMessage.trim() !== "") {
                const iconBox = document.getElementById('feedbackIconBox');
                const emoji = document.getElementById('feedbackEmoji');
                const title = document.getElementById('feedbackTitle');
                
                iconBox.style.backgroundColor = "#DEF7EC";
                emoji.innerText = "✅";
                title.innerText = flashSuccessMessage;

                document.getElementById('confirmButtonsArea').style.display = "none";
                document.getElementById('btnCloseAlert').style.display = "block";
                document.getElementById('feedbackPopupOverlay').classList.add('show');
            }

            if (flashErrorMessage && flashErrorMessage.trim() !== "") {
                const iconBox = document.getElementById('feedbackIconBox');
                const emoji = document.getElementById('feedbackEmoji');
                const title = document.getElementById('feedbackTitle');
                
                iconBox.style.backgroundColor = "#fee2e2";
                emoji.innerText = "❌";
                title.innerText = flashErrorMessage;

                document.getElementById('confirmButtonsArea').style.display = "none";
                document.getElementById('btnCloseAlert').style.display = "block";
                document.getElementById('feedbackPopupOverlay').classList.add('show');
            }
        });
    </script>
</body>
</html>