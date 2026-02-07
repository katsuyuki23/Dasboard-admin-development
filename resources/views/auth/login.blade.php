<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Yayasan Assholihin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(rgba(14, 77, 43, 0.85), rgba(10, 60, 33, 0.9)), 
                        url('{{ asset('assets/images/bg_pattern.jpg') }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
            overflow-y: auto;
        }

        /* Decorative Background Pattern */
        body::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background: transparent;
            z-index: 0;
        }

        /* Decorative Elements */
        .decoration {
            position: absolute;
            z-index: 1;
        }

        .decoration-left {
            left: 10%;
            top: 20%;
            width: 200px;
            height: 200px;
        }

        .decoration-right {
            right: 10%;
            bottom: 15%;
            width: 250px;
            height: 250px;
        }

        .dot-pattern {
            width: 120px;
            height: 120px;
            background-image: radial-gradient(circle, #0E4D2B 2px, transparent 2px);
            background-size: 15px 15px;
            opacity: 0.3;
        }

        .squiggle {
            stroke: #0E4D2B;
            opacity: 0.2;
            stroke-width: 2;
            fill: none;
        }

        /* Login Card */
        .login-card {
            background: white;
            border-radius: 30px;
            padding: 50px 45px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08);
            max-width: 480px;
            width: 100%;
            position: relative;
            z-index: 2;
        }

        /* Logo */
        .logo-container {
            text-align: center;
            margin-bottom: 35px;
        }

        .logo-container img {
            width: 90px;
            height: auto;
            margin-bottom: 20px;
        }

        /* Header */
        .login-header {
            text-align: center;
            margin-bottom: 35px;
        }

        .login-header h1 {
            font-size: 1.8rem;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 12px;
        }

        .login-header p {
            color: #6c757d;
            font-size: 0.95rem;
            line-height: 1.5;
        }

        /* Form */
        .form-group {
            margin-bottom: 20px;
        }

        .form-control {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid #e8e8e8;
            border-radius: 12px;
            font-size: 0.95rem;
            color: #1a1a1a;
            transition: all 0.3s;
            background: #fafafa;
        }

        .form-control:focus {
            outline: none;
            border-color: #0E4D2B;
            background: white;
        }

        .form-control::placeholder {
            color: #adb5bd;
        }

        /* Password Group */
        .password-group {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            cursor: pointer;
            font-size: 0.85rem;
            font-weight: 600;
            user-select: none;
        }

        .password-toggle:hover {
            color: #0E4D2B;
        }

        /* Forgot Password Link */
        .forgot-link {
            display: block;
            text-align: center;
            color: #6c757d;
            text-decoration: none;
            font-size: 0.9rem;
            margin: 20px 0;
            transition: 0.3s;
        }

        .forgot-link:hover {
            color: #0E4D2B;
        }

        /* Sign In Button */
        .btn-signin {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #0E4D2B 0%, #1a7a45 100%);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
        }

        .btn-signin:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(14, 77, 43, 0.3);
        }

        .btn-signin:active {
            transform: translateY(0);
        }

        /* Footer */
        .login-footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 25px;
            border-top: 1px solid #e8e8e8;
        }

        .login-footer p {
            color: #6c757d;
            font-size: 0.9rem;
        }

        /* Alert */
        .alert {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 14px 16px;
            border-radius: 10px;
            margin-bottom: 24px;
            font-size: 0.9rem;
        }

        .alert-danger {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }

        .alert ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .alert li {
            padding: 4px 0;
        }

        /* Responsive */
        @media (max-width: 600px) {
            body {
                padding: 20px 15px;
                align-items: flex-start;
            }

            .login-card {
                padding: 35px 25px;
                border-radius: 20px;
                margin: 20px auto;
            }

            .logo-container img {
                width: 70px;
            }

            .login-header h1 {
                font-size: 1.4rem;
            }

            .login-header p {
                font-size: 0.85rem;
            }

            .form-control {
                padding: 12px 16px;
                font-size: 0.9rem;
            }

            .btn-signin {
                padding: 13px;
                font-size: 0.95rem;
            }

            .login-footer p {
                font-size: 0.8rem;
            }

            .decoration {
                display: none;
            }
        }

        @media (max-width: 400px) {
            .login-card {
                padding: 30px 20px;
            }

            .login-header h1 {
                font-size: 1.3rem;
            }

            .login-header p {
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body>

    <!-- Decorative Elements -->
    <div class="decoration decoration-left">
        <svg width="200" height="200" viewBox="0 0 200 200">
            <path class="squiggle" d="M20,80 Q40,40 60,80 T100,80" />
            <circle cx="180" cy="40" r="3" fill="#0E4D2B" opacity="0.2"/>
            <circle cx="30" cy="150" r="4" fill="#0E4D2B" opacity="0.15"/>
        </svg>
        <div class="dot-pattern" style="position: absolute; bottom: 0; left: 0;"></div>
    </div>

    <div class="decoration decoration-right">
        <svg width="250" height="250" viewBox="0 0 250 250">
            <path class="squiggle" d="M20,100 Q50,50 80,100 T140,100" />
            <circle cx="200" cy="50" r="4" fill="#0E4D2B" opacity="0.2"/>
            <circle cx="40" cy="200" r="3" fill="#0E4D2B" opacity="0.15"/>
        </svg>
        <div class="dot-pattern" style="position: absolute; top: 20px; right: 0;"></div>
    </div>

    <!-- Login Card -->
    <div class="login-card">
        <div class="logo-container">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Yayasan Assholihin">
        </div>

        <div class="login-header">
            <h1>Yayasan Assholihin</h1>
            <p>Masukkan detail Anda untuk masuk ke akun sistem manajemen panti asuhan</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li><i class="fas fa-exclamation-circle me-1"></i> {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <input type="email" name="email" class="form-control" 
                       placeholder="Enter Email / Phone No" 
                       value="{{ old('email') }}" 
                       required autofocus>
            </div>

            <div class="form-group">
                <div class="password-group">
                    <input type="password" name="password" id="password" class="form-control" 
                           placeholder="Passcode" 
                           required>
                    <span class="password-toggle" id="togglePassword">Hide</span>
                </div>
            </div>

            <a href="#" class="forgot-link">Having trouble in sign in?</a>

            <button type="submit" class="btn-signin">Sign in</button>
        </form>

        <div class="login-footer">
            <p>&copy; Yayasan Assholihin {{ date('Y') }} | Sistem Manajemen Panti Asuhan</p>
        </div>
    </div>

    <script>
        // Password Toggle
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.textContent = type === 'password' ? 'Hide' : 'Show';
        });
    </script>

</body>
</html>
