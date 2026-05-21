<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - AnarcyxReptile</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        .login-page-container { flex: 1; display: flex; justify-content: center; align-items: center; padding: 60px 20px; min-height: 80vh; }
        .login-card { background: #FFFFFF; border: 1px solid #E5E5E5; border-radius: 20px; padding: 40px; width: 100%; max-width: 440px; box-shadow: 0 10px 30px rgba(0,0,0,0.02); text-align: center; }
        .admin-avatar-circle { width: 70px; height: 70px; background-color: rgba(74, 92, 58, 0.15); border-radius: 50%; display: flex; justify-content: center; align-items: center; margin: 0 auto 20px auto; }
        .admin-avatar-circle svg { width: 35px; height: 35px; color: #4A5C3A; }
        .login-title { font-size: 1.6rem; font-weight: 800; color: #111111; margin-bottom: 6px; }
        .login-subtitle { font-size: 0.9rem; color: #888888; margin-bottom: 30px; }
        .form-group-block { text-align: left; margin-bottom: 20px; }
        .form-group-block label { display: block; font-size: 0.85rem; font-weight: 700; color: #111111; margin-bottom: 8px; }
        .input-icon-wrapper { position: relative; display: flex; align-items: center; }
        .input-icon-wrapper svg { position: absolute; left: 16px; width: 18px; height: 18px; color: #A3A3A3; }
        .input-icon-wrapper input { width: 100%; padding: 14px 14px 14px 46px; border: 1px solid #E5E5E5; border-radius: 10px; font-size: 0.95rem; font-weight: 600; outline: none; }
        .btn-admin-signin { width: 100%; background-color: #283221; color: white; border: none; padding: 15px; border-radius: 10px; font-weight: 700; font-size: 1rem; cursor: pointer; margin-top: 10px; transition: background 0.2s; }
        .btn-admin-signin:hover { background-color: #3b4930; }
        .err-text { color: red; font-size: 0.85rem; margin-bottom: 15px; text-align: left; font-weight: 600; }
    </style>
</head>
<body>

    <nav>
        <div class="logo-container">
            <span class="brand-name">ANARCYXREPTILE</span>
        </div>
        <ul class="nav-links">
            <li><a href="{{ route('home') }}">Home</a></li>
            <li><a href="{{ route('shop') }}">Shop</a></li>
            <li><a href="{{ route('education') }}">Education</a></li>
            <li><a href="{{ route('cart') }}">Cart</a></li>
        </ul>
    </nav>

    <div class="login-page-container">
        <div class="login-card">
            <div class="admin-avatar-circle">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
            </div>
            <h2 class="login-title">Login Admin</h2>
            <p class="login-subtitle">Sign in to access the admin dashboard</p>

            @if($errors->has('login_error'))
                <div class="err-text">{{ $errors->first('login_error') }}</div>
            @endif

            <form action="{{ route('admin.handleLogin') }}" method="POST">
                @csrf
                <div class="form-group-block">
                    <label>Username</label>
                    <div class="input-icon-wrapper">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        <input type="text" name="username" placeholder="Enter username" required>
                    </div>
                </div>
                <div class="form-group-block">
                    <label>Password</label>
                    <div class="input-icon-wrapper">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                        <input type="password" name="password" placeholder="Enter Password" required>
                    </div>
                </div>
                <button type="submit" class="btn-admin-signin">Sign In</button>
            </form>
        </div>
    </div>
</body>
</html>