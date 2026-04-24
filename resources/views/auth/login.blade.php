<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Repositori Dosen - Teknik Informatika UNIMA</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Lora:ital,wght@0,400;0,600;1,400;1,600&display=swap" rel="stylesheet">

    <style>
        :root {
            --navy:        #0d1f4e;
            --navy-mid:    #1e3a8a;
            --navy-glow:   rgba(30,58,138,0.18);
            --gold:        #c9a84c;
            --gold-pale:   #f5e9c8;
            --cream:       #f7f4ef;
            --surface:     #ffffff;
            --text:        #111827;
            --muted:       #6b7280;
            --border:      #e4e7f0;
            --danger:      #dc2626;
            --danger-soft: #fff1f1;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--cream);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            position: relative;
            overflow-x: hidden;
        }

        /* ── Background layers ── */
        .bg-layer {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 0;
            overflow: hidden;
        }

        .bg-layer::before {
            content: '';
            position: absolute;
            top: -120px; left: -120px;
            width: 520px; height: 520px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(30,58,138,0.09) 0%, transparent 70%);
        }

        .bg-layer::after {
            content: '';
            position: absolute;
            bottom: -80px; right: -80px;
            width: 420px; height: 420px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(201,168,76,0.10) 0%, transparent 70%);
        }

        .bg-grid {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 0;
            background-image:
                linear-gradient(rgba(30,58,138,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(30,58,138,0.03) 1px, transparent 1px);
            background-size: 40px 40px;
        }

        /* ── Page center ── */
        .page-center {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 440px;
            display: flex;
            flex-direction: column;
            align-items: center;
            animation: riseIn 0.55s cubic-bezier(0.22,1,0.36,1) both;
        }

        @keyframes riseIn {
            from { opacity: 0; transform: translateY(24px) scale(0.98); }
            to   { opacity: 1; transform: translateY(0)    scale(1); }
        }

        /* ── Emblem ── */
        .emblem {
            margin-bottom: 24px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }

        .emblem-icon {
            width: 64px; height: 64px;
            background: linear-gradient(145deg, var(--navy-mid), var(--navy));
            border-radius: 18px;
            display: flex; align-items: center; justify-content: center;
            box-shadow:
                0 0 0 6px rgba(30,58,138,0.08),
                0 12px 32px rgba(13,31,78,0.28);
            position: relative;
        }

        .emblem-icon::after {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 18px;
            background: linear-gradient(135deg, rgba(255,255,255,0.12) 0%, transparent 60%);
        }

        .emblem-icon i {
            font-size: 26px;
            color: var(--gold);
            position: relative;
            z-index: 1;
        }

        .emblem-label { text-align: center; }

        .emblem-label h1 {
            font-family: 'Lora', serif;
            font-size: 1.18rem;
            font-weight: 600;
            color: var(--navy);
            letter-spacing: -0.01em;
        }

        .emblem-label p {
            font-size: 0.7rem;
            font-weight: 500;
            color: var(--muted);
            letter-spacing: 0.07em;
            text-transform: uppercase;
            margin-top: 2px;
        }

        /* ── Card ── */
        .card {
            width: 100%;
            background: var(--surface);
            border-radius: 22px;
            border: 1px solid var(--border);
            box-shadow:
                0 2px 0 rgba(255,255,255,0.8) inset,
                0 1px 3px rgba(0,0,0,0.04),
                0 8px 24px rgba(13,31,78,0.08),
                0 32px 64px rgba(13,31,78,0.10);
            overflow: hidden;
        }

        .card-stripe {
            height: 4px;
            background: linear-gradient(90deg, var(--gold) 0%, #e8cc7a 40%, rgba(201,168,76,0.15) 100%);
        }

        .card-body {
            padding: 36px 36px 28px;
        }

        .card-title {
            font-family: 'Lora', serif;
            font-size: 1.65rem;
            font-weight: 600;
            color: var(--navy);
            margin-bottom: 4px;
            letter-spacing: -0.02em;
        }

        .card-title em {
            font-style: italic;
            color: var(--gold);
        }

        .card-sub {
            font-size: 0.8rem;
            color: var(--muted);
            margin-bottom: 28px;
            line-height: 1.5;
        }

        /* ── Alert ── */
        .alert {
            background: var(--danger-soft);
            border: 1px solid #fecaca;
            border-left: 3px solid var(--danger);
            border-radius: 10px;
            padding: 11px 14px;
            margin-bottom: 20px;
            font-size: 0.8rem;
            color: #991b1b;
            display: flex;
            align-items: flex-start;
            gap: 9px;
            animation: shake 0.4s ease;
        }

        .alert i { flex-shrink: 0; margin-top: 2px; }

        @keyframes shake {
            0%,100% { transform: translateX(0); }
            25%,75%  { transform: translateX(-4px); }
            50%      { transform: translateX(4px); }
        }

        /* ── Fields ── */
        .field { margin-bottom: 18px; }

        .field-label {
            font-size: 0.72rem;
            font-weight: 700;
            color: var(--navy);
            letter-spacing: 0.06em;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 7px;
        }

        .field-label i {
            color: var(--gold);
            font-size: 0.68rem;
        }

        .field-wrap { position: relative; }

        .field-input {
            width: 100%;
            height: 48px;
            padding: 0 46px 0 16px;
            border: 1.5px solid var(--border);
            border-radius: 11px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.875rem;
            color: var(--text);
            background: #fafbfd;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        }

        .field-input::placeholder { color: #c0c8d8; }

        .field-input:focus {
            border-color: var(--navy-mid);
            background: #fff;
            box-shadow: 0 0 0 3.5px var(--navy-glow);
        }

        .field-input.is-invalid {
            border-color: var(--danger);
            box-shadow: 0 0 0 3px rgba(220,38,38,0.10);
        }

        .eye-btn {
            position: absolute;
            right: 13px; top: 50%;
            transform: translateY(-50%);
            background: none; border: none; cursor: pointer;
            color: #c0c8d8; font-size: 0.85rem;
            transition: color 0.2s;
            padding: 4px;
            line-height: 1;
        }

        .eye-btn:hover { color: var(--navy-mid); }

        .field-error {
            font-size: 0.72rem;
            color: var(--danger);
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* ── Submit ── */
        .btn-submit {
            width: 100%;
            height: 50px;
            margin-top: 8px;
            background: linear-gradient(135deg, var(--navy) 0%, var(--navy-mid) 100%);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.88rem;
            font-weight: 700;
            letter-spacing: 0.03em;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center; gap: 9px;
            transition: transform 0.2s, box-shadow 0.2s;
            position: relative;
            overflow: hidden;
        }

        .btn-submit::before {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 60%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.08), transparent);
            transition: left 0.5s ease;
        }

        .btn-submit:hover::before { left: 150%; }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 10px 28px rgba(13,31,78,0.30); }
        .btn-submit:active { transform: translateY(0); }

        /* ── Footer ── */
        .card-footer {
            background: #f9fafb;
            border-top: 1px solid var(--border);
            padding: 12px 36px;
            font-size: 0.7rem;
            color: var(--muted);
            text-align: center;
        }

        @media (max-width: 480px) {
            .card-body   { padding: 26px 20px 22px; }
            .card-footer { padding: 12px 20px; }
        }
    </style>
</head>
<body>

    <div class="bg-layer"></div>
    <div class="bg-grid"></div>

    <div class="page-center">

        <!-- Emblem -->
        <div class="emblem">
            <div class="emblem-icon">
                <i class="fas fa-book-open"></i>
            </div>
            <div class="emblem-label">
                <h1>Repositori Dosen</h1>
                <p>Teknik Informatika · Universitas Negeri Manado</p>
            </div>
        </div>

        <!-- Card -->
        <div class="card">
            <div class="card-stripe"></div>

            <div class="card-body">
                <h2 class="card-title">Selamat <em>Datang</em></h2>
                <p class="card-sub">Masukkan email dan password untuk mengakses sistem.</p>

                {{-- Error validation --}}
                @if ($errors->any())
                    <div class="alert">
                        <i class="fas fa-exclamation-circle"></i>
                        <div>
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Session error --}}
                @if (session('error'))
                    <div class="alert">
                        <i class="fas fa-exclamation-circle"></i>
                        <div>{{ session('error') }}</div>
                    </div>
                @endif

                <form method="POST" action="{{ route('login.post') }}">
                    @csrf

                    <!-- Email -->
                    <div class="field">
                        <label class="field-label" for="email">
                            <i class="fas fa-envelope"></i> Email
                        </label>
                        <div class="field-wrap">
                            <input
                                id="email"
                                type="email"
                                name="email"
                                class="field-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                placeholder="Masukkan email Anda"
                                value="{{ old('email') }}"
                                required
                                autocomplete="email"
                                autofocus
                            >
                        </div>
                        @error('email')
                            <p class="field-error">
                                <i class="fas fa-circle-exclamation"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="field">
                        <label class="field-label" for="password">
                            <i class="fas fa-lock"></i> Password
                        </label>
                        <div class="field-wrap">
                            <input
                                id="password"
                                type="password"
                                name="password"
                                class="field-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                placeholder="Masukkan password"
                                required
                                autocomplete="current-password"
                            >
                            <button type="button" class="eye-btn" onclick="togglePass()" title="Tampilkan / sembunyikan password">
                                <i class="fas fa-eye" id="eye-icon"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="field-error">
                                <i class="fas fa-circle-exclamation"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <button type="submit" class="btn-submit">
                        <i class="fas fa-arrow-right-to-bracket"></i>
                        Masuk
                    </button>
                </form>
            </div>

            <div class="card-footer">
                © 2025 Repositori Dosen · Teknik Informatika UNIMA
            </div>
        </div>

    </div>

    <script>
        function togglePass() {
            const input = document.getElementById('password');
            const icon  = document.getElementById('eye-icon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    </script>

</body>
</html>