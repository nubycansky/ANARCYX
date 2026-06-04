<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification Center - Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        /* --- MEMASTIKAN FONT SAMA --- */
        * { font-family: 'Plus Jakarta Sans', sans-serif !important; box-sizing: border-box; }
        
        .noti-msg-text { font-weight: 700; color: #111; }
        .noti-time-stamp { color: #888; font-weight: 600; font-size: 0.8rem; }
        .admin-navbar { background: #FFFFFF; display: flex; justify-content: space-between; align-items: center; padding: 15px 4%; border-bottom: 1px solid #E5E5E5; }
        .noti-page-wrapper { max-width: 900px; margin: 40px auto 60px auto; padding: 0 20px; }
        
        /* HEADER FLEX UNTUK TOMBOL */
        .noti-header-flex { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 2px solid #F0F0F0; padding-bottom: 8px; }
        .noti-section-group { margin-bottom: 40px; text-align: left; }
        .noti-group-title { font-size: 1.1rem; font-weight: 800; color: #4A5C3A; text-transform: uppercase; letter-spacing: 0.5px; margin: 0; }
        
        .noti-row-card { background: #FFFFFF; border: 1px solid #E5E5E5; border-radius: 12px; padding: 16px 20px; display: flex; align-items: center; justify-content: space-between; gap: 20px; margin-bottom: 12px; }
        .noti-left { display: flex; align-items: center; gap: 15px; }
        .icon-indicator-circle { width: 38px; height: 38px; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-size: 1.1rem; }
        
        /* STYLE BUTTON MERAH TRANSPARAN KUSTOM */
        .btn-clear-all-noti {
            background-color: rgba(220, 38, 38, 0.1);
            border: none;
            color: #dc2626;
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 700;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .btn-clear-all-noti:hover {
            background-color: #dc2626;
            color: #ffffff;
            transform: translateY(-1px);
        }
        .btn-delete-single-noti { background: none; border: none; color: #aaa; font-size: 1.4rem; cursor: pointer; transition: color 0.2s; padding: 0 5px; line-height: 1; }
        .btn-delete-single-noti:hover { color: #ef4444; }
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
        .btn-filter-done:hover { background-color: #4A5C3A; }
    </style>
</head>
<body>

    <div class="admin-navbar">
        <div style="font-weight: 800;"><a href="{{ route('admin.dashboard') }}" style="text-decoration: none; color: #283221;">&larr; Kembali ke Dashboard Overview</a></div>
        <div style="font-weight: 700; color: #666;">Notification Center</div>
    </div>

    <div class="noti-page-wrapper">
        
        <div class="noti-section-group">
            <div class="noti-header-flex">
                <div class="noti-group-title">Baru Saja</div>
                <form action="{{ route('admin.notifications.clear') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengosongkan seluruh riwayat notifikasi secara permanen dari MongoDB?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-clear-all-noti">Hapus Semua Notifikasi</button>
                </form>
            </div>

            @forelse($recentNotifications as $rn)
                <div class="noti-row-card">
                    <div class="noti-left">
                        <div class="icon-indicator-circle" style="background-color: #EBF5FF;">{{ $rn->type == 'order' ? '🛒' : '👥' }}</div>
                        <span class="noti-msg-text">{{ $rn->message }}</span>
                    </div>
                    <span class="noti-time-stamp">{{ $rn->created_at ? $rn->created_at->diffForHumans() : 'Baru saja' }}</span>
                    <form action="{{ route('admin.notifications.destroy', $rn->id) }}" method="POST" id="delete-form-{{ $rn->id }}" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn-delete-single-noti" onclick="openSingleDeleteModal('delete-form-{{ $rn->id }}')">&times;</button>
                    </form>
                </div>
            @empty
                <p style="color: #888; font-size: 0.9rem; font-style: italic; margin-top: 15px;">Tidak ada notifikasi baru hari ini.</p>
            @endforelse
        </div>

        <div class="noti-section-group">
            <div class="noti-header-flex" style="border-bottom: 2px solid #F0F0F0; margin-bottom: 20px; padding-bottom: 8px;">
                <div class="noti-group-title">Minggu Lalu</div>
            </div>

            @forelse($lastWeekNotifications as $lwn)
                <div class="noti-row-card">
                    <div class="noti-left">
                        <div class="icon-indicator-circle" style="background-color: #FDF2E9;">⚙️</div>
                        <span class="noti-msg-text">{{ $lwn->message }}</span>
                    </div>
                    <span class="noti-time-stamp">{{ $lwn->created_at ? $lwn->created_at->translatedFormat('d M Y') : 'Minggu lalu' }}</span>
                    <form action="{{ route('admin.notifications.destroy', $lwn->id) }}" method="POST" id="delete-form-{{ $lwn->id }}" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn-delete-single-noti" onclick="openSingleDeleteModal('delete-form-{{ $lwn->id }}')">&times;</button>
                    </form>
                </div>
            @empty
                <p style="color: #888; font-size: 0.9rem; font-style: italic; margin-top: 15px;">Tidak ada riwayat notifikasi dari minggu lalu.</p>
            @endforelse
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
        let deleteTargetFormId = null;

        function closeFeedbackAlert() {
            document.getElementById('feedbackPopupOverlay').classList.remove('show');
        }

        function openSingleDeleteModal(formId) {
            deleteTargetFormId = formId;

            document.getElementById('feedbackIconBox').style.backgroundColor = "#fee2e2";
            document.getElementById('feedbackEmoji').innerText = "⚠️";
            document.getElementById('feedbackTitle').innerText = "Apakah Anda yakin ingin menghapus notifikasi ini dari MongoDB?";

            document.getElementById('confirmButtonsArea').style.display = "flex";
            document.getElementById('btnCloseAlert').style.display = "none";

            document.getElementById('btnYesDelete').onclick = function() {
                if (deleteTargetFormId) {
                    document.getElementById(deleteTargetFormId).submit();
                }
            };

            document.getElementById('feedbackPopupOverlay').classList.add('show');
        }
    </script>
</body>
</html>