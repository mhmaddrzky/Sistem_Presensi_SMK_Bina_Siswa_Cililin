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
                    <span class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-xs font-medium text-blue-700">
                        Total: {{ $registrations->count() }} siswa
                    </span>
                </div>

                {{-- WRAPPER UNTUK SCROLL DI HP --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left">
                        <thead class="bg-slate-50">
                            <tr class="border-b border-slate-200">
                                <th class="px-4 py-3 font-semibold text-slate-700 text-xs uppercase tracking-wide">ID Reg</th>
                                <th class="px-4 py-3 font-semibold text-slate-700 text-xs uppercase tracking-wide">Nama Siswa</th>
                                <th class="px-4 py-3 font-semibold text-slate-700 text-xs uppercase tracking-wide">NIS</th>
                                <th class="px-4 py-3 font-semibold text-slate-700 text-xs uppercase tracking-wide">Kelas</th>
                                <th class="px-4 py-3 font-semibold text-slate-700 text-xs uppercase tracking-wide">Jurusan</th>
                                <th class="px-4 py-3 font-semibold text-slate-700 text-xs uppercase tracking-wide">Tanggal Daftar</th>
                                <th class="px-4 py-3 font-semibold text-slate-700 text-xs uppercase tracking-wide">Username Diminta</th>
                                <th class="px-4 py-3 font-semibold text-slate-700 text-xs uppercase tracking-wide text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @foreach ($registrations as $reg)
                                <tr class="hover:bg-slate-50/80">
                                    <td class="px-4 py-3 text-slate-800">
                                        {{ $reg->id_reg }}
                                    </td>

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

                                    <td class="px-4 py-3">
                                        <div class="flex flex-wrap gap-2 justify-center">

                                            {{-- FORM SETUJUI --}}
                                            <form method="POST"
                                                  action="{{ route('admin.registrations.approve', $reg->id_reg) }}">
                                                @csrf
                                                <button type="submit"
                                                        onclick="return confirm('Setujui registrasi {{ $reg->siswa->nama }} ({{ $reg->siswa->jurusan }})? Akun dibuat dengan Username: {{ $reg->username_request }}.')"
                                                        class="inline-flex items-center rounded-lg px-3 py-1.5 text-xs font-semibold
                                                               bg-emerald-600 text-white shadow-sm
                                                               hover:bg-emerald-700
                                                               focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-1">
                                                    Setujui
                                                </button>
                                            </form>

                                            {{-- FORM TOLAK --}}
                                            <form method="POST"
                                                  action="{{ route('admin.registrations.reject', $reg->id_reg) }}">
                                                @csrf
                                                <button type="submit"
                                                        onclick="return confirm('Tolak dan hapus data registrasi {{ $reg->siswa->nama }}?')"
                                                        class="inline-flex items-center rounded-lg px-3 py-1.5 text-xs font-semibold
                                                               bg-rose-600 text-white shadow-sm
                                                               hover:bg-rose-700
                                                               focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-1">
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
@endsection
