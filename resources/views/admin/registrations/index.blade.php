@extends('layouts.admin')

@section('content')
    <div class="space-y-6">

        {{-- JUDUL HALAMAN --}}
        <div>
            <h1 class="text-2xl font-bold text-blue-900">
                Daftar Permintaan Registrasi Siswa (Pending)
            </h1>
            <p class="mt-1 text-sm text-slate-600">
                Kelola permintaan registrasi akun siswa yang masih menunggu persetujuan.
            </p>
        </div>

        {{-- NOTIFIKASI --}}
        @if(session('success'))
            <div class="rounded-md border border-emerald-300 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 font-semibold">
                ✅ {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-md border border-rose-300 bg-rose-50 px-4 py-3 text-sm text-rose-800 font-semibold">
                ❌ {{ session('error') }}
            </div>
        @endif

        @if($registrations->isEmpty())
            <div class="rounded-lg border border-dashed border-slate-300 bg-white px-6 py-10 text-center">
                <p class="text-sm text-slate-500">
                    Tidak ada permintaan registrasi yang menunggu persetujuan.
                </p>
            </div>
        @else
            {{-- CARD TABEL --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-200">
                <div class="px-4 py-3 border-b border-slate-200 flex items-center justify-between">
                    <h2 class="text-sm font-semibold text-slate-700">
                        List Pending Registrasi
                    </h2>

                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-xs font-medium text-blue-700">
                            Total: {{ $registrations->count() }} siswa
                        </span>

                        {{-- FORM SETUJUI SEMUA --}}
                        @php
                            $canApproveAny = $registrations->count() > 0;
                        @endphp

                        <form id="approve-all-form"
                              method="POST"
                              action="{{ route('admin.registrations.approveAll') }}">
                            @csrf

                            <button
                                type="button"
                                class="btn-confirm inline-flex items-center rounded-lg px-3 py-1.5 text-xs font-semibold
                                       bg-emerald-600 text-white shadow-sm
                                       hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-1
                                       transition disabled:opacity-50 disabled:cursor-not-allowed"
                                data-form-id="approve-all-form"
                                data-type="approve"
                                data-nama="semua registrasi"
                                @if(! $canApproveAny) disabled aria-disabled="true" @endif>
                                Setujui Semua
                            </button>
                        </form>
                    </div>
                </div>

                {{-- WRAPPER UNTUK SCROLL DI HP --}}
                <div class="overflow-x-auto">
    <table class="min-w-full text-sm text-left">
        <thead class="bg-[#0D47C9] text-white" >
            <tr class="border-b border-slate-200">
                <th class="px-4 py-3 font-semibold text-slate-700 text-xs uppercase tracking-wide text-white">No</th>
                {{--<th class="px-4 py-3 font-semibold text-slate-700 text-xs uppercase tracking-wide">ID Reg</th>--}}
                <th class="px-4 py-3 font-semibold text-slate-700 text-xs uppercase tracking-wide text-white">Nama Siswa</th>
                <th class="px-4 py-3 font-semibold text-slate-700 text-xs uppercase tracking-wide text-white">NIS</th>
                <th class="px-4 py-3 font-semibold text-slate-700 text-xs uppercase tracking-wide text-white">Kelas</th>
                <th class="px-4 py-3 font-semibold text-slate-700 text-xs uppercase tracking-wide text-white">Jurusan</th>
                <th class="px-4 py-3 font-semibold text-slate-700 text-xs uppercase tracking-wide text-white">Tanggal Daftar</th>
                <th class="px-4 py-3 font-semibold text-slate-700 text-xs uppercase tracking-wide text-white">Username Diminta</th>
                <th class="px-4 py-3 font-semibold text-slate-700 text-xs uppercase tracking-wide text-white text-center">Aksi</th>
            </tr>
        </thead>

        <tbody class="divide-y divide-slate-100">
            @foreach ($registrations as $reg)
                <tr class="hover:bg-slate-50/80">
                    {{-- NO URUT --}}
                    <td class="px-4 py-3 text-slate-800">
                        {{ $loop->iteration }}
                    </td>

                    {{-- ID REG ASLI --}}
                    {{-- <td class="px-4 py-3 text-slate-800">
                        {{ $reg->id_reg }}
                    </td>- --}}

                    <td class="px-4 py-3">
                        <div class="text-slate-900 font-medium">
                            {{ $reg->siswa->nama }}
                        </div>
                        <div class="text-[11px] text-slate-500">
                            ID Siswa: {{ $reg->siswa->nis }}
                        </div>
                    </td>

                    <td class="px-4 py-3 text-slate-800">
                        {{ $reg->siswa->nis }}
                    </td>

                    <td class="px-4 py-3 text-slate-800">
                        {{ $reg->siswa->kelas }}
                    </td>

                    <td class="px-4 py-3 font-semibold text-blue-900">
                        {{ $reg->siswa->jurusan }}
                    </td>

                    <td class="px-4 py-3 text-slate-800">
                        {{ $reg->tanggal_reg }}
                    </td>

                    <td class="px-4 py-3">
                        <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-800">
                            {{ $reg->username_request }}
                        </span>
                    </td>

                    {{-- AKSI --}}
                                    <td class="px-4 py-3">
                                        <div class="flex flex-wrap gap-2 justify-center">
                                            {{-- SETUJUI --}}
                                            <form id="approve-form-{{ $reg->id_reg }}"
                                                  method="POST"
                                                  action="{{ route('admin.registrations.approve', $reg->id_reg) }}">
                                                @csrf
                                                <button type="button"
                                                        class="btn-confirm inline-flex items-center rounded-lg px-3 py-1.5 text-xs font-semibold
                                                               bg-emerald-600 text-white shadow-sm
                                                               hover:bg-emerald-700
                                                               focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-1"
                                                        data-form-id="approve-form-{{ $reg->id_reg }}"
                                                        data-type="approve"
                                                        data-nama="{{ $reg->siswa->nama }}"
                                                        data-jurusan="{{ $reg->siswa->jurusan }}"
                                                        data-username="{{ $reg->username_request }}">
                                                    Setujui
                                                </button>
                                            </form>

                                            {{-- TOLAK --}}
                                            <form id="reject-form-{{ $reg->id_reg }}"
                                                  method="POST"
                                                  action="{{ route('admin.registrations.reject', $reg->id_reg) }}">
                                                @csrf
                                                <button type="button"
                                                        class="btn-confirm inline-flex items-center rounded-lg px-3 py-1.5 text-xs font-semibold
                                                               bg-rose-600 text-white shadow-sm
                                                               hover:bg-rose-700
                                                               focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-1"
                                                        data-form-id="reject-form-{{ $reg->id_reg }}"
                                                        data-type="reject"
                                                        data-nama="{{ $reg->siswa->nama }}">
                                                    Tolak
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>

    {{-- MODAL KONFIRMASI RESPONSIF --}}
    <div id="confirm-modal"
         class="fixed inset-0 z-40 hidden items-center justify-center bg-slate-900/60">
        <div class="mx-4 w-full max-w-sm rounded-2xl bg-white p-5 shadow-lg">
            <h3 id="confirm-title" class="text-base font-semibold text-slate-900 mb-2">
                Konfirmasi
            </h3>
            <p id="confirm-message" class="text-sm text-slate-600 mb-4">
                Apakah Anda yakin?
            </p>
            <div class="flex justify-end gap-2">
                <button type="button"
                        id="confirm-cancel"
                        class="px-3 py-1.5 text-sm rounded-lg border border-slate-300 text-slate-700">
                    Batal
                </button>
                <button type="button"
                        id="confirm-ok"
                        class="px-3 py-1.5 text-sm rounded-lg bg-emerald-600 text-white">
                    OK
                </button>
            </div>
        </div>
    </div>

    {{-- SCRIPT UNTUK MODAL --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal      = document.getElementById('confirm-modal');
            const titleEl    = document.getElementById('confirm-title');
            const msgEl      = document.getElementById('confirm-message');
            const btnOk      = document.getElementById('confirm-ok');
            const btnCancel  = document.getElementById('confirm-cancel');

            let currentFormId = null;

            // Buka modal saat tombol Setujui / Tolak diklik
            document.querySelectorAll('.btn-confirm').forEach(btn => {
                btn.addEventListener('click', () => {
                    const type     = btn.dataset.type;       // approve / reject
                    const nama     = btn.dataset.nama;
                    const jurusan  = btn.dataset.jurusan || '';
                    const username = btn.dataset.username || '';
                    currentFormId  = btn.dataset.formId;

                    if (type === 'approve') {
                        titleEl.textContent = 'Setujui Registrasi?';
                        msgEl.textContent   =
                            `Setujui registrasi ${nama}${jurusan ? ' (' + jurusan + ')' : ''}? ` +
                            (username ? `Akun dibuat dengan Username: ${username}.` : '');
                    } else {
                        titleEl.textContent = 'Tolak Registrasi?';
                        msgEl.textContent   =
                            `Tolak dan hapus data registrasi ${nama}?`;
                    }

                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                });
            });

            // Tombol batal
            btnCancel.addEventListener('click', () => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                currentFormId = null;
            });

            // Tombol OK
            btnOk.addEventListener('click', () => {
                if (currentFormId) {
                    const form = document.getElementById(currentFormId);
                    if (form) form.submit();
                }
            });

            // Klik di luar card modal untuk menutup
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                    currentFormId = null;
                }
            });
        });
    </script>
@endsection
