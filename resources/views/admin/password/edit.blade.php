@extends('layouts.admin.app')

@section('title', 'Ganti Password Admin')

@section('header-title', 'Ganti Password')

@section('content')
<div class="max-w-xl mx-auto">

    {{-- Alert sukses --}}
    @if (session('success'))
        <div class="mb-6 flex items-center gap-3 rounded-lg border border-green-200 bg-green-50 px-5 py-4 text-green-800 shadow-sm">
            <i class="fas fa-check-circle text-green-500 text-lg flex-shrink-0"></i>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">

        {{-- Header card --}}
        <div class="bg-gradient-to-r from-blue-800 to-blue-600 px-6 py-5">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-white/20">
                    <i class="fas fa-lock text-white"></i>
                </div>
                <div>
                    <h2 class="text-white font-semibold text-lg leading-tight">Ganti Password Akun</h2>
                    <p class="text-blue-100 text-xs mt-0.5">Pastikan password baru Anda kuat dan aman</p>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <form method="POST" action="{{ route('admin.password.update') }}" class="px-6 py-6 space-y-5">
            @csrf
            @method('PUT')

            {{-- Password Saat Ini --}}
            <div>
                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Password Saat Ini <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="fas fa-key text-sm"></i>
                    </span>
                    <input
                        id="current_password"
                        name="current_password"
                        type="password"
                        autocomplete="current-password"
                        placeholder="Masukkan password saat ini"
                        class="block w-full rounded-lg border pl-9 pr-10 py-2.5 text-sm
                               text-gray-900 placeholder-gray-400 transition
                               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                               @error('current_password') border-red-400 bg-red-50 @else border-gray-300 bg-white @enderror"
                    >
                    <button type="button"
                        onclick="togglePassword('current_password', this)"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 transition">
                        <i class="fas fa-eye text-sm"></i>
                    </button>
                </div>
                @error('current_password')
                    <p class="mt-1.5 flex items-center gap-1.5 text-xs text-red-600">
                        <i class="fas fa-circle-exclamation"></i> {{ $message }}
                    </p>
                @enderror
            </div>

            <hr class="border-gray-100">

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
                               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                               @error('password') border-red-400 bg-red-50 @else border-gray-300 bg-white @enderror"
                    >
                    <button type="button"
                        onclick="togglePassword('password', this)"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 transition">
                        <i class="fas fa-eye text-sm"></i>
                    </button>
                </div>

                {{-- Password strength bar --}}
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

            {{-- Konfirmasi Password --}}
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
                               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
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
                <a href="{{ route('admin.dashboard') }}"
                   class="text-sm text-gray-500 hover:text-gray-700 transition flex items-center gap-1.5">
                    <i class="fas fa-arrow-left text-xs"></i> Kembali
                </a>
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-semibold
                           text-white shadow-sm hover:bg-blue-800 focus:outline-none focus:ring-2
                           focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                    <i class="fas fa-save text-sm"></i>
                    Simpan Password
                </button>
            </div>
        </form>
    </div>

    {{-- Info box --}}
    <div class="mt-4 rounded-lg border border-amber-100 bg-amber-50 px-5 py-4 flex gap-3">
        <i class="fas fa-triangle-exclamation text-amber-500 mt-0.5 flex-shrink-0"></i>
        <div class="text-xs text-amber-800 space-y-1">
            <p class="font-semibold">Tips keamanan password:</p>
            <ul class="list-disc list-inside space-y-0.5 text-amber-700">
                <li>Gunakan kombinasi huruf besar, huruf kecil, angka, dan simbol</li>
                <li>Minimal 8 karakter, disarankan 12 karakter atau lebih</li>
                <li>Jangan gunakan informasi pribadi yang mudah ditebak</li>
                <li>Jangan bagikan password kepada siapa pun</li>
            </ul>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
    // Toggle show/hide password
    function togglePassword(fieldId, btn) {
        const input = document.getElementById(fieldId);
        const icon  = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }

    // Password strength checker
    function checkStrength(val) {
        const container = document.getElementById('strength-container');
        const label     = document.getElementById('strength-label');
        const bars      = [1,2,3,4].map(n => document.getElementById('bar-' + n));

        if (!val) {
            container.style.display = 'none';
            bars.forEach(b => b.className = 'h-1 flex-1 rounded-full bg-gray-200 transition-all');
            return;
        }

        container.style.display = 'block';

        let score = 0;
        if (val.length >= 8)               score++;
        if (/[A-Z]/.test(val))             score++;
        if (/[0-9]/.test(val))             score++;
        if (/[^A-Za-z0-9]/.test(val))      score++;

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
    }

    // Match checker
    function checkMatch() {
        const pw   = document.getElementById('password').value;
        const conf = document.getElementById('password_confirmation').value;
        const msg  = document.getElementById('match-msg');

        if (!conf) { msg.classList.add('hidden'); return; }

        if (pw === conf) {
            msg.textContent = '✓ Password cocok';
            msg.className   = 'mt-1.5 text-xs text-green-600';
            msg.classList.remove('hidden');
        } else {
            msg.textContent = '✗ Password tidak cocok';
            msg.className   = 'mt-1.5 text-xs text-red-500';
            msg.classList.remove('hidden');
        }
    }
</script>
@endsection