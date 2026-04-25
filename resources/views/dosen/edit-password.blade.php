{{-- resources/views/dosen/edit-password.blade.php --}}
@extends('layouts.dosen.app')

@section('title', 'Ganti Password - Repositori Dosen')
@section('header-title', 'Ganti Password')
@section('header-subtitle', 'Perbarui password akun Anda secara berkala untuk keamanan')

@section('styles')
<style>
    .profile-card {
        transition: all 0.3s ease;
        border-radius: 20px;
    }
    .profile-card:hover {
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
    }
    .input-group {
        transition: all 0.2s ease;
    }
    .input-group:focus-within {
        transform: translateY(-1px);
    }
    .input-field {
        transition: all 0.2s ease;
        border-radius: 12px;
    }
    .input-field:focus {
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    .btn-action {
        transition: all 0.3s ease;
        border-radius: 12px;
        font-weight: 600;
    }
    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    .strength-bar {
        height: 4px;
        border-radius: 2px;
        transition: all 0.3s ease;
    }
</style>
@endsection

@section('content')
<div class="max-w-2xl mx-auto">

    {{-- Header --}}
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-lock text-blue-600 mr-3"></i>
                    Ganti Password
                </h1>
                <p class="text-gray-600 mt-1">
                    <i class="fas fa-info-circle mr-2 text-blue-500 text-sm"></i>
                    Gunakan password yang kuat dan unik
                </p>
            </div>
            <a href="{{ route('dosen.dashboard') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-xl transition-all text-gray-700">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>

    {{-- Error Alert --}}
    @if ($errors->any())
        <div class="bg-gradient-to-r from-red-50 to-red-100 border-l-4 border-red-500 p-5 mb-6 rounded-xl shadow-sm">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-semibold text-red-800">
                        Terdapat {{ $errors->count() }} kesalahan
                    </h3>
                    <ul class="mt-2 text-sm text-red-700 list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('dosen.password.update') }}">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-xl shadow-lg overflow-hidden profile-card">
            <div class="px-6 py-5 bg-gradient-to-r from-blue-50 to-white border-b border-blue-100">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-key mr-2 text-blue-600"></i>
                    Ubah Password Akun
                </h3>
            </div>

            <div class="p-6 space-y-6">

                {{-- Password Saat Ini --}}
                <div class="input-group">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-lock-open mr-2 text-blue-500"></i>
                        Password Saat Ini <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="password" name="current_password" id="current_password"
                               class="input-field w-full px-4 py-3 pr-12 border @error('current_password') border-red-400 @else border-gray-300 @enderror rounded-xl focus:ring-2 focus:ring-blue-400 focus:border-blue-500 transition-all"
                               placeholder="Masukkan password saat ini" autocomplete="current-password" required>
                        <button type="button"
                                onclick="toggleVisibility('current_password', 'icon-current')"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                            <i id="icon-current" class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('current_password')
                        <p class="mt-1.5 text-sm text-red-600">
                            <i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <hr class="border-gray-100">

                {{-- Password Baru --}}
                <div class="input-group">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-lock mr-2 text-blue-500"></i>
                        Password Baru <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="password" name="password" id="password"
                               class="input-field w-full px-4 py-3 pr-12 border @error('password') border-red-400 @else border-gray-300 @enderror rounded-xl focus:ring-2 focus:ring-blue-400 focus:border-blue-500 transition-all"
                               placeholder="Minimal 8 karakter" autocomplete="new-password"
                               oninput="checkStrength(this.value)" required>
                        <button type="button"
                                onclick="toggleVisibility('password', 'icon-new')"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                            <i id="icon-new" class="fas fa-eye"></i>
                        </button>
                    </div>
                    {{-- Password strength indicator --}}
                    <div class="mt-2">
                        <div class="flex gap-1 mb-1">
                            <div id="bar1" class="strength-bar flex-1 bg-gray-200"></div>
                            <div id="bar2" class="strength-bar flex-1 bg-gray-200"></div>
                            <div id="bar3" class="strength-bar flex-1 bg-gray-200"></div>
                            <div id="bar4" class="strength-bar flex-1 bg-gray-200"></div>
                        </div>
                        <p id="strength-label" class="text-xs text-gray-400">Ketik password untuk melihat kekuatan</p>
                    </div>
                    @error('password')
                        <p class="mt-1.5 text-sm text-red-600">
                            <i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Konfirmasi Password --}}
                <div class="input-group">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-check-double mr-2 text-blue-500"></i>
                        Konfirmasi Password Baru <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="password" name="password_confirmation" id="password_confirmation"
                               class="input-field w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-400 focus:border-blue-500 transition-all"
                               placeholder="Ulangi password baru" autocomplete="new-password"
                               oninput="checkMatch()" required>
                        <button type="button"
                                onclick="toggleVisibility('password_confirmation', 'icon-confirm')"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                            <i id="icon-confirm" class="fas fa-eye"></i>
                        </button>
                    </div>
                    <p id="match-label" class="text-xs text-gray-400 mt-1.5"></p>
                    @error('password_confirmation')
                        <p class="mt-1.5 text-sm text-red-600">
                            <i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Tips --}}
                <div class="p-4 bg-amber-50 rounded-xl border border-amber-100">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-lightbulb text-amber-500 mt-0.5"></i>
                        <div>
                            <p class="text-sm font-medium text-amber-800">Tips Password Kuat</p>
                            <ul class="text-xs text-amber-700 mt-1 space-y-0.5 list-disc pl-4">
                                <li>Minimal 8 karakter</li>
                                <li>Kombinasi huruf besar, huruf kecil, angka, dan simbol</li>
                                <li>Hindari menggunakan nama atau tanggal lahir</li>
                                <li>Jangan gunakan password yang sama dengan akun lain</li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex justify-end gap-4 mt-8 pb-8">
            <a href="{{ route('dosen.dashboard') }}"
               class="inline-flex items-center px-6 py-3 border-2 border-gray-300 shadow-sm text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 transition-all">
                <i class="fas fa-times mr-2"></i> Batal
            </a>
            <button type="submit"
                    class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-xl shadow-lg text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all transform hover:scale-105 btn-action">
                <i class="fas fa-save mr-2"></i> Simpan Password
            </button>
        </div>
    </form>

    {{-- ↓ Script di dalam @section('content') agar pasti ter-render --}}
    <script>
        function toggleVisibility(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon  = document.getElementById(iconId);

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        function checkStrength(val) {
            const bars       = [1, 2, 3, 4].map(i => document.getElementById('bar' + i));
            const label      = document.getElementById('strength-label');
            const colors     = { 1: 'bg-red-400', 2: 'bg-orange-400', 3: 'bg-yellow-400', 4: 'bg-green-500' };
            const labels     = { 1: 'Lemah', 2: 'Cukup', 3: 'Kuat', 4: 'Sangat Kuat' };
            const textColors = { 1: 'text-red-500', 2: 'text-orange-500', 3: 'text-yellow-600', 4: 'text-green-600' };

            let score = 0;
            if (val.length >= 8)           score++;
            if (/[A-Z]/.test(val))         score++;
            if (/[0-9]/.test(val))         score++;
            if (/[^A-Za-z0-9]/.test(val))  score++;

            bars.forEach((bar, i) => {
                bar.className = 'strength-bar flex-1 ' +
                    (score > 0 && i < score ? colors[score] : 'bg-gray-200');
            });

            label.className   = 'text-xs mt-0 ' + (score > 0 ? textColors[score] : 'text-gray-400');
            label.textContent = score > 0
                ? 'Kekuatan: ' + labels[score]
                : 'Ketik password untuk melihat kekuatan';
        }

        function checkMatch() {
            const pw      = document.getElementById('password').value;
            const confirm = document.getElementById('password_confirmation').value;
            const label   = document.getElementById('match-label');

            if (confirm === '') {
                label.textContent = '';
                return;
            }

            if (pw === confirm) {
                label.className   = 'text-xs mt-1.5 text-green-600';
                label.textContent = '✓ Password cocok';
            } else {
                label.className   = 'text-xs mt-1.5 text-red-500';
                label.textContent = '✗ Password tidak cocok';
            }
        }
    </script>

</div>
@endsection