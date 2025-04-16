<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MetaGame - Masuk</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary-color: #121212;
            --secondary-color: #1f1f1f;
            --accent-color: #bb86fc;
            --accent-color-secondary: #03dac6;
            --text-primary: #ffffff;
            --text-secondary: #e0e0e0;
            --background-color: #121212;
            --card-background: #1a1a1a;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            line-height: 1.6;
            background-color: var(--background-color);
            color: var(--text-primary);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .login-container {
            background-color: var(--card-background);
            border-radius: 8px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.5);
            width: 100%;
            max-width: 400px;
            padding: 35px;
            border: 1px solid #2d2d2d;
        }

        .site-title {
            font-size: 2rem;
            font-weight: bold;
            color: var(--accent-color);
            text-align: center;
            margin-bottom: 25px;
        }

        .site-title span {
            color: var(--accent-color-secondary);
        }

        .login-form {
            display: flex;
            flex-direction: column;
        }

        .input-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-control {
            width: 100%;
            padding: 12px 40px 12px 15px;
            border: 2px solid #2d2d2d;
            background-color: #1f1f1f;
            color: var(--text-primary);
            border-radius: 4px;
            outline: none;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--accent-color);
        }

        .input-group-text {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--accent-color);
            pointer-events: none;
        }
        
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--accent-color);
            cursor: pointer;
            z-index: 10;
        }

        .error-message {
            color: #ff4136;
            font-size: 0.9em;
            margin-top: 5px;
            margin-left: 5px;
        }

        .login-btn {
            background-color: var(--accent-color);
            color: #121212;
            border: none;
            padding: 12px 20px;
            border-radius: 4px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: background-color 0.3s;
            margin-top: 10px;
            cursor: pointer;
        }

        .login-btn:hover {
            background-color: #9966FF;
        }

        .register-link {
            text-align: center;
            margin-top: 20px;
        }

        .register-link a {
            color: var(--accent-color);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .register-link a:hover {
            color: var(--accent-color-secondary);
        }

        .return-home {
            text-align: center;
            margin-top: 20px;
            font-size: 0.9em;
        }

        .return-home a {
            color: var(--text-secondary);
            text-decoration: none;
        }

        .return-home a:hover {
            color: var(--accent-color-secondary);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="site-title">Meta<span>Game</span></div>
        
        <form action="{{ route('login') }}" method="POST" class="login-form">
            @csrf

            <div class="input-group">
                <input type="email" class="form-control" name="email" placeholder="Email" required>
                <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                </div>
            </div>
            @error('email')
                <div class="error-message">{{ $message }}</div>
            @enderror

            <div class="input-group">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                <span class="password-toggle" onclick="togglePassword('password')">
                    <i class="fas fa-eye" id="password-icon"></i>
                </span>
            </div>
            @error('password')
                <div class="error-message">{{ $message }}</div>
            @enderror

            <button type="submit" class="login-btn">Masuk</button>
        </form>

        <div class="register-link">
            <a href="{{ route('register') }}">Belum punya akun? Daftar disini</a>
        </div>
        
        <div class="return-home">
            <a href="/"><i class="fas fa-home"></i> Kembali ke Beranda</a>
        </div>
    </div>

    <script>
        function togglePassword(fieldId) {
            const passwordField = document.getElementById(fieldId);
            const passwordIcon = document.getElementById(fieldId + '-icon');
            
            if (passwordField.type === "password") {
                passwordField.type = "text";
                passwordIcon.classList.remove("fa-eye");
                passwordIcon.classList.add("fa-eye-slash");
            } else {
                passwordField.type = "password";
                passwordIcon.classList.remove("fa-eye-slash");
                passwordIcon.classList.add("fa-eye");
            }
        }
    </script>
</body>
</html>