@extends('layouts.admin')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    {{-- Judul + Tombol Tambah --}}
    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-blue-900">
                🎓 Daftar Data Siswa
            </h1>
            <p class="text-sm text-slate-600">
                Manajemen data siswa yang sudah terdaftar dan disetujui.
            </p>
        </div>
    </div>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="rounded-md bg-green-50 border border-green-300 px-4 py-3 text-sm font-semibold text-green-700">
            ✅ {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-md bg-red-50 border border-red-300 px-4 py-3 text-sm font-semibold text-red-700">
            ❌ {{ session('error') }}
        </div>
    @endif

    {{-- Card daftar siswa --}}
    <div class="bg-white shadow-sm rounded-2xl border border-slate-200 overflow-hidden">

        {{-- SEARCH & FILTER --}}
        <div class="px-4 py-4 border-b border-slate-200 bg-slate-50">
            <form method="GET" action="{{ route('admin.siswa.index') }}"
                  class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">

                {{-- Search --}}
                <div class="flex-1">
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Cari NIS / Nama / Kelas..."
                           class="w-full rounded-lg border border-slate-300 px-4 py-2 text-sm
                                  focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                </div>

                {{-- Filter Jurusan --}}
                <div class="w-full md:w-48">
                    <select name="jurusan"
                            class="w-full rounded-lg border border-slate-300 px-4 py-2 text-sm
                                   focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <option value="">Semua Jurusan</option>
                        <option value="TKJ" {{ request('jurusan') == 'TKJ' ? 'selected' : '' }}>TKJ</option>
                        <option value="TBSM" {{ request('jurusan') == 'RPL' ? 'selected' : '' }}>TBSM</option>
                    </select>
                </div>

                {{-- Tombol --}}
                <div class="flex gap-2">
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-semibold
                                   hover:bg-blue-700 transition">
                        Cari
                    </button>

                    @if(request('search') || request('jurusan'))
                        <a href="{{ route('admin.siswa.index') }}"
                           class="inline-flex items-center px-4 py-2 rounded-lg bg-slate-200 text-slate-700 text-sm font-semibold
                                  hover:bg-slate-300 transition">
                            Reset
                        </a>
                    @endif
                </div>

            </form>
        </div>

        {{-- Header tabel --}}
        <div class="px-4 py-3 border-b border-slate-200 flex items-center justify-between">
            <h2 class="text-sm font-semibold text-slate-700">List Data Siswa</h2>
            @if(isset($siswas))
                <span class="inline-flex items-center rounded-full bg-slate-50 px-3 py-1 text-xs font-medium text-slate-700">
                    Total: {{ $siswas->count() }} siswa
                </span>
            @endif
        </div>

        {{-- Tabel --}}
        <div class="overflow-x-auto">
            <table class="min-w-full text-xs md:text-sm text-left">
                <thead class="bg-[#0D47C9] text-white">
                    <tr class="border-b border-slate-200">
                        <th class="px-4 py-3 font-semibold uppercase tracking-wide text-white">NIS</th>
                        <th class="px-4 py-3 font-semibold uppercase tracking-wide text-white">Nama Siswa</th>
                        <th class="px-4 py-3 font-semibold uppercase tracking-wide text-white">Kelas</th>
                        <th class="px-4 py-3 font-semibold uppercase tracking-wide text-white">Jurusan</th>
                        <th class="px-4 py-3 font-semibold uppercase tracking-wide text-white">Username</th>
                        <th class="px-4 py-3 font-semibold uppercase tracking-wide text-white">Status</th>
                        <th class="px-4 py-3 font-semibold uppercase tracking-wide text-white text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse($siswas as $siswa)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3 whitespace-nowrap font-mono text-slate-800">
                                {{ $siswa->nis }}
                            </td>

                            <td class="px-4 py-3">
                                <div class="font-medium text-slate-900">
                                    {{ $siswa->nama }}
                                </div>
                            </td>

                            <td class="px-4 py-3 whitespace-nowrap text-slate-800 font-semibold">
                                {{ $siswa->kelas }}
                            </td>

                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                    {{ $siswa->jurusan === 'TKJ' ? 'bg-blue-100 text-blue-700 border border-blue-300' : 'bg-purple-100 text-purple-700 border border-purple-300' }}">
                                    {{ $siswa->jurusan }}
                                </span>
                            </td>

                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-800">
                                    {{ $siswa->user->username ?? 'N/A' }}
                                </span>
                            </td>

                            {{-- ✅ KOLOM STATUS - BADGE INDICATOR --}}
                            <td class="px-4 py-3">
                                @if($siswa->user)
                                    <span id="status-badge-{{ $siswa->id }}"
                                          class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium {{ $siswa->user->status === 'Aktif' ? 'bg-green-100 text-green-700 border border-green-300' : 'bg-red-100 text-red-700 border border-red-300' }}">
                                        <span id="status-dot-{{ $siswa->id }}" class="w-2 h-2 rounded-full {{ $siswa->user->status === 'Aktif' ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                        <span id="status-text-{{ $siswa->id }}">{{ $siswa->user->status }}</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-gray-100 text-gray-500 text-xs font-medium">
                                        No Account
                                    </span>
                                @endif
                            </td>

                            {{-- ✅ BAGIAN AKSI --}}
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-2">

                                    {{-- Tombol Toggle Status --}}
                                    @if($siswa->user)
                                        <button type="button"
                                                onclick="toggleStatus({{ $siswa->id }}, '{{ $siswa->user->status }}')"
                                                id="toggle-btn-{{ $siswa->id }}"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg transition-colors duration-200 {{ $siswa->user->status === 'Aktif' ? 'bg-amber-50 text-amber-600 hover:bg-amber-100' : 'bg-green-50 text-green-600 hover:bg-green-100' }}"
                                                title="{{ $siswa->user->status === 'Aktif' ? 'Nonaktifkan Akun' : 'Aktifkan Akun' }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                            </svg>
                                        </button>
                                    @endif

                                    {{-- Tombol Edit --}}
                                    <a href="{{ route('admin.siswa.edit', $siswa->id) }}"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 hover:text-blue-700 transition-colors duration-200"
                                       title="Edit Siswa">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>

                                    {{-- Tombol Hapus --}}
                                    <form id="delete-siswa-{{ $siswa->id }}"
                                          action="{{ route('admin.siswa.destroy', $siswa->id) }}"
                                          method="POST"
                                          class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        
                                        <button type="button"
                                                class="btn-confirm inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 hover:text-red-700 transition-colors duration-200"
                                                title="Hapus Siswa"
                                                data-form-id="delete-siswa-{{ $siswa->id }}"
                                                data-title="Hapus Data Siswa?"
                                                data-message="Hapus data siswa {{ $siswa->nama }} (NIS: {{ $siswa->nis }}, Kelas: {{ $siswa->kelas }} {{ $siswa->jurusan }})? Akun login siswa juga akan dihapus."
                                                data-btn-ok="Hapus"
                                                data-btn-color="rose">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center px-4 py-6 text-slate-500">
                                Belum ada data siswa yang terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if(isset($siswas) && $siswas->hasPages())
            <div class="px-4 py-3 border-t border-slate-200">
                {{ $siswas->links() }}
            </div>
        @endif
    </div>

</div>

{{-- INCLUDE MODAL KONFIRMASI --}}
@include('components.modal-confirmation')


{{-- ✅ NOTIFIKASI TOAST (STYLE PRESENSI) --}}
<div id="toast-notification"
     class="fixed top-20 left-1/2 transform -translate-x-1/2
            px-6 py-4 rounded-xl shadow-xl z-50 hidden transition-all duration-300
            bg-white border flex items-center gap-4"
     style="min-width: 320px;">

    {{-- ICON --}}
    <div id="toast-icon-wrapper"
         class="flex items-center justify-center w-9 h-9 rounded-full">
        <svg id="toast-icon"
             class="w-5 h-5"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
        </svg>
    </div>

    {{-- MESSAGE --}}
    <span id="toast-message"
          class="text-sm font-semibold text-slate-700"></span>
</div>

{{-- ✅ JAVASCRIPT UNTUK TOGGLE STATUS --}}
<script>
function toggleStatus(siswaId, currentStatus) {
    const toggleBtn = document.getElementById(`toggle-btn-${siswaId}`);
    const statusBadge = document.getElementById(`status-badge-${siswaId}`);
    const statusText = document.getElementById(`status-text-${siswaId}`);
    const statusDot = document.getElementById(`status-dot-${siswaId}`);
    
    if (!toggleBtn || !statusBadge || !statusText || !statusDot) {
        console.error('Element tidak ditemukan untuk siswa ID:', siswaId);
        return;
    }
    
    // Disable button saat proses
    toggleBtn.disabled = true;
    toggleBtn.classList.add('opacity-50', 'cursor-not-allowed');
    
    fetch(`/admin/siswa/${siswaId}/toggle-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const newStatus = data.status;
            
            // Update status text
            statusText.textContent = newStatus;
            
            // Update status badge
            statusBadge.className = 'inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium';
            if (newStatus === 'Aktif') {
                statusBadge.classList.add('bg-green-100', 'text-green-700', 'border', 'border-green-300');
                statusDot.className = 'w-2 h-2 rounded-full bg-green-500';
            } else {
                statusBadge.classList.add('bg-red-100', 'text-red-700', 'border', 'border-red-300');
                statusDot.className = 'w-2 h-2 rounded-full bg-red-500';
            }
            
            // Update toggle button
            toggleBtn.className = 'inline-flex items-center justify-center w-8 h-8 rounded-lg transition-colors duration-200';
            if (newStatus === 'Aktif') {
                toggleBtn.classList.add('bg-amber-50', 'text-amber-600', 'hover:bg-amber-100');
                toggleBtn.title = 'Nonaktifkan Akun';
            } else {
                toggleBtn.classList.add('bg-green-50', 'text-green-600', 'hover:bg-green-100');
                toggleBtn.title = 'Aktifkan Akun';
            }
            
            // Show success notification
            showToast(data.message, 'success');
        } else {
            showToast(data.message || 'Terjadi kesalahan', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Gagal mengubah status akun', 'error');
    })
    .finally(() => {
        // Enable button kembali
        toggleBtn.disabled = false;
        toggleBtn.classList.remove('opacity-50', 'cursor-not-allowed');
    });
}

function showToast(message, type) {
    const toast = document.getElementById('toast-notification');
    const toastMessage = document.getElementById('toast-message');
    const toastIcon = document.getElementById('toast-icon');
    const toastIconWrapper = document.getElementById('toast-icon-wrapper');

    if (!toast || !toastMessage || !toastIcon || !toastIconWrapper) {
        console.error('Toast elements tidak ditemukan');
        return;
    }

    toastMessage.textContent = message;

    // RESET CLASS DASAR
    toast.className = 'fixed top-20 left-1/2 transform -translate-x-1/2 px-6 py-4 rounded-xl shadow-xl z-50 transition-all duration-300 bg-white border flex items-center gap-4';

    if (type === 'success') {
        toast.classList.add('border-green-300');
        toastIconWrapper.className = 'flex items-center justify-center w-9 h-9 rounded-full bg-green-100';
        toastIcon.className = 'w-5 h-5 text-green-600';
        toastIcon.innerHTML =
            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>';
    } else {
        toast.classList.add('border-red-300');
        toastIconWrapper.className = 'flex items-center justify-center w-9 h-9 rounded-full bg-red-100';
        toastIcon.className = 'w-5 h-5 text-red-600';
        toastIcon.innerHTML =
            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>';
    }

    // SHOW
    toast.classList.remove('hidden');
    toast.style.opacity = '0';
    toast.style.transform = 'translate(-50%, -20px)';

    setTimeout(() => {
        toast.style.opacity = '1';
        toast.style.transform = 'translate(-50%, 0)';
    }, 10);

    // HIDE
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translate(-50%, -20px)';
        setTimeout(() => toast.classList.add('hidden'), 300);
    }, 3000);
}
</script>

@endsection