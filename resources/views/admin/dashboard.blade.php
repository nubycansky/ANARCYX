<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - AnarcyxReptile</title>
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
        
        /* REVISI: Navigasi Kanan Berjejer Memanjang Rapi, Tidak Mepet Kanan */
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

        /* --- INTERAKTIF SIDEBAR DRAWER PANEL RATA KIRI PENUH (MANDAT 1) --- */
        .admin-sidebar-drawer { position: fixed; top: 0; left: -300px; width: 300px; height: 100%; background: #000000; color: white; z-index: 200; padding: 35px 24px; transition: left 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .admin-sidebar-drawer.open { left: 0; }
        .sidebar-brand-title { font-size: 1.3rem; font-weight: 800; margin-bottom: 40px; color: #FFFFFF; text-align: left; }
        .sidebar-menu-ul { list-style: none; display: flex; flex-direction: column; gap: 12px; padding: 0; margin: 0; }
        .sidebar-menu-ul a { display: flex; align-items: center; justify-content: flex-start; gap: 15px; color: #A3A3A3; text-decoration: none; padding: 14px 18px; border-radius: 10px; font-weight: 700; font-size: 0.95rem; transition: all 0.2s; text-align: left; width: 100%; }
        .sidebar-menu-ul a:hover, .sidebar-menu-ul .active-menu { background: #283221; color: #FFFFFF; }
        .sidebar-menu-ul svg { flex-shrink: 0; }
        .sidebar-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 150; display: none; backdrop-filter: blur(3px); }
        .sidebar-overlay.show { display: block; }

        /* DASHBOARD GRID CONTENT */
        .dashboard-main-content { max-width: 100%; width: 100%; margin: 40px auto; padding: 0 4%; }
        .page-headline { font-size: 2.2rem; font-weight: 800; color: #111; margin-bottom: 6px; text-align: left; }
        .page-subheadline { font-size: 0.95rem; color: #666; margin-bottom: 35px; text-align: left; }
        
        /* REVISI KARTU METRIK INTERAKTIF DENGAN SVG OUTLINE AMAN BEBAS ERROR */
        .metrics-grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 40px; }
        .metric-card-white { background: #FFFFFF; border: 1px solid #E5E5E5; border-radius: 14px; padding: 24px; display: flex; flex-direction: column; align-items: flex-start; gap: 12px; position: relative; transition: transform 0.2s, box-shadow 0.2s; cursor: pointer; box-shadow: 0 4px 15px rgba(0,0,0,0.01); }
        .metric-card-white:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
        .metric-icon-box { width: 44px; height: 44px; border-radius: 10px; display: flex; justify-content: center; align-items: center; border: 1px solid #E5E5E5; background-color: #F9FAF7; color: #4A5C3A; }
        .metric-icon-box svg { color: #4A5C3A; }

        .metric-info-text { display: flex; flex-direction: column; gap: 2px; text-align: left; width: 100%; }
        .metric-label { font-size: 0.85rem; color: #888888; font-weight: 600; }
        .metric-value { font-size: 1.5rem; font-weight: 800; color: #111111; letter-spacing: -0.5px; }

        /* REVISI UTAMA: REVENUE CHART DILEBARKAN (1.5fr) DAN PIE CHART DIWADAHI PROPORSIONAL (1fr) */
        .chart-grid-2 { display: grid; grid-template-columns: 1.5fr 1fr; gap: 30px; margin-bottom: 40px; align-items: stretch; }
        .chart-card { background: #FFFFFF; border: 1px solid #E5E5E5; border-radius: 16px; padding: 24px; box-shadow: 0 4px 15px rgba(0,0,0,0.01); display: flex; flex-direction: column; }
        .chart-card-title { font-size: 1.1rem; font-weight: 800; color: #111; margin-bottom: 25px; text-align: left; }
        
        /* Tinggi Kanvas Ditingkatkan Menjadi 350px Agar Lebar & Padat */
        .chart-wrapper-line { width: 100%; height: 350px; position: relative; flex-grow: 1; }
        .chart-wrapper-pie { max-width: 280px; height: 350px; margin: 0 auto; position: relative; width: 100%; display: flex; align-items: center; justify-content: center; }

        /* TABLES SECTION */
        .tables-grid-2 { display: grid; grid-template-columns: 1fr 1.2fr; gap: 30px; }
        .table-box-white { background: #FFFFFF; border: 1px solid #E5E5E5; border-radius: 16px; padding: 24px; }
        .list-stack-group { display: flex; flex-direction: column; gap: 14px; margin-top: 15px; }
        .list-row-item { display: flex; justify-content: space-between; align-items: center; padding-bottom: 14px; border-bottom: 1px solid #F5F5F5; }
        .item-left-flex { display: flex; align-items: center; gap: 15px; }
        .number-green-box { width: 28px; height: 28px; background: #C9E4A4; color: #4A5C3A; font-weight: 800; border-radius: 6px; display: flex; justify-content: center; align-items: center; font-size: 0.85rem; }
        .product-title-bold { font-size: 0.95rem; font-weight: 700; color: #111; }
        .sales-count-gray { font-size: 0.9rem; color: #666; font-weight: 600; }

        .badge-status { padding: 6px 14px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; }
        .badge-status.delivered { background: #DEF7EC; color: #03543F; }
        .badge-status.confirmed { background: #EBF5FF; color: #1E429F; }
        .badge-status.pending { background: #FEF08A; color: #713F12; }
    </style>
</head>
<body>

    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>
    
    <aside class="admin-sidebar-drawer" id="adminSidebar">
        <div class="sidebar-brand-title">ANARCYXREPTILE <span style="font-size:0.75rem; color:#888; display:block; margin-top:4px; font-weight:600;">Admin Panel</span></div>
        <nav class="sidebar-menu-ul">
            <a href="#" class="active-menu">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" /></svg>
                Dashboard Overview
            </a>
            <a href="{{ route('admin.products') }}">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4L4 7m0 0v10l8 4" /></svg>
                Products
            </a>
            <a href="{{ route('admin.orders') }}">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                Order Management
            </a>
            <a href="{{ route('admin.education') }}">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                Education Management
            </a>
            <a href="{{ route('admin.notifications') }}">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                Notifications
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
        <h1 class="page-headline">Dashboard Overview</h1>
        <p class="page-subheadline">Welcome back! Here's what's happening with your store.</p>

        <section class="metrics-grid-4">
            <div class="metric-card-white">
                <div class="metric-icon-box">
                    <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div class="metric-info-text">
                    <span class="metric-label">Total Revenue</span>
                    <span class="metric-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
                </div>
            </div>
            <div class="metric-card-white">
                <div class="metric-icon-box">
                    <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                </div>
                <div class="metric-info-text">
                    <span class="metric-label">Total Orders</span>
                    <span class="metric-value">{{ $totalOrders }}</span>
                </div>
            </div>
            <div class="metric-card-white">
                <div class="metric-icon-box">
                    <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4L4 7m0 0v10l8 4" /></svg>
                </div>
                <div class="metric-info-text">
                    <span class="metric-label">Total Products</span>
                    <span class="metric-value">{{ $totalProducts }}</span>
                </div>
            </div>
            <div class="metric-card-white">
                <div class="metric-icon-box">
                    <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                </div>
                <div class="metric-info-text">
                    <span class="metric-label">New Customers</span>
                    <span class="metric-value">{{ $newCustomers }}</span>
                </div>
            </div>
        </section>

        <section class="chart-grid-2">
            <div class="chart-card">
                <div class="chart-card-title">Revenue Overview <span style="font-weight:500; color:#888; font-size:0.85rem;">— Monthly revenue and orders</span></div>
                <div class="chart-wrapper-line">
                    <canvas id="lineChartRevenue"></canvas>
                </div>
            </div>
            <div class="chart-card">
                <div class="chart-card-title">Sales by Category <span style="font-weight:500; color:#888; font-size:0.85rem;">— Distribution of sales</span></div>
                <div class="chart-wrapper-pie">
                    <canvas id="pieChartCategory"></canvas>
                </div>
            </div>
        </section>

        <section class="tables-grid-2">
            <div class="table-box-white">
                <div class="chart-card-title">Top Selling Products</div>
                <div class="list-stack-group">
                    @forelse($topProducts as $index => $prod)
                    <div class="list-row-item">
                        <div class="item-left-flex">
                            <div class="number-green-box">{{ $index + 1 }}</div>
                            <span class="product-title-bold">{{ $prod->name }}</span>
                        </div>
                        <span class="sales-count-gray">{{ $prod->sales_count }} sales</span>
                    </div>
                    @empty
                    <div class="sales-count-gray">Katalog produk MongoDB masih kosong.</div>
                    @endforelse
                </div>
            </div>

            <div class="table-box-white">
                <div class="chart-card-title">Recent Orders</div>
                <div class="list-stack-group">
                    @forelse($recentOrders as $order)
                    <div class="list-row-item">
                        <div class="item-left-flex">
                            <span class="product-title-bold" style="color: #6B8E4E;">{{ $order->order_id_string ?? '#ORD-' . substr((string)$order->_id, -5) }}</span>
                            <span class="sales-count-gray">{{ $order->customer_name ?? 'Guest User' }}</span>
                        </div>
                        <span class="badge-status {{ $order->status }}">{{ $order->status }}</span>
                    </div>
                    @empty
                    <div class="sales-count-gray">Belum ada transaksi di database.</div>
                    @endforelse
                </div>
            </div>
        </section>
    </main>

    <footer>
        <div class="footer-grid">
            <div class="footer-brand">
                <h3>ANARCYXREPTILE</h3>
                <p class="footer-desc">Penyedia reptil eksotis terpercaya berkualitas tinggi, sehat, dan aman.</p>
            </div>
            <div class="footer-links">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="#">Shop</a></li>
                    <li><a href="#">Education</a></li>
                    <li><a href="#">Cart</a></li>
                </ul>
            </div>
            <div class="footer-contact">
                <h4>Contact</h4>
                <p>WhatsApp: +62 812-3456-7890<br>Jakarta, Indonesia</p>
            </div>
        </div>
        <div class="footer-bottom-copyright">&copy; 2026 AnarcyxReptile. All Rights Reserved.</div>
    </footer>

    <script>
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

        // REVISI ENGINE LINE CHART - MELEBAR PENUH TANPA SPACE KOSONG BERLEBIH
        const ctxLine = document.getElementById('lineChartRevenue').getContext('2d');
        new Chart(ctxLine, {
            type: 'line',
            data: {
                labels: ['Januari', 'Februari', 'Maret', 'April'],
                datasets: [{
                    label: 'Tren Pendapatan',
                    data: [400000, 800000, 1200000, 1500000],
                    borderColor: '#283221', 
                    backgroundColor: 'rgba(40, 50, 33, 0.02)',
                    tension: 0.4, 
                    pointBackgroundColor: '#283221', 
                    pointRadius: 5, 
                    pointHoverRadius: 7,
                    fill: true
                }]
            },
            options: { 
                responsive: true,
                maintainAspectRatio: false, // Membebaskan dimensi grafik mengikuti kontainer luar (Melebar Sempurna)
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: { font: { weight: '600' } }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#F3F4F6' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });

        // Pie Chart - Mengambil data kategori bersih dari Controller dengan aman
        const ctxPie = document.getElementById('pieChartCategory').getContext('2d');
        new Chart(ctxPie, {
            type: 'pie',
            data: {
                labels: ['Snakes', 'Lizards', 'Geckos', 'Turtles'],
                datasets: [{ 
                    // REVISI: Menggunakan JSON.parse + Trik String Blade agar Editor tidak mengira ini TypeScript
                    data: JSON.parse('{!! json_encode($categoryData) !!}'), 
                    backgroundColor: ['#283221', '#4A5C3A', '#6B8E4E', '#C9E4A4'],
                    borderWidth: 2,
                    borderColor: '#FFFFFF'
                }]
            },
            options: { 
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 12, font: { size: 11, weight: '600' } }
                    }
                }
            }
        });
    </script>
</body>
</html>