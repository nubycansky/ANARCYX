<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $order->order_id_string ?? '#' . substr($order->_id, 0, 8) }}</title>
    <style>
        @page { margin: 0; padding: 0; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'DejaVu Sans', sans-serif; }
        body { background: #f4f4f4; padding: 40px; }
        .invoice-wrap { max-width: 800px; margin: 0 auto; background: #fff; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 40px rgba(0,0,0,0.08); }
        .inv-header { background: #1a2a1a; padding: 40px; color: #fff; }
        .inv-header-top { display: flex; justify-content: space-between; align-items: center; }
        .inv-brand { display: flex; align-items: center; gap: 14px; }
        .inv-brand-logo { width: 50px; height: 50px; background: #3a5a3a; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.2rem; color: #81c784; }
        .inv-brand-name { font-size: 1.4rem; font-weight: 800; letter-spacing: 1px; }
        .inv-brand-sub { font-size: 0.75rem; color: #81c784; font-weight: 600; }
        .inv-title-box { text-align: right; }
        .inv-title { font-size: 1.8rem; font-weight: 800; letter-spacing: 2px; }
        .inv-subtitle { font-size: 0.75rem; color: #a0c0a0; }
        .inv-divider { height: 1px; background: rgba(255,255,255,0.1); margin: 20px 0; }
        .inv-meta { display: flex; justify-content: space-between; font-size: 0.85rem; }
        .inv-meta-block { display: flex; flex-direction: column; gap: 4px; }
        .inv-meta-label { color: #81c784; font-weight: 600; font-size: 0.7rem; text-transform: uppercase; }
        .inv-meta-value { color: #fff; font-weight: 700; }
        .inv-body { padding: 40px; }
        .inv-customer { margin-bottom: 25px; }
        .inv-customer h3 { font-size: 1rem; font-weight: 800; color: #1a2a1a; margin-bottom: 6px; }
        .inv-customer p { font-size: 0.85rem; color: #555; line-height: 1.5; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0 30px; }
        th { background: #f0f4f0; color: #1a2a1a; padding: 12px 14px; font-size: 0.78rem; text-transform: uppercase; font-weight: 800; text-align: left; }
        td { padding: 14px; border-bottom: 1px solid #eee; font-size: 0.85rem; color: #333; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .fw-bold { font-weight: 700; }
        .total-row td { border-top: 2px solid #1a2a1a; border-bottom: none; font-weight: 800; font-size: 1rem; color: #1a2a1a; padding-top: 16px; }
        .inv-footer { padding: 30px 40px; background: #f9fbf9; text-align: center; }
        .qris-section { margin-bottom: 20px; }
        .qris-section h4 { font-size: 0.9rem; font-weight: 800; color: #1a2a1a; margin-bottom: 12px; }
        .qris-section img { width: 180px; height: 180px; border: 2px solid #e0e8e0; border-radius: 12px; padding: 8px; }
        .qris-note { font-size: 0.75rem; color: #888; margin-top: 6px; }
        .inv-footer-text { font-size: 0.75rem; color: #aaa; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="invoice-wrap">
        <div class="inv-header">
            <div class="inv-header-top">
                <div class="inv-brand">
                    <div class="inv-brand-logo">AR</div>
                    <div>
                        <div class="inv-brand-name">ANARCYXREPTILE</div>
                        <div class="inv-brand-sub">Premium Exotic Shop</div>
                    </div>
                </div>
                <div class="inv-title-box">
                    <div class="inv-title">INVOICE</div>
                    <div class="inv-subtitle">{{ $order->order_id_string ?? '#' . substr($order->_id, 0, 8) }}</div>
                </div>
            </div>
            <div class="inv-divider"></div>
            <div class="inv-meta">
                <div class="inv-meta-block">
                    <span class="inv-meta-label">Tanggal Invoice</span>
                    <span class="inv-meta-value">{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y, H:i') }}</span>
                </div>
                <div class="inv-meta-block">
                    <span class="inv-meta-label">Status</span>
                    <span class="inv-meta-value">{{ strtoupper($order->status ?? 'PENDING') }}</span>
                </div>
                <div class="inv-meta-block">
                    <span class="inv-meta-label">No. Pesanan</span>
                    <span class="inv-meta-value">{{ $order->order_id_string ?? '#' . substr($order->_id, 0, 8) }}</span>
                </div>
            </div>
        </div>
        <div class="inv-body">
            <div class="inv-customer">
                <h3>Detail Pelanggan</h3>
                <p>
                    <strong>{{ $order->customer_name ?? 'Guest User' }}</strong><br>
                    @if(!empty($order->customer_phone)) WA: {{ $order->customer_phone }}<br> @endif
                    @if(!empty($order->customer_address)) {{ $order->customer_address }} @endif
                </p>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th class="text-center">Qty</th>
                        <th class="text-right">Harga</th>
                        <th class="text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @php $grandTotal = 0; @endphp
                    @forelse($order->items ?? [] as $item)
                        @php
                            $itemName = $item['product_name'] ?? $item['name'] ?? 'Produk';
                            $itemQty = (int)($item['qty'] ?? 1);
                            $itemPrice = (float)($item['price'] ?? 0);
                            $subtotal = $itemQty * $itemPrice;
                            $grandTotal += $subtotal;
                        @endphp
                        <tr>
                            <td>{{ $itemName }}</td>
                            <td class="text-center">{{ $itemQty }}</td>
                            <td class="text-right">Rp {{ number_format($itemPrice, 0, ',', '.') }}</td>
                            <td class="text-right fw-bold">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" style="text-align:center;color:#888;">Tidak ada item</td></tr>
                    @endforelse
                    <tr class="total-row">
                        <td colspan="3" class="text-right">Total Belanja</td>
                        <td class="text-right">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="inv-footer">
            <div class="qris-section">
                <h4>Pembayaran via QRIS</h4>
                <img src="{{ public_path('images/qris.svg') }}" alt="QRIS Pembayaran">
                <div class="qris-note">Scan QR di atas untuk melakukan pembayaran</div>
            </div>
            <div class="inv-footer-text">
                Terima kasih telah berbelanja di AnarcyxReptile!<br>
                Jika ada pertanyaan, hubungi WA: +62 895-6133-69443
            </div>
        </div>
    </div>
</body>
</html>