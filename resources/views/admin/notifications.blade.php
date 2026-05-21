<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification Center - Admin</title>
    <link href="https://fonts.googleapis.com/css2=family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif !important; }
        .noti-msg-text { font-weight: 700; color: #111; }
        .noti-time-stamp { color: #888; font-weight: 600; font-size: 0.8rem; }
        .admin-navbar { background: #FFFFFF; display: flex; justify-content: space-between; align-items: center; padding: 15px 4%; border-bottom: 1px solid #E5E5E5; }
        .noti-page-wrapper { max-width: 900px; margin: 40px auto 60px auto; padding: 0 20px; }
        .noti-section-group { margin-bottom: 40px; text-align: left; }
        .noti-group-title { font-size: 1.1rem; font-weight: 800; color: #4A5C3A; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 20px; border-bottom: 2px solid #F0F0F0; padding-bottom: 8px; }
        
        .noti-row-card { background: #FFFFFF; border: 1px solid #E5E5E5; border-radius: 12px; padding: 16px 20px; display: flex; align-items: center; justify-content: space-between; gap: 20px; margin-bottom: 12px; }
        .noti-left { display: flex; align-items: center; gap: 15px; }
        .icon-indicator-circle { width: 38px; height: 38px; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-size: 1.1rem; }
    </style>
</head>
<body>

    <div class="admin-navbar">
        <div style="font-weight: 800;"><a href="{{ route('admin.dashboard') }}" style="text-decoration: none; color: #283221;">&larr; Kembali ke Dashboard Overview</a></div>
        <div style="font-weight: 700; color: #666;">Notification Center</div>
    </div>

    <div class="noti-page-wrapper">
        
        <div class="noti-section-group">
            <div class="noti-group-title">Baru Saja</div>
            @forelse($recentNotifications as $rn)
                <div class="noti-row-card">
                    <div class="noti-left">
                        <div class="icon-indicator-circle" style="background-color: #EBF5FF;">{{ $rn->type == 'order' ? '🛒' : '👥' }}</div>
                        <span class="noti-msg-text">{{ $rn->message }}</span>
                    </div>
                    <span class="noti-time-stamp">10 menit yang lalu</span>
                </div>
            @empty
                <p style="color: #888; font-size: 0.9rem; font-style: italic;">Tidak ada notifikasi baru hari ini.</p>
            @endforelse
        </div>

        <div class="noti-section-group">
            <div class="noti-group-title">Minggu Lalu</div>
            @forelse($lastWeekNotifications as $lwn)
                <div class="noti-row-card">
                    <div class="noti-left">
                        <div class="icon-indicator-circle" style="background-color: #FDF2E9;">⚙️</div>
                        <span class="noti-msg-text">{{ $lwn->message }}</span>
                    </div>
                    <span class="noti-time-stamp">12 Mei 2026</span>
                </div>
            @empty
                <p style="color: #888; font-size: 0.9rem; font-style: italic;">Tidak ada riwayat notifikasi dari minggu lalu.</p>
            @endforelse
        </div>

    </div>

</body>
</html>