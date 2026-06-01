@extends('layouts.public')

@section('title', 'Login - AnarcyxReptile')

@push('styles')
    <style>
        .auth-page-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 60px 20px;
            min-height: 70vh;
        }

        .auth-card {
            background: #FFFFFF;
            border: 1px solid #E5E5E5;
            border-radius: 20px;
            padding: 44px 40px;
            width: 100%;
            max-width: 460px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.02);
        }

        .auth-icon-circle {
            width: 70px;
            height: 70px;
            background-color: rgba(74, 92, 58, 0.15);
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto 22px auto;
        }

        .auth-icon-circle svg {
            width: 34px;
            height: 34px;
            color: #4A5C3A;
            stroke-width: 2;
        }

        .auth-title {
            font-size: 1.7rem;
            font-weight: 800;
            color: #111111;
            text-align: center;
            margin-bottom: 6px;
            letter-spacing: -0.3px;
        }

        .auth-subtitle {
            font-size: 0.92rem;
            color: #888888;
            text-align: center;
            margin-bottom: 32px;
        }

        .form-group-block {
            margin-bottom: 18px;
        }

        .form-group-block label {
            display: block;
            font-size: 0.85rem;
            font-weight: 700;
            color: #111111;
            margin-bottom: 8px;
        }

        .input-icon-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon-wrapper svg {
            position: absolute;
            left: 16px;
            width: 18px;
            height: 18px;
            color: #A3A3A3;
        }

        .input-icon-wrapper input {
            width: 100%;
            padding: 14px 14px 14px 46px;
            border: 1px solid #E5E5E5;
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 600;
            color: #111;
            background: #FFFFFF;
            outline: none;
            transition: border-color 0.2s ease;
        }

        .input-icon-wrapper input:focus {
            border-color: #283221;
        }

        .input-icon-wrapper input::placeholder {
            color: #B5B5B5;
            font-weight: 500;
        }

        .field-error {
            color: #dc2626;
            font-size: 0.82rem;
            margin-top: 6px;
            font-weight: 600;
        }

        .btn-auth-submit {
            width: 100%;
            background-color: #283221;
            color: white;
            border: none;
            padding: 15px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            margin-top: 12px;
            transition: background 0.2s ease;
        }

        .btn-auth-submit:hover {
            background-color: #3b4930;
        }

        .auth-footer-text {
            text-align: center;
            font-size: 0.9rem;
            color: #666;
            margin-top: 22px;
        }

        .auth-footer-text a {
            color: #283221;
            font-weight: 700;
            text-decoration: none;
        }

        .auth-footer-text a:hover {
            text-decoration: underline;
        }
    </style>
@endpush

@section('content')
    <div class="auth-page-wrapper">
        <div class="auth-card">
            <div class="auth-icon-circle">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>

            <h2 class="auth-title">Welcome Back</h2>
            <p class="auth-subtitle">Sign in to your account to continue shopping</p>

            @if ($errors->any())
                <div class="field-error" style="text-align:center; margin-bottom:14px;">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('auth.handleLogin') }}" method="POST">
                @csrf

                <div class="form-group-block">
                    <label>Email Address</label>
                    <div class="input-icon-wrapper">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l9 6 9-6M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter your username" required>
                    </div>
                    @error('email')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group-block">
                    <label>Password</label>
                    <div class="input-icon-wrapper">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        <input type="password" name="password" placeholder="Enter your password" required>
                    </div>
                    @error('password')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn-auth-submit">Sign In</button>
            </form>

            <p class="auth-footer-text">
                Don't have an account? <a href="{{ route('auth.signup') }}">Sign up</a>
            </p>
        </div>
    </div>
@endsection
