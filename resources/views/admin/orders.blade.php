<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Management - Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif !important; box-sizing: border-box; }

        .admin-navbar { background: #FFFFFF; display: flex; align-items: center; padding: 15px 4%; border-bottom: 1px solid #E5E5E5; position: sticky; top:0; z-index: 90; }
        .nav-left-side { display: flex; align-items: center; gap: 20px; }
        .btn-hamburger { background: none; border: none; font-size: 2rem; cursor: pointer; color: #111; font-weight: 800; display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 8px; transition: background 0.2s; }
        .btn-hamburger:hover { background-color: #F5F5F5; }

        .nav-right-side { display: flex; align-items: center; gap: 25px; margin-left: auto; font-weight: 700; position: relative; }
        .admin-profile-wrapper { display: flex; align-items: center; gap: 10px; }

        .noti-bell-container { position: relative; cursor: pointer; color: #333; display: flex; align-items: center; }
        .bell-badge-red { position: absolute; top: 2px; right: 2px; width: 10px; height: 10px; background-color: #ef4444; border-radius: 50%; border: 1.5px solid #FFFFFF; display: block; }

        .floating-noti-dropdown { position: absolute; top: 50px; right: 170px; width: 340px; background: #FFFFFF; border: 1px solid #E5E5E5; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.08); display: none; flex-direction: column; z-index: 100; overflow: hidden; }
        .floating-noti-dropdown.show { display: flex; }
        .noti-dropdown-header { padding: 14px 16px; font-size: 0.9rem; font-weight: 800; border-bottom: 1px solid #F5F5F5; text-align: left; color: #111; }
        .noti-dropdown-body { max-height: 250px; overflow-y: auto; }
        .noti-dropdown-item { padding: 14px 16px; font-size: 0.85rem; font-weight: 600; color: #444; border-bottom: 1px solid #F9F9F9; text-align: left; display: flex; gap: 12px; align-items: flex-start; }
        .noti-dropdown-item svg { color: #6B8E4E; flex-shrink: 0; margin-top: 2px; }
        .btn-read-more-noti { display: block; text-align: center; padding: 12px; background: #F9FAF7; font-size: 0.85rem; font-weight: 700; color: #4A5C3A; text-decoration: none; border-top: 1px solid #E5E5E5; }
        .btn-read-more-noti:hover { background: #F3F4F0; }

        .admin-sidebar-drawer { position: fixed; top: 0; left: -300px; width: 300px; height: 100%; background: #000000; color: white; z-index: 200; padding: 35px 24px; transition: left 0.3s ease; }
        .admin-sidebar-drawer.open { left: 0; }
        .sidebar-brand-title { font-size: 1.3rem; font-weight: 800; margin-bottom: 40px; color: #FFFFFF; text-align: left; }
        .sidebar-menu-ul { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 12px; }
        .sidebar-menu-ul a { display: flex; align-items: center; justify-content: flex-start; gap: 15px; color: #A3A3A3; text-decoration: none; padding: 14px 18px; border-radius: 10px; font-weight: 700; font-size: 0.95rem; width: 100%; text-align: left; }
        .sidebar-menu-ul a:hover, .sidebar-menu-ul .active-menu { background: #283221; color: #FFFFFF; }
        .sidebar-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 150; display: none; backdrop-filter: blur(3px); }
        .sidebar-overlay.show { display: block; }

        /* SIDEBAR FLEX LAYOUT & LOGOUT BUTTON */
        .sidebar-inner-flex { display: flex; flex-direction: column; height: 100%; }
        .sidebar-logout-form { margin-top: auto; width: 100%; padding-top: 20px; }
        .sidebar-logout-btn { width: 100%; background-color: #dc2626; color: #ffffff; border: none; padding: 12px 18px; border-radius: 8px; font-weight: 700; font-size: 0.9rem; cursor: pointer; transition: background 0.2s; display: flex; align-items: center; justify-content: center; gap: 10px; }
        .sidebar-logout-btn:hover { background-color: #b91c1c; }

        .dashboard-main-content { max-width: 100%; width: 100%; margin: 40px auto; padding: 0 4%; }

        .page-headline { font-size: 2.2rem; font-weight: 800; color: #000; margin-bottom: 5px; }
        .page-subheadline { font-size: 1rem; color: #777; margin-bottom: 35px; font-weight: 600; }

        .order-stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 30px; }
        .stat-card-box { background: #FFFFFF; border: 1px solid #E5E5E5; border-radius: 12px; padding: 20px; text-align: left; box-shadow: 0 4px 12px rgba(0,0,0,0.01); }
        .stat-card-title { font-size: 0.85rem; color: #888; font-weight: 600; margin-bottom: 5px; }
        .stat-card-number { font-size: 1.6rem; font-weight: 800; color: #111; }

        .toolbar-flex-control { display: flex; justify-content: space-between; align-items: center; gap: 20px; margin-bottom: 25px; }
        .search-filter-left { display: flex; gap: 15px; flex: 1; }

        .search-wrapper-relative { position: relative; width: 380px; display: flex; align-items: center; }
        .search-wrapper-relative svg { position: absolute; left: 16px; color: #888888; pointer-events: none; }
        .search-input-box { width: 100%; padding: 12px 16px 12px 46px; border: 1px solid #E5E5E5; border-radius: 8px; font-size: 0.9rem; font-weight: 600; outline: none; transition: border-color 0.2s; }
        .search-input-box:focus { border-color: #6B8E4E; }

        .select-wrapper-relative { position: relative; display: flex; align-items: center; }
        .filter-select-box { padding: 12px 40px 12px 16px; border: 1px solid #E5E5E5; border-radius: 8px; background: white; font-size: 0.9rem; font-weight: 600; cursor: pointer; outline: none; appearance: none; -webkit-appearance: none; -moz-appearance: none; }
        .filter-select-box:focus { border-color: #6B8E4E; }
        .select-wrapper-relative svg { position: absolute; right: 16px; color: #666666; pointer-events: none; }

        .table-container-white { background: #FFFFFF; border: 1px solid #E5E5E5; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.01); width: 100%; }
        .crud-table { width: 100%; border-collapse: collapse; text-align: left; }
        .crud-table th { background: #F9FAF7; padding: 16px 20px; font-size: 0.85rem; font-weight: 800; color: #4A5C3A; text-transform: uppercase; border-bottom: 1px solid #E5E5E5; }
        .crud-table td { padding: 16px 20px; border-bottom: 1px solid #F5F5F5; font-size: 0.95rem; font-weight: 600; color: #333; vertical-align: middle; }
        .order-id-green { color: #6B8E4E; font-weight: 800; }

        .badge-status { padding: 6px 14px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; display: inline-block; }
        .badge-status.delivered { background: #DEF7EC; color: #03543F; }
        .badge-status.confirmed { background: #EBF5FF; color: #1E429F; }
        .badge-status.pending   { background: #FEF08A; color: #713F12; }
        .badge-status.cancelled { background: #FEE2E2; color: #9B1C1C; }

        .status-action-form { display: flex; gap: 8px; align-items: center; }
        .status-action-form select { height: 36px; padding: 0 10px; border: 1px solid #E5E5E5; border-radius: 8px; font-size: 0.85rem; font-weight: 600; background: white; cursor: pointer; outline: none; }
        .status-action-form select:focus { border-color: #6B8E4E; }

        .action-buttons-flex { display: flex; gap: 12px; align-items: center; justify-content: center; }
        .btn-icon-action { width: 36px; height: 36px; border-radius: 8px; border: 1px solid #E5E5E5; background: white; cursor: pointer; display: flex; justify-content: center; align-items: center; transition: all 0.2s; color: #555555; }
        .btn-icon-action:hover { border-color: #6B8E4E; background: #F9FAF7; color: #6B8E4E; }
        .btn-icon-action.delete:hover { border-color: #EF4444; background: #FEE2E2; color: #EF4444; }

        /* POP-UP CONFIRMATION (SAMA DENGAN PRODUCTS) */
        .confirm-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.4); backdrop-filter: blur(5px); display: none; justify-content: center; align-items: center; z-index: 600; opacity: 0; transition: opacity 0.3s; }
        .confirm-overlay.show { display: flex; opacity: 1; }
        .confirm-box { background: white; border-radius: 16px; padding: 35px; width: 100%; max-width: 420px; text-align: center; box-shadow: 0 15px 40px rgba(0,0,0,0.15); animation: popScale 0.25s cubic-bezier(0.34, 1.56, 0.64, 1); }
        @keyframes popScale { from { transform: scale(0.8); opacity:0; } to { transform: scale(1); opacity:1; } }
        .confirm-icon-circle { width: 60px; height: 60px; border-radius: 50%; display: flex; justify-content: center; align-items: center; margin: 0 auto 20px auto; font-size: 1.5rem; }
        .confirm-title-text { font-size: 1.2rem; font-weight: 800; color: #111; margin-bottom: 25px; line-height: 1.4; }
        .confirm-buttons-flex { display: flex; gap: 12px; justify-content: center; }
        .btn-confirm-yes { background-color: #EF4444; color: white; border: none; padding: 12px 24px; border-radius: 8px; font-weight: 700; cursor: pointer; flex: 1; }
        .btn-confirm-cancel { background-color: #F3F4F6; color: #4B5563; border: 1px solid #E5E7EB; padding: 12px 24px; border-radius: 8px; font-weight: 700; cursor: pointer; flex: 1; }
        .btn-filter-done { background: #283221; color: white; border: none; padding: 12px; border-radius: 8px; font-weight: 700; cursor: pointer; font-size: 0.9rem; }

        .flash-success-banner { background: #DEF7EC; color: #03543F; border: 1px solid #BBE5C5; padding: 12px 18px; border-radius: 8px; font-size: 0.9rem; font-weight: 600; margin-bottom: 20px; }
        .flash-error-banner { background: #FEE2E2; color: #9B1C1C; border: 1px solid #FCA5A5; padding: 12px 18px; border-radius: 8px; font-size: 0.9rem; font-weight: 600; margin-bottom: 20px; }

        .order-modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.4); backdrop-filter: blur(5px); display: none; justify-content: center; align-items: center; z-index: 999; }
        .order-modal-overlay.show { display: flex; }
        .order-modal-box { background: white; border-radius: 16px; padding: 30px; width: 100%; max-width: 600px; box-shadow: 0 15px 40px rgba(0,0,0,0.15); animation: popScale 0.25s cubic-bezier(0.34, 1.56, 0.64, 1); max-height: 85vh; overflow-y: auto; }
        .detail-grid-info { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px; text-align: left; }
        .detail-info-block { display: flex; flex-direction: column; gap: 4px; }
        .detail-info-block label { font-size: 0.8rem; font-weight: 700; color: #888; text-transform: uppercase; }
        .detail-info-block span { font-size: 0.95rem; font-weight: 600; color: #111; }
        .order-items-list { border: 1px solid #E5E5E5; border-radius: 8px; margin-bottom: 20px; overflow: hidden; }
        .order-item-row { display: flex; justify-content: space-between; align-items: center; padding: 12px 16px; border-bottom: 1px solid #F5F5F5; font-size: 0.9rem; font-weight: 600; }
        .order-item-row:last-child { border-bottom: none; }
    </style>
</head>
<body>

    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <aside class="admin-sidebar-drawer" id="adminSidebar">
        <div class="sidebar-inner-flex">
            <div>
                <div class="sidebar-brand-title">ANARCYXREPTILE <span style="font-size:0.75rem; color:#888; display:block; margin-top:4px; font-weight:600;">Admin Panel</span></div>
                <nav class="sidebar-menu-ul">
                    <a href="{{ route('admin.dashboard') }}">
                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" /></svg> Dashboard
                    </a>
                    <a href="{{ route('admin.products') }}">
                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4L4 7m0 0v10l8 4" /></svg> Product
                    </a>
                    <a href="{{ route('admin.orders') }}" class="active-menu">
                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg> Order
                    </a>
                    <a href="{{ route('admin.education') }}">
                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg> Education
                    </a>
                </nav>
            </div>
            <form action="{{ route('admin.logout') }}" method="POST" class="sidebar-logout-form">
                @csrf
                <button type="submit" class="sidebar-logout-btn">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                    Log Out
                </button>
            </form>
        </div>
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
                    <span class="bell-badge-red"></span>
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
            </div>

            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">@csrf</form>
        </div>
    </div>

    <main class="dashboard-main-content">
        <h1 class="page-headline">Order Management</h1>
        <p class="page-subheadline">View and manage customer orders</p>

        @if(session('flash_success'))
            <div class="flash-success-banner">{{ session('flash_success') }}</div>
        @endif
        @if(session('flash_error'))
            <div class="flash-error-banner">{{ session('flash_error') }}</div>
        @endif
        @if(session('flash_warning'))
            <div class="flash-error-banner" style="background:#FEF3C7;border-color:#F59E0B;color:#92400E;">{{ session('flash_warning') }}</div>
        @endif

        <section class="order-stats-grid">
            <div class="stat-card-box"><div class="stat-card-title">Total Orders</div><div class="stat-card-number">{{ $stats['total'] }}</div></div>
            <div class="stat-card-box"><div class="stat-card-title">Delivered</div><div class="stat-card-number" style="color: #03543F;">{{ $stats['delivered'] }}</div></div>
            <div class="stat-card-box"><div class="stat-card-title">Confirmed</div><div class="stat-card-number" style="color: #1E429F;">{{ $stats['confirmed'] }}</div></div>
            <div class="stat-card-box"><div class="stat-card-title">Pending</div><div class="stat-card-number" style="color: #713F12;">{{ $stats['pending'] }}</div></div>
        </section>

        <section class="toolbar-flex-control">
            <form action="{{ route('admin.orders') }}" method="GET" class="search-filter-left" id="filterForm">
                <div class="search-wrapper-relative">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    <input type="text" name="search" class="search-input-box" placeholder="Search by order number or customer number..." value="{{ request('search') }}" onkeyup="if(event.key === 'Enter') this.form.submit();">
                </div>

                <div class="select-wrapper-relative">
                    <select name="status" class="filter-select-box" onchange="document.getElementById('filterForm').submit();">
                        <option value="all" {{ request('status', 'all') == 'all' ? 'selected' : '' }}>All Status</option>
                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="pending"   {{ request('status') == 'pending'   ? 'selected' : '' }}>Pending</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                </div>
            </form>
        </section>

        <section class="table-container-white">
            <table class="crud-table">
                <thead>
                    <tr>
                        <th>Order Number</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th style="text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td class="order-id-green">{{ $order->display_id }}</td>
                        <td>{{ $order->customer_name ?? 'Guest User' }}</td>
                        <td>{{ $order->display_date }}</td>
                        <td>{{ $order->item_count }} item(s)</td>
                        <td style="font-weight: 800; color: #283221;">Rp {{ number_format((int)($order->total_amount ?? $order->total_price ?? 0), 0, ',', '.') }}</td>
                        <td><span class="badge-status {{ $order->status }}">{{ ucfirst($order->status) }}</span></td>
                        <td>
                            <div class="action-buttons-flex">
                                @if($order->status === 'pending')
                                    <button type="button" onclick="openAdminActionModal('terima', '{{ route('admin.orders.approve', $order->_id) }}')" style="cursor: pointer; background: #6B8E4E; color: white; border: none; padding: 6px 12px; border-radius: 6px; font-weight: 600; font-size: 0.8rem;">Terima & Proses</button>
                                    <button type="button" onclick="event.preventDefault(); openAdminActionModal('tolak', '{{ route('admin.orders.reject', $order->_id) }}')" style="cursor: pointer; background: #991b1b; color: white; border: none; padding: 6px 12px; border-radius: 6px; font-weight: 600; font-size: 0.8rem; margin-left: 5px; transition: background 0.2s;" onmouseover="this.style.background='#7f1d1d'" onmouseout="this.style.background='#991b1b'">Tolak</button>
                                @else
                                    <form action="{{ route('admin.orders.updateStatus', $order->_id) }}" method="POST" class="status-action-form">
                                        @csrf
                                        <select name="status">
                                            <option value="pending"   {{ $order->status == 'pending'   ? 'selected' : '' }}>Pending</option>
                                            <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                        <button type="submit" class="btn-icon-action" title="Update Status">
                                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                        </button>
                                    </form>
                                @endif
                                <button type="button" class="btn-icon-action" onclick='openOrderDetailModal(@json($order))' title="Lihat Detail Pesanan">
                                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                                <a href="{{ route('admin.orders.invoice', $order->_id) }}" class="btn-icon-action" title="Download Invoice PDF" target="_blank">
                                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </a>
                                <button class="btn-icon-action delete" onclick="triggerDeleteOrder('{{ $order->_id }}', '{{ $order->display_id }}')" title="Delete Order">
                                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3" /></svg>
                                </button>
                                <form id="delete-order-form-{{ $order->_id }}" action="{{ route('admin.orders.delete', $order->_id) }}" method="POST" style="display:none;">@csrf @method('DELETE')</form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" style="text-align: center; color: #888; padding: 40px 0;">Belum ada pesanan di database MongoDB.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </section>
    </main>



    <div class="confirm-overlay" id="feedbackPopupOverlay">
        <div class="confirm-box" style="max-width: 400px;">
            <div class="confirm-icon-circle" id="feedbackIconBox" style="background-color: #fee2e2; margin-bottom: 20px;">
                <span id="feedbackEmoji" style="font-size: 1.5rem;">⚠️</span>
            </div>
            <div class="confirm-title-text" id="feedbackTitle" style="font-size: 1.2rem; margin-bottom: 25px; line-height: 1.4;">Apakah Anda yakin ingin menghapus pesanan ini secara permanen dari MongoDB?</div>
            <div class="confirm-buttons-flex" id="confirmButtonsArea">
                <button class="btn-confirm-yes" id="btnYesDelete">Ya, Hapus Pesanan</button>
                <button class="btn-confirm-cancel" onclick="closeFeedbackAlert()">Batalkan</button>
            </div>
            <button class="btn-filter-done" id="btnCloseAlert" style="width: 100%; display: none; padding: 12px;" onclick="closeFeedbackAlert()">OK, Mengerti</button>
        </div>
    </div>

    <!-- Modal Detail Pesanan -->
    <div class="order-modal-overlay" id="orderDetailModal">
        <div class="order-modal-box">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #F5F5F5; padding-bottom: 12px;">
                <h3 style="font-size: 1.2rem; font-weight: 800; color: #111; margin: 0;">Rincian Pesanan <span id="lbl-order-number" style="color: #6B8E4E;"></span></h3>
                <button type="button" style="background: none; border: none; font-size: 1.5rem; color: #aaa; cursor: pointer;" onclick="closeOrderModal()">&times;</button>
            </div>
            <div class="detail-grid-info">
                <div class="detail-info-block"><label>Nama Pembeli</label><span id="lbl-customer-name"></span></div>
                <div class="detail-info-block"><label>WhatsApp</label><span id="lbl-customer-phone"></span></div>
                <div class="detail-info-block" style="grid-column: span 2;"><label>Alamat Lengkap</label><span id="lbl-customer-address"></span></div>
                <div class="detail-info-block"><label>Status</label><span id="lbl-payment-status"></span></div>
                <div class="detail-info-block"><label>Waktu Transaksi</label><span id="lbl-order-date"></span></div>
            </div>
            <h4 style="font-size: 0.85rem; font-weight: 800; color: #4A5C3A; text-align: left; text-transform: uppercase; margin-bottom: 10px;">Daftar Item Reptil / Produk:</h4>
            <div class="order-items-list" id="lbl-order-items-container"></div>
            <div style="display: flex; justify-content: space-between; align-items: center; background: #F9FAF7; padding: 14px 20px; border-radius: 8px; font-weight: 800; font-size: 1.05rem; color: #111; margin-bottom: 0;">
                <span>Total Belanja:</span><span id="lbl-total-price" style="color: #283221;"></span>
            </div>
        </div>
    </div>

    <!-- Admin Action Modal -->
    <div id="adminActionModal" style="display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.8); backdrop-filter: blur(4px); align-items: center; justify-content: center;">
        <div style="background: #1A2315; border: 2px solid #6B8E4E; border-radius: 16px; padding: 30px; width: 90%; max-width: 400px; text-align: center; box-shadow: 0 10px 30px rgba(0,0,0,0.5); font-family: sans-serif;">
            
            <div id="modalIconContainer" style="margin-bottom: 15px;"></div>
            
            <h3 id="modalTitle" style="color: #FFFFFF; font-size: 1.25rem; margin: 0 0 10px 0; font-weight: 800;"></h3>
            <p id="modalDescription" style="color: #BBE5C5; font-size: 0.9rem; line-height: 1.5; margin: 0 0 25px 0;"></p>
            
            <form id="modalActionForm" method="POST" style="margin: 0;">
                @csrf
                <div style="display: flex; gap: 12px; justify-content: center;">
                    <button type="button" onclick="closeAdminActionModal()" style="flex: 1; background: transparent; color: #BBE5C5; border: 1px solid rgba(255,255,255,0.2); padding: 10px; border-radius: 8px; font-weight: 700; font-size: 0.85rem; cursor: pointer;">Batal</button>
                    <button type="submit" id="modalSubmitBtn" style="flex: 1; color: white; border: none; padding: 10px; border-radius: 8px; font-weight: 700; font-size: 0.85rem; cursor: pointer;"></button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let deleteOrderTargetFormId = null;

        function openAdminActionModal(type, actionUrl) {
            const modal = document.getElementById('adminActionModal');
            const form = document.getElementById('modalActionForm');
            const iconContainer = document.getElementById('modalIconContainer');
            const title = document.getElementById('modalTitle');
            const description = document.getElementById('modalDescription');
            const submitBtn = document.getElementById('modalSubmitBtn');
            
            form.action = actionUrl;
            modal.style.display = 'flex';
            
            if (type === 'terima') {
                title.innerText = 'Terima Pesanan?';
                description.innerText = 'Pesanan akan dikonfirmasi dan statusnya akan berubah menjadi sedang diproses.';
                submitBtn.innerText = 'Ya, Proses';
                submitBtn.style.background = '#6B8E4E';
                iconContainer.innerHTML = `
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#6B8E4E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: inline-block;">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>`;
            } else {
                title.innerText = 'Tolak Pesanan?';
                description.innerText = 'Apakah Anda yakin ingin membatalkan dan menolak transaksi pesanan pelanggan ini?';
                submitBtn.innerText = 'Ya, Tolak';
                submitBtn.style.background = '#991b1b';
                iconContainer.innerHTML = `
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#991b1b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: inline-block;">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="15" y1="9" x2="9" y2="15"></line>
                    </svg>`;
            }
        }
        
        function closeAdminActionModal() {
            document.getElementById('adminActionModal').style.display = 'none';
        }

        function toggleSidebar() {
            var sidebar = document.getElementById('adminSidebar');
            var overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('open');
            overlay.classList.toggle('show');
            localStorage.setItem('sidebarStatus', sidebar.classList.contains('open') ? 'open' : 'closed');
        }

        document.addEventListener('DOMContentLoaded', function() {
            if (localStorage.getItem('sidebarStatus') === 'open') {
                document.getElementById('adminSidebar').classList.add('open');
                document.getElementById('sidebarOverlay').classList.add('show');
            }
        });

        document.addEventListener('click', function(event) {
            var sidebar = document.getElementById('adminSidebar');
            if (sidebar.classList.contains('open')) {
                if (!sidebar.contains(event.target) && !event.target.closest('.btn-hamburger')) {
                    sidebar.classList.remove('open');
                    document.getElementById('sidebarOverlay').classList.remove('show');
                    localStorage.setItem('sidebarStatus', 'closed');
                }
            }
        });

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

        function triggerDeleteOrder(id, displayId) {
            deleteOrderTargetFormId = "delete-order-form-" + id;

            document.getElementById('feedbackIconBox').style.backgroundColor = "#fee2e2";
            document.getElementById('feedbackEmoji').innerText = "⚠️";
            document.getElementById('feedbackTitle').innerText = "Apakah Anda yakin ingin menghapus pesanan " + displayId + " secara permanen dari MongoDB?";

            document.getElementById('confirmButtonsArea').style.display = "flex";
            document.getElementById('btnCloseAlert').style.display = "none";
            document.getElementById('feedbackPopupOverlay').classList.add('show');
        }

        document.getElementById('btnYesDelete').addEventListener('click', () => {
            if (deleteOrderTargetFormId) {
                document.getElementById(deleteOrderTargetFormId).submit();
            }
        });

        function closeFeedbackAlert() {
            document.getElementById('feedbackPopupOverlay').classList.remove('show');
        }

        function openOrderDetailModal(order) {
            const orderNum = order.order_number || (order.id ? order.id : (order._id ? order._id.substring(0, 8) : '0000'));
            document.getElementById('lbl-order-number').innerText = "#" + orderNum;
            document.getElementById('lbl-customer-name').innerText = order.customer_name || '';
            document.getElementById('lbl-customer-phone').innerText = order.customer_phone || '-';
            document.getElementById('lbl-customer-address').innerText = order.customer_address || '-';
            document.getElementById('lbl-payment-status').innerText = (order.status || 'pending').toUpperCase();

            let dateObj = new Date(order.created_at);
            document.getElementById('lbl-order-date').innerText = order.created_at ? dateObj.toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute:'2-digit'}) : '-';

            let formatter = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 });
            document.getElementById('lbl-total-price').innerText = formatter.format(order.total_price);

            let itemsContainer = document.getElementById('lbl-order-items-container');
            itemsContainer.innerHTML = "";

            if (order.items && order.items.length > 0) {
                order.items.forEach(item => {
                    let row = document.createElement('div');
                    row.className = "order-item-row";
                    let productName = item.product_name || item.name || 'Produk';
                    let price = item.price || 0;
                    let qty = item.qty || 1;
                    row.innerHTML = `
                        <div style="text-align: left;">
                            <div style="color: #111; font-weight: 700;">${productName}</div>
                            <div style="font-size: 0.75rem; color: #888;">${qty} unit &times; ${formatter.format(price)}</div>
                        </div>
                        <div style="color: #283221; font-weight: 700;">${formatter.format(qty * price)}</div>
                    `;
                    itemsContainer.appendChild(row);
                });
            } else {
                itemsContainer.innerHTML = `<div style="padding: 15px; color: #888; font-style: italic;">Tidak ada rincian item barang.</div>`;
            }
            document.getElementById('orderDetailModal').classList.add('show');
        }

        function closeOrderModal() {
            document.getElementById('orderDetailModal').classList.remove('show');
        }

        document.addEventListener("DOMContentLoaded", () => {
            let flashSuccessMessage = `{!! addslashes(session('flash_success') ?? '') !!}`;
            let flashErrorMessage = `{!! addslashes(session('flash_error') ?? '') !!}`;

            if (flashSuccessMessage && flashSuccessMessage.trim() !== "") {
                document.getElementById('feedbackIconBox').style.backgroundColor = "#DEF7EC";
                document.getElementById('feedbackEmoji').innerText = "✅";
                document.getElementById('feedbackTitle').innerText = flashSuccessMessage;
                document.getElementById('confirmButtonsArea').style.display = "none";
                document.getElementById('btnCloseAlert').style.display = "block";
                document.getElementById('feedbackPopupOverlay').classList.add('show');
            }

            if (flashErrorMessage && flashErrorMessage.trim() !== "") {
                document.getElementById('feedbackIconBox').style.backgroundColor = "#fee2e2";
                document.getElementById('feedbackEmoji').innerText = "❌";
                document.getElementById('feedbackTitle').innerText = flashErrorMessage;
                document.getElementById('confirmButtonsArea').style.display = "none";
                document.getElementById('btnCloseAlert').style.display = "block";
                document.getElementById('feedbackPopupOverlay').classList.add('show');
            }
        });
    </script>
</body>
</html>
