<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Fantastic Stays Luxury Villas</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #071913 0%, #0b251d 50%, #153b30 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }
        .login-card {
            background: #ffffff;
            border-radius: 18px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 450px;
            width: 100%;
            overflow: hidden;
            border: 1px solid rgba(197, 168, 128, 0.3);
        }
        .login-header {
            background: #0b251d;
            padding: 2.5rem 2rem 2rem;
            text-align: center;
            border-bottom: 3px solid #c5a880;
        }
        .brand-title {
            font-family: 'Outfit', sans-serif;
            color: #ffffff;
            font-size: 1.6rem;
            font-weight: 700;
            letter-spacing: 1px;
            margin: 0;
        }
        .brand-title span {
            color: #c5a880;
        }
        .btn-gold {
            background: linear-gradient(135deg, #a9853c 0%, #c5a880 100%);
            color: #ffffff;
            border: none;
            font-weight: 600;
            padding: 0.75rem;
            border-radius: 10px;
            width: 100%;
            transition: all 0.2s ease;
        }
        .btn-gold:hover {
            color: #ffffff;
            opacity: 0.95;
            box-shadow: 0 6px 18px rgba(169, 133, 60, 0.4);
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="login-header">
        <h1 class="brand-title"><span>FANTASTIC</span> STAYS</h1>
        <p class="text-white-50 mb-0 small mt-1" style="letter-spacing: 1px;">LUXURY VILLAS ADMIN PORTAL</p>
    </div>

    <div class="p-4 p-md-5">
        <h4 class="fw-bold mb-1" style="font-family: 'Outfit', sans-serif; color: #0b251d;">Sign In</h4>
        <p class="text-muted small mb-4">Enter your credentials to access the management dashboard.</p>

        @if(session('error'))
            <div class="alert alert-danger py-2 small mb-3">
                <i class="bi bi-exclamation-circle me-1"></i> {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger py-2 small mb-3">
                @foreach($errors->all() as $err)
                    <div>{{ $err }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('admin.login.post') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label small fw-semibold text-secondary">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                    <input type="email" name="email" class="form-control bg-light border-start-0" value="{{ old('email', 'admin@fantasticstays.com') }}" required autofocus>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-semibold text-secondary">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" id="password" class="form-control bg-light border-start-0" value="admin123" required>
                    <button class="btn btn-light border border-start-0 text-muted" type="button" onclick="togglePassword()">
                        <i class="bi bi-eye" id="eye-icon"></i>
                    </button>
                </div>
            </div>

            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" checked>
                    <label class="form-check-label small text-secondary" for="remember">
                        Remember me
                    </label>
                </div>
            </div>

            <button type="submit" class="btn btn-gold mb-3">
                Sign In to Dashboard <i class="bi bi-arrow-right ms-1"></i>
            </button>

            <div class="card bg-light border-0 p-3 rounded-3 mt-3 text-center">
                <small class="text-muted d-block fw-semibold mb-1"><i class="bi bi-key-fill text-warning me-1"></i> Default Demo Credentials</small>
                <code class="text-dark small d-block">admin@fantasticstays.com &bull; admin123</code>
            </div>
        </form>
    </div>
</div>

<script>
    function togglePassword() {
        const input = document.getElementById('password');
        const icon = document.getElementById('eye-icon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }
</script>
</body>
</html>
