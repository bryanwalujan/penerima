<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Repositori Dosen - Teknik Informatika UNIMA</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">

    <style>
        :root {
            --navy:       #0f2356;
            --navy-mid:   #1e3a8a;
            --navy-light: #2d52b8;
            --gold:       #c8a84b;
            --gold-light: #e8cc7a;
            --cream:      #f8f5ee;
            --surface:    #ffffff;
            --text:       #1a1f36;
            --muted:      #6b7280;
            --danger:     #dc2626;
            --danger-bg:  #fef2f2;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--cream);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            position: relative;
            overflow-x: hidden;
        }

        /* ── Background geometric decoration ── */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background:
                radial-gradient(ellipse 80% 60% at 10% 20%, rgba(30,58,138,0.08) 0%, transparent 60%),
                radial-gradient(ellipse 60% 80% at 90% 80%, rgba(200,168,75,0.07) 0%, transparent 60%);
            pointer-events: none;
            z-index: 0;
        }

        .geo-line {
            position: fixed;
            pointer-events: none;
            z-index: 0;
        }
        .geo-line-1 {
            top: -80px; left: -80px;
            width: 400px; height: 400px;
            border: 1px solid rgba(30,58,138,0.08);
            border-radius: 50%;
        }
        .geo-line-2 {
            bottom: -100px; right: -100px;
            width: 500px; height: 500px;
            border: 1px solid rgba(200,168,75,0.1);
            border-radius: 50%;
        }
        .geo-line-3 {
            top: 50%; right: 5%;
            transform: translateY(-50%);
            width: 200px; height: 200px;
            border: 1px solid rgba(30,58,138,0.05);
            transform: translateY(-50%) rotate(45deg);
        }

        /* ── Card wrapper ── */
        .card-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 480px;
        }

        /* ── Header badge ── */
        .header-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 28px;
        }

        .badge-icon {
            width: 52px; height: 52px;
            background: var(--navy);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 24px rgba(15,35,86,0.25);
        }

        .badge-icon i {
            color: var(--gold-light);
            font-size: 22px;
        }

        .badge-text h1 {
            font-family: 'DM Serif Display', serif;
            font-size: 1.25rem;
            color: var(--navy);
            line-height: 1.2;
        }

        .badge-text p {
            font-size: 0.72rem;
            color: var(--muted);
            font-weight: 500;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        /* ── Main card ── */
        .login-card {
            background: var(--surface);
            border-radius: 20px;
            box-shadow:
                0 1px 0px rgba(0,0,0,0.04),
                0 4px 16px rgba(15,35,86,0.06),
                0 20px 48px rgba(15,35,86,0.10);
            overflow: hidden;
            border: 1px solid rgba(15,35,86,0.06);
        }

        /* ── Tab switcher ── */
        .tab-switcher {
            display: grid;
            grid-template-columns: 1fr 1fr;
            background: #f1f3f9;
            padding: 5px;
            gap: 4px;
        }

        .tab-btn {
            padding: 10px 16px;
            border: none;
            border-radius: 10px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.25s ease;
            color: var(--muted);
            background: transparent;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            letter-spacing: 0.01em;
        }

        .tab-btn.active {
            background: var(--surface);
            color: var(--navy);
            box-shadow: 0 2px 8px rgba(15,35,86,0.10);
        }

        .tab-btn:hover:not(.active) {
            color: var(--navy-mid);
            background: rgba(255,255,255,0.5);
        }

        /* ── Form section ── */
        .form-section {
            padding: 32px;
            display: none;
        }

        .form-section.active {
            display: block;
        }

        .section-title {
            font-family: 'DM Serif Display', serif;
            font-size: 1.5rem;
            color: var(--navy);
            margin-bottom: 6px;
        }

        .section-subtitle {
            font-size: 0.82rem;
            color: var(--muted);
            margin-bottom: 28px;
        }

        /* ── Input group ── */
        .input-group {
            margin-bottom: 18px;
        }

        .input-label {
            display: flex;
            align-items: center;
            gap: 7px;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 7px;
            letter-spacing: 0.01em;
        }

        .input-label i {
            color: var(--navy-light);
            font-size: 0.78rem;
            width: 14px;
        }

        .input-wrap {
            position: relative;
        }

        .input-field {
            width: 100%;
            padding: 12px 44px 12px 14px;
            border: 1.5px solid #e2e6f0;
            border-radius: 10px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.88rem;
            color: var(--text);
            background: #fafbfd;
            outline: none;
            transition: all 0.2s ease;
        }

        .input-field:focus {
            border-color: var(--navy-light);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(45,82,184,0.10);
        }

        .input-field::placeholder {
            color: #b0b8cc;
        }

        .input-toggle {
            position: absolute;
            right: 13px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #b0b8cc;
            font-size: 0.85rem;
            padding: 4px;
            transition: color 0.2s;
            line-height: 1;
        }

        .input-toggle:hover { color: var(--navy-mid); }

        .field-error {
            font-size: 0.75rem;
            color: var(--danger);
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        /* ── Alert ── */
        .alert-error {
            background: var(--danger-bg);
            border: 1px solid #fecaca;
            border-left: 3px solid var(--danger);
            border-radius: 10px;
            padding: 12px 14px;
            margin-bottom: 22px;
            font-size: 0.82rem;
            color: #991b1b;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .alert-error i { font-size: 0.95rem; margin-top: 1px; flex-shrink: 0; }

        /* ── Submit button ── */
        .btn-submit {
            width: 100%;
            padding: 13px 20px;
            background: linear-gradient(135deg, var(--navy) 0%, var(--navy-light) 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.88rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.25s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 8px;
            letter-spacing: 0.02em;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(15,35,86,0.28);
        }

        .btn-submit:active { transform: translateY(0); }

        /* ── Divider ── */
        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 24px 0 20px;
        }

        .divider-line {
            flex: 1;
            height: 1px;
            background: #e8ebf3;
        }

        .divider-text {
            font-size: 0.72rem;
            font-weight: 600;
            color: #b0b8cc;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            white-space: nowrap;
        }

        /* ── Gold accent bar ── */
        .gold-bar {
            height: 3px;
            background: linear-gradient(90deg, var(--gold) 0%, var(--gold-light) 50%, transparent 100%);
            border-radius: 0 0 0 0;
        }

        /* ── Footer ── */
        .card-footer {
            background: #fafbfd;
            border-top: 1px solid #f0f2f8;
            padding: 14px 32px;
            text-align: center;
            font-size: 0.73rem;
            color: var(--muted);
        }

        /* ── Animations ── */
        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .card-wrapper {
            animation: fadeSlideUp 0.5s ease both;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20%, 60%  { transform: translateX(-4px); }
            40%, 80%  { transform: translateX(4px); }
        }

        .has-error { animation: shake 0.4s ease; }
    </style>
</head>
<body>
    <!-- Geometric decorations -->
    <div class="geo-line geo-line-1"></div>
    <div class="geo-line geo-line-2"></div>
    <div class="geo-line geo-line-3"></div>

    <div class="card-wrapper">

        <!-- Header badge -->
        <div class="header-badge">
            <div class="badge-icon">
                <i class="fas fa-book-open"></i>
            </div>
            <div class="badge-text">
                <h1>Repositori Dosen</h1>
                <p>Teknik Informatika · Universitas Negeri Manado</p>
            </div>
        </div>

        <!-- Card -->
        <div class="login-card">

            <!-- Gold accent line -->
            <div class="gold-bar"></div>

            <!-- Tab switcher -->
            <div class="tab-switcher">
                <button class="tab-btn active" onclick="switchTab('dosen')" id="tab-dosen">
                    <i class="fas fa-chalkboard-teacher"></i> Dosen
                </button>
                <button class="tab-btn" onclick="switchTab('admin')" id="tab-admin">
                    <i class="fas fa-user-shield"></i> Admin
                </button>
            </div>

            <!-- ═══ FORM DOSEN ═══ -->
            <div class="form-section active" id="section-dosen">
                <p class="section-title">Selamat Datang</p>
                <p class="section-subtitle">Masuk menggunakan akun dosen Anda</p>

                {{-- Error untuk form dosen --}}
                @if ($errors->has('email_dosen') || (session('active_tab') === 'dosen' && $errors->any()))
                    <div class="alert-error has-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <div>
                            @if ($errors->has('email_dosen'))
                                {{ $errors->first('email_dosen') }}
                            @else
                                @foreach ($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('login.dosen') }}">
                    @csrf

                    <!-- Email -->
                    <div class="input-group">
                        <label class="input-label">
                            <i class="fas fa-envelope"></i> Email
                        </label>
                        <div class="input-wrap">
                            <input
                                type="email"
                                name="email"
                                class="input-field"
                                placeholder="email@unima.ac.id"
                                value="{{ old('email') }}"
                                required
                                autocomplete="email"
                            >
                        </div>
                        @error('email_dosen')
                            <p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="input-group">
                        <label class="input-label">
                            <i class="fas fa-lock"></i> Password
                        </label>
                        <div class="input-wrap">
                            <input
                                type="password"
                                name="password"
                                id="pass_dosen"
                                class="input-field"
                                placeholder="Masukkan password"
                                required
                                autocomplete="current-password"
                            >
                            <button type="button" class="input-toggle" onclick="togglePass('pass_dosen', this)">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">
                        <i class="fas fa-sign-in-alt"></i> Masuk sebagai Dosen
                    </button>
                </form>
            </div>

            <!-- ═══ FORM ADMIN ═══ -->
            <div class="form-section" id="section-admin">
                <p class="section-title">Panel Admin</p>
                <p class="section-subtitle">Akses khusus administrator sistem</p>

                {{-- Error untuk form admin --}}
                @if ($errors->has('email') && session('active_tab') === 'admin')
                    <div class="alert-error has-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <div>{{ $errors->first('email') }}</div>
                    </div>
                @endif

                <form method="POST" action="{{ route('login.post') }}">
                    @csrf

                    <!-- Email -->
                    <div class="input-group">
                        <label class="input-label">
                            <i class="fas fa-envelope"></i> Email Admin
                        </label>
                        <div class="input-wrap">
                            <input
                                type="email"
                                name="email"
                                class="input-field"
                                placeholder="admin@example.com"
                                value="{{ old('email') }}"
                                required
                                autocomplete="email"
                            >
                        </div>
                        @error('email')
                            <p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="input-group">
                        <label class="input-label">
                            <i class="fas fa-lock"></i> Password
                        </label>
                        <div class="input-wrap">
                            <input
                                type="password"
                                name="password"
                                id="pass_admin"
                                class="input-field"
                                placeholder="Masukkan password"
                                required
                                autocomplete="current-password"
                            >
                            <button type="button" class="input-toggle" onclick="togglePass('pass_admin', this)">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">
                        <i class="fas fa-sign-in-alt"></i> Masuk sebagai Admin
                    </button>
                </form>
            </div>

            <!-- Footer -->
            <div class="card-footer">
                © 2025 Repositori Dosen · Teknik Informatika UNIMA
            </div>

        </div>
    </div>

    <script>
        // ── Tab switcher ──────────────────────────────────────────
        function switchTab(tab) {
            const tabs     = ['dosen', 'admin'];
            const sections = { dosen: 'section-dosen', admin: 'section-admin' };

            tabs.forEach(t => {
                const btn = document.getElementById('tab-' + t);
                const sec = document.getElementById(sections[t]);
                if (t === tab) {
                    btn.classList.add('active');
                    sec.classList.add('active');
                } else {
                    btn.classList.remove('active');
                    sec.classList.remove('active');
                }
            });
        }

        // ── Toggle show/hide password ─────────────────────────────
        function togglePass(inputId, btn) {
            const input = document.getElementById(inputId);
            const icon  = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        // ── Auto switch tab berdasarkan session active_tab ────────
        // Dikirim dari controller saat ada error agar tab tetap terbuka
        const activeTab = "{{ session('active_tab', 'dosen') }}";
        if (activeTab === 'admin') {
            switchTab('admin');
        }
    </script>
</body>
</html>