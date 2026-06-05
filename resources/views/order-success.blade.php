@extends('layouts.public')

@section('title', 'Pesanan Berhasil - AnarcyxReptile')

@push('styles')
<style>
    .success-wrapper {
        display: flex; flex-direction: column;
        justify-content: center; align-items: center;
        text-align: center; padding: 100px 20px;
        min-height: 60vh;
    }
    .success-icon {
        width: 100px; height: 100px; border-radius: 50%;
        background: #DEF7EC; display: flex;
        justify-content: center; align-items: center;
        margin-bottom: 28px;
    }
    .success-icon svg { width: 50px; height: 50px; color: #03543F; }
    .success-headline {
        font-size: 2rem; font-weight: 800; color: #111;
        margin-bottom: 10px;
    }
    .success-desc {
        font-size: 1rem; color: #6c757d;
        max-width: 450px; line-height: 1.6;
        margin-bottom: 35px;
    }
    .btn-success-back {
        background: #283221; color: #fff;
        padding: 16px 50px; border-radius: 6px;
        text-decoration: none; font-weight: 700;
        font-size: 0.95rem; transition: all 0.3s ease;
    }
    .btn-success-back:hover {
        background: #3b4930; transform: translateY(-2px);
    }
</style>
@endpush

@section('content')
    <div class="success-wrapper">
        <div class="success-icon">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        <h1 class="success-headline">Pesanan Berhasil Dikirim!</h1>
        <p class="success-desc">
            Terima kasih! Pesanan kamu sudah masuk ke WhatsApp admin AnarcyxReptile.
            Admin akan segera menghubungi kamu untuk konfirmasi dan pembayaran.
        </p>
        <a href="{{ route('shop') }}" class="btn-success-back">Lanjut Belanja &rarr;</a>
    </div>
@endsection