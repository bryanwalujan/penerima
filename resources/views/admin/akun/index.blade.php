@extends('layouts.admin.app')

@section('title', 'Manajemen Akun')
@section('header-title', 'Manajemen Akun')

@section('content')

    {{-- Alert --}}
    @if (session('success'))
        <div class="mb-5 flex items-center gap-3 rounded-lg border border-green-200 bg-green-50 px-5 py-4 text-green-800 shadow-sm">
            <i class="fas fa-check-circle text-green-500 flex-shrink-0"></i>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif
    @if (session('info'))
        <div class="mb-5 flex items-center gap-3 rounded-lg border border-blue-200 bg-blue-50 px-5 py-4 text-blue-800 shadow-sm">
            <i class="fas fa-info-circle text-blue-500 flex-shrink-0"></i>
            <span class="text-sm font-medium">{{ session('info') }}</span>
        </div>
    @endif

    {{-- Stats ringkas --}}
    @php
        $totalAdmin = $users->where('role', 'admin')->count();
        $totalDosen = $users->where('role', 'dosen')->count();
    @endphp
    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-5 py-4 flex items-center gap-4">
            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100">
                <i class="fas fa-users text-blue-700"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500">Total Akun</p>
                <p class="text-xl font-bold text-gray-800">{{ $users->count() }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-5 py-4 flex items-center gap-4">
            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-purple-100">
                <i class="fas fa-user-shield text-purple-700"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500">Admin</p>
                <p class="text-xl font-bold text-gray-800">{{ $totalAdmin }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-5 py-4 flex items-center gap-4">
            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-green-100">
                <i class="fas fa-user-tie text-green-700"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500">Dosen</p>
                <p class="text-xl font-bold text-gray-800">{{ $totalDosen }}</p>
            </div>
        </div>
    </div>

    {{-- Tabel akun --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fas fa-id-badge text-blue-600"></i>
                <h2 class="font-semibold text-gray-800">Daftar Akun Terdaftar</h2>
            </div>
            {{-- Search --}}
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 pointer-events-none">
                    <i class="fas fa-search text-xs"></i>
                </span>
                <input
                    type="text"
                    id="search-akun"
                    placeholder="Cari nama atau email..."
                    onkeyup="filterTable()"
                    class="text-sm pl-8 pr-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 w-56"
                >
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm" id="tabel-akun">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-3 text-left">#</th>
                        <th class="px-6 py-3 text-left">Nama</th>
                        <th class="px-6 py-3 text-left">Email</th>
                        <th class="px-6 py-3 text-left">NIDN</th>
                        <th class="px-6 py-3 text-left">Role</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($users as $i => $user)
                        <tr class="hover:bg-gray-50 transition-colors akun-row">
                            <td class="px-6 py-4 text-gray-400">{{ $i + 1 }}</td>

                            {{-- Nama + avatar --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex-shrink-0 h-8 w-8 rounded-full flex items-center justify-center text-white text-xs font-bold
                                        {{ $user->role === 'admin' ? 'bg-purple-500' : 'bg-blue-600' }}">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <span class="font-medium text-gray-800 akun-nama">{{ $user->name }}</span>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-gray-600 akun-email">{{ $user->email }}</td>

                            {{-- NIDN dari data dosen --}}
                            <td class="px-6 py-4 text-gray-500">
                                {{ $user->dosen?->nidn ?? '—' }}
                            </td>

                            {{-- Badge role --}}
                            <td class="px-6 py-4">
                                @if ($user->role === 'admin')
                                    <span class="inline-flex items-center gap-1 rounded-full bg-purple-100 px-2.5 py-0.5 text-xs font-medium text-purple-700">
                                        <i class="fas fa-user-shield text-[10px]"></i> Admin
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-700">
                                        <i class="fas fa-user-tie text-[10px]"></i> Dosen
                                    </span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="px-6 py-4 text-center">
                                @if ($user->id === Auth::guard('web')->id())
                                    <span class="text-xs text-gray-400 italic">Akun Anda</span>
                                @else
                                    <a href="{{ route('admin.akun.reset-password.edit', $user->id) }}"
                                       class="inline-flex items-center gap-1.5 rounded-lg border border-amber-300 bg-amber-50 px-3 py-1.5 text-xs font-medium text-amber-700 hover:bg-amber-100 transition-colors">
                                        <i class="fas fa-key text-[10px]"></i> Reset Password
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-400">
                                <i class="fas fa-users-slash text-2xl mb-2 block"></i>
                                Belum ada akun terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection

@section('scripts')
<script>
    function filterTable() {
        const q     = document.getElementById('search-akun').value.toLowerCase();
        const rows  = document.querySelectorAll('.akun-row');
        rows.forEach(row => {
            const nama  = row.querySelector('.akun-nama')?.textContent.toLowerCase()  ?? '';
            const email = row.querySelector('.akun-email')?.textContent.toLowerCase() ?? '';
            row.style.display = (nama.includes(q) || email.includes(q)) ? '' : 'none';
        });
    }
</script>
@endsection