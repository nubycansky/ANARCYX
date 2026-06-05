@extends('layouts.public')

@section('title', 'Create Account - AnarcyxReptile')

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
            max-width: 520px;
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
            margin-bottom: 16px;
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
            z-index: 1;
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

        .phone-input-wrapper {
            display: flex;
            align-items: stretch;
            border: 1px solid #E5E5E5;
            border-radius: 10px;
            overflow: hidden;
            background: #FFFFFF;
            transition: border-color 0.2s ease;
        }

        .phone-input-wrapper:focus-within {
            border-color: #283221;
        }

        .phone-prefix {
            display: flex;
            align-items: center;
            padding: 0 14px;
            background-color: #F4F4F4;
            color: #111;
            font-weight: 700;
            font-size: 0.95rem;
            border-right: 1px solid #E5E5E5;
        }

        .phone-input-wrapper input {
            flex: 1;
            padding: 14px;
            border: none;
            font-size: 0.95rem;
            font-weight: 600;
            color: #111;
            outline: none;
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
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
            </div>

            <h2 class="auth-title">Create Account</h2>
            <p class="auth-subtitle">Join us to start your reptile journey</p>

            @if ($errors->any())
                <div class="field-error" style="text-align:center; margin-bottom:14px;">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('auth.handleSignup') }}" method="POST">
                @csrf

                <div class="form-group-block">
                    <label>Full Name</label>
                    <div class="input-icon-wrapper">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Enter your full name" required>
                    </div>
                    @error('name')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group-block">
                    <label>Email Address</label>
                    <div class="input-icon-wrapper">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l9 6 9-6M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter your email" required>
                    </div>
                    @error('email')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group-block">
                    <label>Phone Number</label>
                    <div class="phone-input-wrapper">
                        <span class="phone-prefix">+62</span>
                        <input type="tel" name="phone_number" value="{{ old('phone_number') }}" placeholder="812xxxxxxxx" required>
                    </div>
                    @error('phone_number')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group-block">
                    <label>Password</label>
                    <div class="input-icon-wrapper">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        <input type="password" name="password" placeholder="Create a password" required>
                    </div>
                    @error('password')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group-block">
                    <label>Confirm Password</label>
                    <div class="input-icon-wrapper">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        <input type="password" name="password_confirmation" placeholder="Confirm your password" required>
                    </div>
                </div>

                <button type="submit" class="btn-auth-submit">Create Account</button>
            </form>

            <p class="auth-footer-text">
                Already have an account? <a href="{{ route('login') }}">Sign In</a>
            </p>
        </div>
    </div>
@endsection
