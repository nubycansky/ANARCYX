<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $order->order_number ?? $order->order_id_string ?? '#' . substr($order->_id, 0, 8) }}</title>
    <style>
        @page { margin: 0; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body, table, td, th, div, p, span, strong { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif !important; }
        body { background: #f5f5f5; padding: 30px; font-size: 12px; color: #222; }
        .invoice-wrap { max-width: 750px; margin: 0 auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 8px 30px rgba(0,0,0,0.06); }
        .inv-header { background: #1a2a1a; padding: 32px 36px; color: #fff; }
        .inv-header-top { display: flex; justify-content: space-between; align-items: center; }
        .inv-brand { display: flex; align-items: center; gap: 12px; }
        .inv-brand-icon { width: 44px; height: 44px; background: #2d4a2d; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.1rem; color: #81c784; }
        .inv-brand-name { font-size: 1.3rem; font-weight: 800; letter-spacing: 1px; }
        .inv-brand-sub { font-size: 0.65rem; color: #81c784; font-weight: 600; letter-spacing: 0.3px; }
        .inv-title-box { text-align: right; }
        .inv-title { font-size: 1.6rem; font-weight: 800; letter-spacing: 2px; }
        .inv-subtitle { font-size: 0.65rem; color: #a0c0a0; letter-spacing: 0.3px; }
        .inv-divider { height: 1px; background: rgba(255,255,255,0.1); margin: 18px 0; }
        .inv-meta { display: flex; justify-content: space-between; font-size: 0.8rem; }
        .inv-meta-block { display: flex; flex-direction: column; gap: 3px; }
        .inv-meta-label { color: #81c784; font-weight: 600; font-size: 0.6rem; text-transform: uppercase; letter-spacing: 0.5px; }
        .inv-meta-value { color: #fff; font-weight: 700; }
        .inv-body { padding: 32px 36px; }
        .inv-customer { margin-bottom: 22px; }
        .inv-customer h3 { font-size: 0.9rem; font-weight: 800; color: #1a2a1a; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px; }
        .inv-customer p { font-size: 0.82rem; color: #555; line-height: 1.6; }
        table { width: 100%; border-collapse: collapse; margin: 18px 0 0; }
        th { background: #f0f4f0; color: #1a2a1a; padding: 10px 12px; font-size: 0.7rem; text-transform: uppercase; font-weight: 800; text-align: left; letter-spacing: 0.3px; }
        td { padding: 12px; border-bottom: 1px solid #eee; font-size: 0.82rem; color: #333; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .fw-bold { font-weight: 700; }
        .total-row td { border-top: 2px solid #1a2a1a; border-bottom: none; font-weight: 800; font-size: 1rem; color: #1a2a1a; padding-top: 14px; }
    </style>
</head>
<body>
    <div class="invoice-wrap">
        <div class="inv-header">
            <div class="inv-header-top">
                <div class="inv-brand">
                    <img src="{{ public_path('images/logo/logo.jpeg') }}" alt="ANARCYX Logo" style="width: 50px; height: 50px; border-radius: 50%; object-fit: contain; background: #283221; padding: 2px; vertical-align: middle;">
                    <div>
                        <div class="inv-brand-name">ANARCYXREPTILE</div>
                        <div class="inv-brand-sub">Premium Exotic Shop</div>
                    </div>
                </div>
                <div class="inv-title-box">
                    <div class="inv-title">INVOICE</div>
                    <div class="inv-subtitle">{{ $order->order_number ?? $order->order_id_string ?? '#' . substr($order->_id, 0, 8) }}</div>
                </div>
            </div>
            <div class="inv-divider"></div>
            <div style="margin-top: 25px; padding-top: 15px; border-top: 1px solid rgba(255,255,255,0.1); font-family: Helvetica, Arial, sans-serif;">
                <div style="margin-bottom: 8px; font-size: 0.85rem;">
                    <span style="display: inline-block; width: 140px; color: #A3C293; font-weight: 500;">Tanggal Transaksi</span>
                    <span style="color: #A3C293; margin-right: 10px;">:</span>
                    <span style="color: #FFFFFF; font-weight: 700; letter-spacing: 0.3px;">{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y, H:i') }}</span>
                </div>

                <div style="margin-bottom: 8px; font-size: 0.85rem;">
                    <span style="display: inline-block; width: 140px; color: #A3C293; font-weight: 500;">No. Invoice</span>
                    <span style="color: #A3C293; margin-right: 10px;">:</span>
                    <span style="color: #FFFFFF; font-weight: 700; letter-spacing: 0.3px;">{{ $order->order_number ?? $order->order_id_string ?? '#' . substr($order->_id, 0, 8) }}</span>
                </div>

                <div style="font-size: 0.85rem;">
                    <span style="display: inline-block; width: 140px; color: #A3C293; font-weight: 500;">Status</span>
                    <span style="color: #A3C293; margin-right: 10px;">:</span>
                    <span style="background: rgba(255,255,255,0.15); color: #FFFFFF; font-weight: 700; padding: 2px 8px; border-radius: 4px; font-size: 0.75rem; letter-spacing: 0.5px; display: inline-block;">{{ strtoupper($order->status ?? 'PENDING') }}</span>
                </div>
            </div>
        </div>
        <div class="inv-body">
            <div class="inv-customer">
                <h3>Informasi Pembeli</h3>
                <p>
                    <strong>{{ $order->customer_name ?? 'Guest User' }}</strong><br>
                    @if(!empty($order->customer_phone)) WA: {{ $order->customer_phone }}<br> @endif
                    @if(!empty($order->shipping_address)) {{ $order->shipping_address }} @elseif(!empty($order->customer_address)) {{ $order->customer_address }} @endif
                </p>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Nama Item</th>
                        <th class="text-center">Qty</th>
                        <th class="text-right">Harga Satuan</th>
                        <th class="text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $itemsSubtotal = 0;
                    @endphp
                    @forelse($order->items ?? [] as $item)
                        @php
                            $itemName = $item['product_name'] ?? $item['name'] ?? 'Produk';
                            $itemQty = (int)($item['qty'] ?? 1);
                            $itemPrice = (float)($item['price'] ?? 0);
                            $lineTotal = $itemQty * $itemPrice;
                            $itemsSubtotal += $lineTotal;
                        @endphp
                        <tr>
                            <td>{{ $itemName }}</td>
                            <td class="text-center">{{ $itemQty }}</td>
                            <td class="text-right">Rp {{ number_format($itemPrice, 0, ',', '.') }}</td>
                            <td class="text-right fw-bold">Rp {{ number_format($lineTotal, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" style="text-align:center;color:#888;">Tidak ada item</td></tr>
                    @endforelse
                    @php
                        $totalAmount = (float)($order->total_amount ?? $order->total_price ?? $itemsSubtotal);
                        $shippingCost = (float)($order->shipping_cost ?? $order->ongkir ?? max(0, $totalAmount - $itemsSubtotal));
                    @endphp
                    <tr>
                        <td colspan="3" style="text-align:right;padding:8px;color:#888;">Subtotal Produk:</td>
                        <td style="padding:8px;text-align:right;color:#333;font-weight:700;">Rp {{ number_format($itemsSubtotal, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" style="text-align:right;padding:8px;color:#888;">Biaya Pengantaran:</td>
                        <td style="padding:8px;text-align:right;color:#333;font-weight:700;">Rp {{ number_format($shippingCost, 0, ',', '.') }}</td>
                    </tr>
                    <tr style="background:#1A2315;border-top:2px solid #6B8E4E;">
                        <td colspan="3" style="text-align:right;padding:10px;font-weight:bold;color:#6B8E4E;">Total Pembayaran:</td>
                        <td style="padding:10px;text-align:right;font-weight:bold;color:#6B8E4E;font-size:1.1rem;">Rp {{ number_format($totalAmount, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>

            <div style="margin-top: 25px; background: #F9FAF7; border: 1px solid #E5E5E5; border-radius: 12px; padding: 20px; text-align: center; font-family: sans-serif;">

                <h4 style="margin: 0 0 10px 0; font-size: 0.95rem; font-weight: 800; color: #4A5C3A; text-transform: uppercase; letter-spacing: 0.5px; display: flex; align-items: center; justify-content: center; gap: 8px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#4A5C3A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle;">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                    <span>Metode Pembayaran Transfer Bank</span>
                </h4>

                <p style="margin: 0 0 15px 0; font-size: 0.85rem; color: #666; font-weight: 600;">Silakan lakukan transfer pembayaran penuh ke rekening resmi berikut:</p>

                <div style="background: white; border: 1px solid #EAEAEA; border-radius: 8px; padding: 15px; display: inline-block; min-width: 280px; text-align: left; margin: 0 auto;">

                    <div style="font-size: 0.85rem; color: #777; font-weight: 600;">Bank:</div>
                    <div style="font-size: 1rem; font-weight: 800; color: #4A5C3A; margin-bottom: 8px;">BCA (Bank Central Asia)</div>

                    <div style="font-size: 0.85rem; color: #777; font-weight: 600;">Nomor Rekening:</div>
                    <div style="font-size: 1.25rem; font-weight: 800; color: #283221; letter-spacing: 0.5px; margin-bottom: 8px;">7370623729</div>

                    <div style="font-size: 0.85rem; color: #777; font-weight: 600;">Atas Nama:</div>
                    <div style="font-size: 0.95rem; font-weight: 800; color: #111; text-transform: uppercase;">SULTAN ISKANDAR DZULKARNAIN</div>
                </div>

                <div style="margin-top: 15px; font-size: 0.8rem; color: #dc2626; font-style: italic; font-weight: 700;">
                    *Harap kirimkan bukti transfer ke WhatsApp Admin agar pesanan dapat segera diproses.
                </div>
            </div>

            <div style="margin-top: 25px; padding: 15px 0 5px; text-align: center; border-top: 1px solid #E5E5E5;">
                <div style="font-size: 0.85rem; color: #888; font-weight: 600;">
                    Terima kasih telah berbelanja di AnarcyxReptile!<br>
                    Jika ada pertanyaan, hubungi WA: +62 895-6133-69443
                </div>
            </div>
        </div>
    </div>
</body>
</html>
