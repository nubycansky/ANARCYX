@extends('layouts.public')

@section('title', 'Profil Saya - AnarcyxReptile')

@push('styles')
<style>
.profile-wrapper {
    max-width: 1100px;
    margin: 40px auto 80px;
    padding: 0 5%;
}

.profile-header {
    display: flex;
    align-items: center;
    gap: 25px;
    margin-bottom: 40px;
    padding: 30px 35px;
    background: linear-gradient(135deg, #1e2518, #283221);
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
}

.profile-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: var(--text-accent);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    font-weight: 800;
    color: #1e2518;
    flex-shrink: 0;
}

.profile-header-info h1 {
    font-size: 1.8rem;
    font-weight: 800;
    color: #fff;
    margin-bottom: 4px;
}

.profile-header-info p {
    color: var(--text-accent);
    font-size: 0.95rem;
}

.profile-tabs {
    display: flex;
    gap: 0;
    background: #fff;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.03);
    margin-bottom: 35px;
    border: 1px solid #e5e5e5;
}

.profile-tab {
    flex: 1;
    padding: 18px 20px;
    text-align: center;
    font-weight: 700;
    font-size: 0.95rem;
    color: #666;
    cursor: pointer;
    transition: all 0.3s ease;
    border-bottom: 3px solid transparent;
    background: none;
    border-top: none;
    border-left: none;
    border-right: none;
    font-family: inherit;
}

.profile-tab:hover {
    color: var(--title-color);
    background: #f9faf7;
}

.profile-tab.active {
    color: var(--title-color);
    border-bottom-color: var(--title-color);
    background: #f9faf7;
}

.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

/* Account Settings Tab */
.profile-card {
    background: #fff;
    border-radius: 20px;
    padding: 35px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.02);
    border: 1px solid #e5e5e5;
    margin-bottom: 30px;
}

