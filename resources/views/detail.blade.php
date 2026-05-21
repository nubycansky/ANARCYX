<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $reptile->name }} - Detail Sahabat</title>
    <link href="https://fonts.googleapis.com/css2 family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        .detail-wrapper {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
            padding: 60px 8%;
        }
        .detail-image-box {
            width: 100%;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }
        .detail-image-box img {
            width: 100%;
            height: auto;
            display: block;
        }
        .detail-info-box h2 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 10px;
        }
        .detail-price {
            font-size: 1.8rem;
            color: var(--title-color);
            font-weight: 700;
            margin-bottom: 20px;
        }
        .detail-desc-text {
            font-size: 1rem;
            line-height: 1.7;
            color: #555;
            margin-bottom: 30px;
        }
        @media (max-width: 768px) {
            .detail-wrapper { grid-template-columns: 1fr; gap: 30px; padding: 40px 5%; }
        }
    </style>
</head>
<body>

    <nav>
        <div class="logo-container">
            <img src="https://via.placeholder.com/40" alt="Logo">
            <span class="brand-name">ANARCYXREPTILE</span>
        </div>
    </nav>

    <div class="detail-wrapper">
        <div class="detail-image-box">
            <img src="{{ $reptile->image ? asset('storage/' . $reptile->image) : 'https://images.unsplash.com/photo-1601758124510-52d02ddb7cbd?auto=format&fit=crop&q=80&w=800' }}" alt="{{ $reptile->name }}">
        </div>
        <div class="detail-info-box">
            <div class="badge-container">
                <span class="badge">{{ $reptile->category }}</span>
            </div>
            <h2>{{ $reptile->name }}</h2>
            <div class="detail-price">Rp.{{ number_format($reptile->price, 0, ',', '.') }}</div>
            
            <h3 style="margin-bottom: 10px; color: #111;">Deskripsi Singkat</h3>
            <p class="detail-desc-text">
                {{ $reptile->description ?? 'Reptil premium sehat, aktif, makan lancar, dan sudah melalui proses karantina ketat oleh tim ahli ahli kami.' }}
            </p>

            <div style="max-width: 350px;">
                <button class="btn-action btn-quick-order" onclick="orderSpesifik('{{ $reptile->name }}', '{{ $reptile->price }}')">
                    Beli Lewat WhatsApp Owner &rarr;
                </button>
            </div>
        </div>
    </div>

    <script>
        const OWNER_PHONE = "6281234567890";
        function orderSpesifik(name, price) {
            const formatted = Number(price).toLocaleString('id-ID');
            const msg = `Halo Kak, saya sudah membaca detail unitnya di website. Saya berminat memesan:\n\n*Unit:* ${name}\n*Harga:* Rp.${formatted}\n\nBagaimana metode pembayarannya?`;
            window.open(`https://wa.me/${OWNER_PHONE}?text=${encodeURIComponent(msg)}`, '_blank');
        }
    </script>
</body>
</html>