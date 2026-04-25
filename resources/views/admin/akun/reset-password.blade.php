@extends('layouts.admin.app')

@section('title', 'Reset Password Akun')
@section('header-title', 'Reset Password')

@section('content')
<div class="max-w-xl mx-auto">

    {{-- Breadcrumb --}}
    <nav class="mb-5 flex items-center gap-2 text-sm text-gray-500">
        <a href="{{ route('admin.akun.index') }}" class="hover:text-blue-600 transition">Manajemen Akun</a>
        <i class="fas fa-chevron-right text-xs"></i>
        <span class="text-gray-800 font-medium">Reset Password</span>
    </nav>

    {{-- Info akun yang akan direset --}}
    <div class="mb-5 bg-white rounded-xl border border-gray-100 shadow-sm px-5 py-4 flex items-center gap-4">
        <div class="flex-shrink-0 h-12 w-12 rounded-full flex items-center justify-center text-white font-bold text-lg
            {{ $user->role === 'admin' ? 'bg-purple-500' : 'bg-blue-600' }}">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>
        <div class="flex-1 min-w-0">
            <p class="font-semibold text-gray-800 truncate">{{ $user->name }}</p>
            <p class="text-sm text-gray-500 truncate">{{ $user->email }}</p>
            @if ($dosen)
                <p class="text-xs text-gray-400 mt-0.5">NIDN: {{ $dosen->nidn ?? '—' }}</p>
            @endif
        </div>
        <div>
            @if ($user->role === 'admin')
                <span class="inline-flex items-center gap-1 rounded-full bg-purple-100 px-2.5 py-1 text-xs font-medium text-purple-700">
                    <i class="fas fa-user-shield text-[10px]"></i> Admin
                </span>
            @else
                <span class="inline-flex items-center gap-1 rounded-full bg-blue-100 px-2.5 py-1 text-xs font-medium text-blue-700">
                    <i class="fas fa-user-tie text-[10px]"></i> Dosen
                </span>
            @endif
        </div>
    </div>

    {{-- Form --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">

        {{-- Header card --}}
        <div class="bg-gradient-to-r from-amber-600 to-amber-500 px-6 py-5">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-white/20">
                    <i class="fas fa-key text-white"></i>
                </div>
                <div>
                    <h2 class="text-white font-semibold text-lg leading-tight">Reset Password Akun</h2>
                    <p class="text-amber-100 text-xs mt-0.5">Password lama akan langsung diganti tanpa perlu verifikasi</p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.akun.reset-password.update', $user->id) }}" class="px-6 py-6 space-y-5">
            @csrf
            @method('PUT')

            {{-- Password Baru --}}
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Password Baru <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="fas fa-lock text-sm"></i>
                    </span>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        autocomplete="new-password"
                        placeholder="Minimal 8 karakter"
                        oninput="checkStrength(this.value)"
                        class="block w-full rounded-lg border pl-9 pr-10 py-2.5 text-sm
                               text-gray-900 placeholder-gray-400 transition
                               focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent
                               @error('password') border-red-400 bg-red-50 @else border-gray-300 bg-white @enderror"
                    >
                    <button type="button"
                        onclick="togglePassword('password', this)"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 transition">
                        <i class="fas fa-eye text-sm"></i>
                    </button>
                </div>

                {{-- Strength bar --}}
                <div class="mt-2 space-y-1" id="strength-container" style="display:none">
                    <div class="flex gap-1">
                        <div id="bar-1" class="h-1 flex-1 rounded-full bg-gray-200 transition-all"></div>
                        <div id="bar-2" class="h-1 flex-1 rounded-full bg-gray-200 transition-all"></div>
                        <div id="bar-3" class="h-1 flex-1 rounded-full bg-gray-200 transition-all"></div>
                        <div id="bar-4" class="h-1 flex-1 rounded-full bg-gray-200 transition-all"></div>
                    </div>
                    <p id="strength-label" class="text-xs text-gray-500"></p>
                </div>

                @error('password')
                    <p class="mt-1.5 flex items-center gap-1.5 text-xs text-red-600">
                        <i class="fas fa-circle-exclamation"></i> {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Konfirmasi --}}
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Konfirmasi Password Baru <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="fas fa-lock text-sm"></i>
                    </span>
                    <input
                        id="password_confirmation"
                        name="password_confirmation"
                        type="password"
                        autocomplete="new-password"
                        placeholder="Ulangi password baru"
                        oninput="checkMatch()"
                        class="block w-full rounded-lg border pl-9 pr-10 py-2.5 text-sm
                               text-gray-900 placeholder-gray-400 transition
                               focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent
                               @error('password_confirmation') border-red-400 bg-red-50 @else border-gray-300 bg-white @enderror"
                    >
                    <button type="button"
                        onclick="togglePassword('password_confirmation', this)"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 transition">
                        <i class="fas fa-eye text-sm"></i>
                    </button>
                </div>
                <p id="match-msg" class="mt-1.5 text-xs hidden"></p>
                @error('password_confirmation')
                    <p class="mt-1.5 flex items-center gap-1.5 text-xs text-red-600">
                        <i class="fas fa-circle-exclamation"></i> {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Tombol --}}
            <div class="flex items-center justify-between pt-2">
                <a href="{{ route('admin.akun.index') }}"
                   class="text-sm text-gray-500 hover:text-gray-700 transition flex items-center gap-1.5">
                    <i class="fas fa-arrow-left text-xs"></i> Kembali
                </a>
                <button type="submit"
                    id="submit-btn"
                    disabled
                    class="inline-flex items-center gap-2 rounded-lg px-5 py-2.5 text-sm font-semibold text-white shadow-sm
                           transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500
                           bg-gray-300 cursor-not-allowed"
                    title="Isi dan cocokkan password terlebih dahulu">
                    <i class="fas fa-key text-sm"></i>
                    Reset Password
                </button>
            </div>
        </form>
    </div>

    {{-- Warning box --}}
    <div class="mt-4 rounded-lg border border-red-100 bg-red-50 px-5 py-4 flex gap-3">
        <i class="fas fa-triangle-exclamation text-red-500 mt-0.5 flex-shrink-0"></i>
        <div class="text-xs text-red-800 space-y-1">
            <p class="font-semibold">Perhatian!</p>
            <ul class="list-disc list-inside space-y-0.5 text-red-700">
                <li>Password lama akun <strong>{{ $user->name }}</strong> akan langsung diganti.</li>
                <li>Beritahu pengguna tersebut password barunya secara langsung.</li>
                <li>Tindakan ini tercatat di log audit sistem.</li>
            </ul>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
    function togglePassword(fieldId, btn) {
        const input = document.getElementById(fieldId);
        const icon  = btn.querySelector('i');
        input.type  = input.type === 'password' ? 'text' : 'password';
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
    }

    function checkStrength(val) {
        const container = document.getElementById('strength-container');
        const label     = document.getElementById('strength-label');
        const bars      = [1,2,3,4].map(n => document.getElementById('bar-' + n));

        if (!val) { container.style.display = 'none'; return; }
        container.style.display = 'block';

        let score = 0;
        if (val.length >= 8)          score++;
        if (/[A-Z]/.test(val))        score++;
        if (/[0-9]/.test(val))        score++;
        if (/[^A-Za-z0-9]/.test(val)) score++;

        const levels = [
            { color: 'bg-red-400',    text: 'Sangat lemah',  textColor: 'text-red-500'    },
            { color: 'bg-orange-400', text: 'Lemah',         textColor: 'text-orange-500' },
            { color: 'bg-yellow-400', text: 'Cukup',         textColor: 'text-yellow-600' },
            { color: 'bg-green-500',  text: 'Kuat',          textColor: 'text-green-600'  },
        ];
        const lvl = levels[score - 1] || levels[0];
        bars.forEach((b, i) => {
            b.className = 'h-1 flex-1 rounded-full transition-all ' + (i < score ? lvl.color : 'bg-gray-200');
        });
        label.textContent = lvl.text;
        label.className   = 'text-xs ' + lvl.textColor;
        validateSubmit();
    }

    function checkMatch() {
        const pw   = document.getElementById('password').value;
        const conf = document.getElementById('password_confirmation').value;
        const msg  = document.getElementById('match-msg');

        if (!conf) { msg.classList.add('hidden'); validateSubmit(); return; }

        if (pw === conf) {
            msg.textContent = '✓ Password cocok';
            msg.className   = 'mt-1.5 text-xs text-green-600';
        } else {
            msg.textContent = '✗ Password tidak cocok';
            msg.className   = 'mt-1.5 text-xs text-red-500';
        }
        msg.classList.remove('hidden');
        validateSubmit();
    }

    function validateSubmit() {
        const pw   = document.getElementById('password').value;
        const conf = document.getElementById('password_confirmation').value;
        const btn  = document.getElementById('submit-btn');
        const ok   = pw.length >= 8 && pw === conf;

        btn.disabled = !ok;
        if (ok) {
            btn.className = btn.className
                .replace('bg-gray-300 cursor-not-allowed', 'bg-amber-600 hover:bg-amber-700 cursor-pointer');
        } else {
            btn.className = btn.className
                .replace('bg-amber-600 hover:bg-amber-700 cursor-pointer', 'bg-gray-300 cursor-not-allowed');
        }
    }
</script>
@endsection