.profile-card h2 {
    font-size: 1.3rem;
    font-weight: 800;
    color: #111;
    margin-bottom: 25px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    font-size: 0.85rem;
    font-weight: 700;
    color: #444;
    margin-bottom: 6px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.form-group input {
    width: 100%;
    padding: 14px 16px;
    border: 2px solid #e5e5e5;
    border-radius: 10px;
    font-size: 0.95rem;
    font-weight: 600;
    color: #111;
    transition: border-color 0.3s ease;
    outline: none;
    font-family: inherit;
}

.form-group input:focus {
    border-color: var(--title-color);
}

.form-group input:disabled {
    background: #f5f5f5;
    color: #888;
}

.btn-save {
    background: var(--btn-add-cart);
    color: #fff;
    border: none;
    padding: 14px 40px;
    border-radius: 10px;
    font-weight: 700;
    font-size: 0.95rem;
    cursor: pointer;
    transition: all 0.3s ease;
    font-family: inherit;
}

.btn-save:hover {
    background: #38462c;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(74, 92, 58, 0.3);
}

.password-section {
    border-top: 1px solid #e5e5e5;
    padding-top: 30px;
    margin-top: 30px;
}

.password-section h3 {
    font-size: 1.1rem;
    font-weight: 700;
    color: #111;
    margin-bottom: 20px;
}

.btn-password {
    background: transparent;
    color: var(--btn-add-cart);
    border: 2px solid var(--btn-add-cart);
    padding: 12px 30px;
    border-radius: 10px;
    font-weight: 700;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.3s ease;
    font-family: inherit;
}

.btn-password:hover {
    background: var(--btn-add-cart);
    color: #fff;
}

.btn-password.cancel {
    border-color: #999;
    color: #999;
}

.btn-password.cancel:hover {
    background: #999;
    color: #fff;
}

.password-form {
    display: none;
    margin-top: 20px;
    padding: 25px;
    background: #f9faf7;
    border-radius: 16px;
}

.password-form.open {
    display: block;
}

/* Orders Tab */
.order-card {
    background: #fff;
    border: 1px solid #e5e5e5;
    border-radius: 16px;
    padding: 22px 25px;
    margin-bottom: 18px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: box-shadow 0.3s ease;
}

.order-card:hover {
    box-shadow: 0 5px 20px rgba(0,0,0,0.04);
}

.order-info h4 {
    font-size: 1.05rem;
    font-weight: 800;
    color: #111;
    margin-bottom: 4px;
}

.order-info span {
    font-size: 0.85rem;
    color: #888;
}

.order-meta {
    text-align: right;
}

.order-meta .order-price {
    font-size: 1.1rem;
    font-weight: 800;
    color: #111;
    display: block;
    margin-bottom: 6px;
}

.order-status-badge {
    display: inline-block;
    padding: 5px 14px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.order-status-badge.pending { background: #fef3c7; color: #92400e; }
.order-status-badge.confirmed { background: #dbeafe; color: #1e40af; }
.order-status-badge.delivered { background: #d1fae5; color: #065f46; }
.order-status-badge.canceled { background: #fee2e2; color: #991b1b; }

.order-items-preview {
    font-size: 0.8rem;
    color: #999;
    margin-top: 4px;
}

/* Reviews Tab */
.review-card {
    background: #fff;
    border: 1px solid #e5e5e5;
    border-radius: 16px;
    padding: 22px 25px;
    margin-bottom: 18px;
}

.review-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.review-product-name {
    font-size: 1rem;
    font-weight: 700;
    color: var(--title-color);
}

.review-stars {
    display: flex;
    gap: 3px;
}

.review-stars svg {
    width: 18px;
    height: 18px;
    stroke: #f59e0b;
    stroke-width: 2;
    fill: none;
}

.review-stars svg.filled {
    fill: #f59e0b;
}

.review-date {
    font-size: 0.8rem;
    color: #aaa;
    margin-bottom: 10px;
}

.review-comment {
    font-size: 0.92rem;
    line-height: 1.6;
    color: #555;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #999;
    font-style: italic;
}

.flash-message {
    background: #d1fae5;
    color: #065f46;
    padding: 16px 20px;
    border-radius: 12px;
    font-weight: 600;
    margin-bottom: 25px;
    border: 1px solid #a7f3d0;
}

.error-message {
    background: #fee2e2;
    color: #991b1b;
    padding: 12px 16px;
    border-radius: 10px;
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 15px;
    border: 1px solid #fecaca;
}

@media (max-width: 768px) {
    .profile-header { flex-direction: column; text-align: center; padding: 25px 20px; }
    .profile-tabs { flex-direction: column; }
    .order-card { flex-direction: column; text-align: center; gap: 12px; }
    .order-meta { text-align: center; }
    .profile-card { padding: 25px 20px; }
}
</style>
@endpush

@section('content')
<div class="profile-wrapper">
    @if (session('flash_success'))
        <div class="flash-message">{{ session('flash_success') }}</div>
    @endif

    <div class="profile-header">
        <div class="profile-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
        <div class="profile-header-info">
            <h1>{{ $user->name }}</h1>
            <p>{{ $user->email }} &middot; Bergabung sejak {{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->format('d M Y') : 'baru saja' }}</p>
        </div>
    </div>

    <div class="profile-tabs">
        <button class="profile-tab active" onclick="switchTab('account')">Pengaturan Akun</button>
        <button class="profile-tab" onclick="switchTab('orders')">Riwayat Transaksi ({{ $orders->count() }})</button>
        <button class="profile-tab" onclick="switchTab('reviews')">Ulasan Saya ({{ $reviews->count() }})</button>
    </div>

    {{-- TAB 1: Account Settings --}}
    <div id="tab-account" class="tab-content active">
        <div class="profile-card">
            <h2>Informasi Profil</h2>
            <form method="POST" action="{{ route('user.profile.update') }}">
                @csrf
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                    @error('name')<div class="error-message">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                    @error('email')<div class="error-message">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label for="phone_number">Phone Number</label>
                    <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" required>
                    @error('phone_number')<div class="error-message">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label for="address">Alamat Lengkap</label>
                    <textarea id="address" name="address" rows="3" style="width:100%;padding:14px 16px;border:2px solid #e5e5e5;border-radius:10px;font-size:0.95rem;font-weight:600;color:#111;font-family:inherit;outline:none;resize:vertical;transition:border-color 0.3s ease;" onfocus="this.style.borderColor='var(--title-color)'" onblur="this.style.borderColor='#e5e5e5'">{{ old('address', $user->address) }}</textarea>
                    @error('address')<div class="error-message">{{ $message }}</div>@enderror
                </div>
                <button type="submit" class="btn-save">Simpan Perubahan</button>
            </form>
        </div>

        <div class="profile-card">
            <div class="password-section" id="password-section">
                <h3>Ubah Password</h3>
                <button type="button" class="btn-password" onclick="togglePasswordForm()">Ubah Password</button>

                <div id="password-form" class="password-form">
                    <form method="POST" action="{{ route('user.profile.password') }}">
                        @csrf
                        <div class="form-group">
                            <label for="current_password">Password Lama</label>
                            <div style="position:relative;">
                                <input type="password" id="current_password" name="current_password" placeholder="Masukkan password saat ini" value="{{ old('current_password') }}" required style="padding-right:45px;">
                                <span onclick="togglePasswordVisibility('current_password','eye_current')" style="position:absolute;right:14px;top:50%;transform:translateY(-50%);cursor:pointer;display:flex;align-items:center;" id="eye_current">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#888" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </span>
                            </div>
                            @error('current_password')<div class="error-message">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="new_password">Password Baru</label>
                            <div style="position:relative;">
                                <input type="password" id="new_password" name="new_password" placeholder="Minimal 6 karakter" value="{{ old('new_password') }}" required style="padding-right:45px;">
                                <span onclick="togglePasswordVisibility('new_password','eye_new')" style="position:absolute;right:14px;top:50%;transform:translateY(-50%);cursor:pointer;display:flex;align-items:center;" id="eye_new">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#888" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </span>
                            </div>
                            @error('new_password')<div class="error-message">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="new_password_confirmation">Konfirmasi Password Baru</label>
                            <div style="position:relative;">
                                <input type="password" id="new_password_confirmation" name="new_password_confirmation" placeholder="Ketik ulang password baru" value="{{ old('new_password_confirmation') }}" required style="padding-right:45px;">
                                <span onclick="togglePasswordVisibility('new_password_confirmation','eye_confirm')" style="position:absolute;right:14px;top:50%;transform:translateY(-50%);cursor:pointer;display:flex;align-items:center;" id="eye_confirm">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#888" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </span>
                            </div>
                            @error('new_password_confirmation')<div class="error-message">{{ $message }}</div>@enderror
                        </div>
                        <div style="display:flex;gap:12px;">
                            <button type="submit" class="btn-save">Simpan Password</button>
                            <button type="button" class="btn-password cancel" onclick="togglePasswordForm()">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- TAB 2: Orders --}}
    <div id="tab-orders" class="tab-content">
        @forelse($orders as $order)
            <div class="order-card">
                <div class="order-info">
                    <h4>{{ $order->order_id_string }}</h4>
                    <span>{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y, H:i') }}</span>
                    @if(isset($order->items) && count($order->items) > 0)
                        <div class="order-items-preview">
                            {{ collect($order->items)->pluck('name')->take(3)->implode(', ') }}{{ count($order->items) > 3 ? '...' : '' }}
                        </div>
                    @endif
                </div>
                <div class="order-meta">
                    <span class="order-price">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                    <span class="order-status-badge {{ $order->status }}">{{ $order->status }}</span>
                </div>
            </div>
        @empty
            <div class="empty-state">Belum ada transaksi. Ayo belanja sekarang!</div>
        @endforelse
    </div>

    {{-- TAB 3: Reviews --}}
    <div id="tab-reviews" class="tab-content">
        @forelse($reviews as $review)
            @php
                $product = \App\Models\Reptile::find($review->product_id);
            @endphp
            <div class="review-card">
                <div class="review-card-header">
                    <span class="review-product-name">{{ $product->name ?? 'Produk tidak ditemukan' }}</span>
                    <div class="review-stars">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="{{ $i <= $review->rating ? 'filled' : '' }}" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                            </svg>
                        @endfor
                    </div>
                </div>
                <div class="review-date">{{ \Carbon\Carbon::parse($review->created_at)->format('d M Y') }}</div>
                <div class="review-comment">{{ $review->comment }}</div>
            </div>
        @empty
            <div class="empty-state">Belum ada ulasan. Yuk ulas produk yang sudah dibeli!</div>
        @endforelse
    </div>
</div>

<script>
function switchTab(tab) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
    document.querySelectorAll('.profile-tab').forEach(el => el.classList.remove('active'));
    document.getElementById('tab-' + tab).classList.add('active');
    document.querySelector(`.profile-tab[onclick="switchTab('${tab}')"]`).classList.add('active');
}

function togglePasswordForm() {
    document.getElementById('password-form').classList.toggle('open');
}

document.addEventListener("DOMContentLoaded", function() {
    @if ($errors->has('current_password') || $errors->has('new_password') || $errors->has('new_password_confirmation'))
        const pwSection = document.getElementById('password-section');
        if (pwSection) {
            const pwForm = document.getElementById('password-form');
            if (pwForm) pwForm.classList.add('open');
            setTimeout(function() {
                pwSection.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }, 100);
        }
    @endif
});

function togglePasswordVisibility(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    if (input.type === 'password') {
        input.type = 'text';
        icon.innerHTML = `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#6B8E4E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>`;
    } else {
        input.type = 'password';
        icon.innerHTML = `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#888" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>`;
    }
}
</script>
@endsection
