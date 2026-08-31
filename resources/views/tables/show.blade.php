@extends('layouts.app')

@section('title', $tableInfo['title'])
@section('page_title', $tableInfo['title'])

@section('content')
<div class="max-w-7xl mx-auto space-y-6" x-data="{
        selectedRecord: null,
        modalOpen: false,
        createOpen: {{ (isset($errors) && $errors->any() && old('_token')) ? 'true' : 'false' }},
        editOpen: false,
        editRecord: {},
        deleteOpen: false,
        deleteRecord: null,
        openDetail(record) {
            this.selectedRecord = record;
            this.modalOpen = true;
        },
        openEdit(record) {
            this.editRecord = Object.assign({}, record);
            this.editOpen = true;
        },
        openDelete(id, label) {
            this.deleteRecord = { id: id, label: label };
            this.deleteOpen = true;
        }
    }">

    <!-- HEADER SECTION (Matches Screenshot 2 & 3) -->
    <div class="bg-white dark:bg-[#0e1626] rounded-2xl p-5 sm:p-6 border border-slate-200/80 dark:border-slate-800/80 shadow-2xs flex flex-col md:flex-row md:items-center justify-between gap-4 transition-colors">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 dark:bg-indigo-950/50 border border-indigo-100 dark:border-indigo-800/60 text-[#4f46e5] dark:text-indigo-400 flex items-center justify-center text-xl shadow-xs flex-shrink-0">
                <i class="fa-solid {{ $tableInfo['icon'] }}"></i>
            </div>
            <div>
                <div class="flex items-center gap-2.5 flex-wrap">
                    <h2 class="text-xl sm:text-2xl font-black text-slate-800 dark:text-white tracking-tight">{{ $tableInfo['title'] }}</h2>
                    <span class="px-2.5 py-0.5 rounded-full bg-indigo-50 dark:bg-indigo-950/60 border border-indigo-200 dark:border-indigo-800/60 text-indigo-700 dark:text-indigo-300 text-xs font-bold">
                        {{ $records->total() }} Data
                    </span>
                </div>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ $tableInfo['desc'] }}</p>
            </div>
        </div>

        <!-- SEARCH BAR + TAMBAH DATA BUTTON -->
        <div class="flex items-center gap-3 w-full md:w-auto">
            <form method="GET" action="{{ route('table.show', $table) }}" class="flex items-center gap-2 flex-1 md:flex-none">
                <div class="relative flex-1 md:w-64">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" 
                           name="search" 
                           value="{{ $search }}" 
                           placeholder="Cari dalam tabel ini..." 
                           class="w-full pl-9 pr-8 py-2 bg-slate-50 dark:bg-[#131d31] border border-slate-200 dark:border-slate-700/80 rounded-xl text-xs sm:text-sm text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-[#18233a] transition-all">
                    @if($search)
                        <a href="{{ route('table.show', $table) }}" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                            <i class="fa-solid fa-xmark text-xs"></i>
                        </a>
                    @endif
                </div>
            </form>

            <button type="button" 
                    @click="createOpen = true"
                    class="px-4 py-2 bg-[#4f46e5] hover:bg-indigo-700 active:scale-[0.98] text-white rounded-xl text-xs font-bold transition-all shadow-md shadow-indigo-500/20 inline-flex items-center gap-1.5 whitespace-nowrap cursor-pointer">
                <i class="fa-solid fa-plus"></i>
                <span>Tambah {{ $tableInfo['title'] }}</span>
            </button>
        </div>
    </div>

    <!-- MAIN TABLE CARD -->
    <div class="bg-white dark:bg-[#0e1626] rounded-2xl border border-slate-200/80 dark:border-slate-800/80 shadow-2xs overflow-hidden transition-colors">
        
        @if($records->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 dark:bg-[#121c2e] border-b border-slate-200 dark:border-slate-800 text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-400">
                            <th class="py-3.5 px-4 sm:px-6 w-16">#ID</th>
                            
                            @if($table === 'alumni')
                                <th class="py-3.5 px-4">FOTO & NAMA ALUMNI</th>
                                <th class="py-3.5 px-4">ANGKATAN & KELAS</th>
                                <th class="py-3.5 px-4">SOSIAL MEDIA</th>
                                <th class="py-3.5 px-4">PEKERJAAN SAAT INI</th>
                            @elseif($table === 'angkatan')
                                <th class="py-3.5 px-4">TAHUN ANGKATAN</th>
                                <th class="py-3.5 px-4">NAMA / JULUKAN ANGKATAN</th>
                                <th class="py-3.5 px-4">TOTAL KELAS</th>
                                <th class="py-3.5 px-4">TOTAL ALUMNI</th>
                            @elseif($table === 'kelas')
                                <th class="py-3.5 px-4">NAMA KELAS</th>
                                <th class="py-3.5 px-4">TAHUN ANGKATAN</th>
                                <th class="py-3.5 px-4">TOTAL SISWA TERDATA</th>
                            @elseif($table === 'users')
                                <th class="py-3.5 px-4">USERNAME</th>
                                <th class="py-3.5 px-4">EMAIL</th>
                                <th class="py-3.5 px-4">ROLE AKSES</th>
                                <th class="py-3.5 px-4">STATUS ALUMNI</th>
                            @elseif($table === 'periode_kepengurusan')
                                <th class="py-3.5 px-4">NAMA PERIODE</th>
                                <th class="py-3.5 px-4">TANGGAL MULAI</th>
                                <th class="py-3.5 px-4">TANGGAL SELESAI</th>
                                <th class="py-3.5 px-4">TOTAL PENGURUS</th>
                            @elseif($table === 'pengurus_alumni')
                                <th class="py-3.5 px-4">NAMA ALUMNI</th>
                                <th class="py-3.5 px-4">JABATAN ORGANISASI</th>
                                <th class="py-3.5 px-4">PERIODE KEPENGURUSAN</th>
                            @elseif($table === 'prestasi_alumni')
                                <th class="py-3.5 px-4">NAMA PRESTASI & PENGHARGAAN</th>
                                <th class="py-3.5 px-4">PEMENANG (ALUMNI)</th>
                                <th class="py-3.5 px-4">TINGKAT & TAHUN</th>
                            @elseif($table === 'artikels')
                                <th class="py-3.5 px-4">COVER</th>
                                <th class="py-3.5 px-4">JUDUL ARTIKEL</th>
                                <th class="py-3.5 px-4">STATUS</th>
                                <th class="py-3.5 px-4">AUTHOR</th>
                            @elseif($table === 'acara')
                                <th class="py-3.5 px-4">BANNER</th>
                                <th class="py-3.5 px-4">NAMA ACARA</th>
                                <th class="py-3.5 px-4">JADWAL PELAKSANAAN</th>
                                <th class="py-3.5 px-4">LOKASI</th>
                            @elseif($table === 'albums')
                                <th class="py-3.5 px-4">COVER</th>
                                <th class="py-3.5 px-4">NAMA ALBUM</th>
                                <th class="py-3.5 px-4">DESKRIPSI</th>
                                <th class="py-3.5 px-4">JUMLAH FOTO</th>
                            @elseif($table === 'galleries')
                                <th class="py-3.5 px-4">PREVIEW FOTO</th>
                                <th class="py-3.5 px-4">ALBUM</th>
                                <th class="py-3.5 px-4">CAPTION FOTO</th>
                            @elseif($table === 'testimonies')
                                <th class="py-3.5 px-4">ALUMNI</th>
                                <th class="py-3.5 px-4">PESAN TESTIMONI</th>
                                <th class="py-3.5 px-4">STATUS</th>
                            @elseif($table === 'contents')
                                <th class="py-3.5 px-4">KEY IDENTIFIER</th>
                                <th class="py-3.5 px-4">JUDUL KONTEN</th>
                                <th class="py-3.5 px-4">ISI KONTEN RINGKAS</th>
                            @endif

                            <th class="py-3.5 px-4 text-center w-28">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/70 text-xs sm:text-sm text-slate-700 dark:text-slate-200">
                        @foreach($records as $row)
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-[#131d31] transition-colors">
                                <!-- ID -->
                                <td class="py-4 px-4 sm:px-6 font-mono text-xs font-semibold text-slate-400 dark:text-slate-500">
                                    #{{ $row->id }}
                                </td>

                                <!-- 1. ALUMNI (Screenshot 2 Match) -->
                                @if($table === 'alumni')
                                    <td class="py-4 px-4">
                                        <div class="flex items-center gap-3">
                                            <img src="{{ $row->foto_profil }}" alt="{{ $row->nama_lengkap }}" class="w-10 h-10 rounded-full object-cover ring-1 ring-slate-200 dark:ring-slate-700 shadow-2xs flex-shrink-0">
                                            <div>
                                                <div class="font-bold text-slate-900 dark:text-white leading-tight">{{ $row->nama_lengkap }}</div>
                                                <div class="text-[11px] text-slate-400 dark:text-slate-400 mt-0.5 font-normal">NISN: {{ $row->nisn ?? '-' }} &bull; {{ $row->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4">
                                        <div class="font-bold text-slate-900 dark:text-white text-xs">{{ $row->angkatan->nama_angkatan ?? '-' }}</div>
                                        <div class="mt-1">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border border-blue-200/80 dark:border-blue-800/60">
                                                {{ $row->kelas->nama_kelas ?? '-' }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4">
                                        @if(!empty($row->sosial_media))
                                            <span class="text-xs font-medium text-slate-700 dark:text-slate-300">{{ $row->sosial_media }}</span>
                                        @else
                                            <span class="text-xs text-slate-400 dark:text-slate-500 italic">Belum diisi</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-4">
                                        @if($row->pekerjaan_saat_ini)
                                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold bg-slate-50 dark:bg-slate-800 border border-slate-200/90 dark:border-slate-700 text-slate-700 dark:text-slate-200 shadow-2xs">
                                                {{ $row->pekerjaan_saat_ini }}
                                            </span>
                                        @else
                                            <span class="text-xs text-slate-400 dark:text-slate-500 italic">Belum diisi</span>
                                        @endif
                                    </td>

                                <!-- 2. ANGKATAN -->
                                @elseif($table === 'angkatan')
                                    <td class="py-4 px-4">
                                        <span class="font-bold text-indigo-700 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-950/60 px-3 py-1 rounded-lg border border-indigo-200 dark:border-indigo-800">
                                            {{ $row->tahun_angkatan }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-4 font-bold text-slate-900 dark:text-white">{{ $row->nama_angkatan }}</td>
                                    <td class="py-4 px-4">
                                        <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $row->kelas_count }} Kelas</span>
                                    </td>
                                    <td class="py-4 px-4">
                                        <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $row->alumni_count }} Alumni</span>
                                    </td>

                                <!-- 3. KELAS (Screenshot 3 Match) -->
                                @elseif($table === 'kelas')
                                    <td class="py-4 px-4 font-bold text-slate-900 dark:text-white text-sm">{{ $row->nama_kelas }}</td>
                                    <td class="py-4 px-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold bg-amber-50/60 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 text-amber-700 dark:text-amber-300">
                                            {{ $row->angkatan->nama_angkatan ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-4">
                                        @if($row->alumni_count > 0)
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 dark:bg-indigo-950/60 border border-indigo-200 dark:border-indigo-800 text-indigo-600 dark:text-indigo-300">
                                                <i class="fa-solid fa-users text-[11px]"></i>
                                                <span>{{ $row->alumni_count }} Siswa Terdaftar</span>
                                                <i class="fa-solid fa-chevron-down text-[10px] ml-0.5"></i>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-400 dark:text-slate-500">
                                                <i class="fa-solid fa-users text-[11px]"></i>
                                                <span>0 Siswa Terdaftar</span>
                                            </span>
                                        @endif
                                    </td>

                                <!-- 4. USERS -->
                                @elseif($table === 'users')
                                    <td class="py-4 px-4 font-bold text-slate-900 dark:text-white">
                                        <div class="flex items-center gap-2">
                                            <i class="fa-regular fa-user text-slate-400"></i>
                                            <span>{{ $row->username }}</span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4 text-slate-600 dark:text-slate-300 font-mono text-xs">{{ $row->email }}</td>
                                    <td class="py-4 px-4">
                                        @if($row->role === 'admin')
                                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-purple-50 dark:bg-purple-950/60 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800">ADMIN</span>
                                        @elseif($row->role === 'moderator')
                                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800">MODERATOR</span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">USER</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-4">
                                        @if($row->alumni)
                                            <span class="text-xs text-emerald-600 dark:text-emerald-400 font-semibold flex items-center gap-1">
                                                <i class="fa-solid fa-check-circle"></i> {{ $row->alumni->nama_lengkap }}
                                            </span>
                                        @else
                                            <span class="text-xs text-slate-400 dark:text-slate-500 italic">Bukan Profil Alumni</span>
                                        @endif
                                    </td>

                                <!-- 5. PERIODE KEPENGURUSAN -->
                                @elseif($table === 'periode_kepengurusan')
                                    <td class="py-4 px-4 font-bold text-slate-900 dark:text-white">{{ $row->nama_periode }}</td>
                                    <td class="py-4 px-4 text-xs font-semibold text-slate-700 dark:text-slate-300">{{ $row->tanggal_mulai ? \Carbon\Carbon::parse($row->tanggal_mulai)->format('d M Y') : '-' }}</td>
                                    <td class="py-4 px-4 text-xs font-semibold text-slate-700 dark:text-slate-300">{{ $row->tanggal_selesai ? \Carbon\Carbon::parse($row->tanggal_selesai)->format('d M Y') : '-' }}</td>
                                    <td class="py-4 px-4">
                                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-teal-50 dark:bg-teal-950/60 text-teal-700 dark:text-teal-300 border border-teal-200 dark:border-teal-800">
                                            {{ $row->pengurus_count ?? 0 }} Pengurus
                                        </span>
                                    </td>

                                <!-- 6. PENGURUS ALUMNI -->
                                @elseif($table === 'pengurus_alumni')
                                    <td class="py-4 px-4">
                                        <div class="flex items-center gap-2.5">
                                            <img src="{{ $row->alumni->foto_profil ?? 'https://i.pravatar.cc/100' }}" alt="Alumni" class="w-9 h-9 rounded-full object-cover ring-1 ring-slate-200 dark:ring-slate-700">
                                            <div>
                                                <div class="font-bold text-slate-900 dark:text-white">{{ $row->alumni->nama_lengkap ?? '-' }}</div>
                                                <div class="text-[11px] text-slate-400">{{ $row->alumni->angkatan->nama_angkatan ?? '' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4">
                                        <span class="font-bold text-rose-700 dark:text-rose-300 bg-rose-50 dark:bg-rose-950/60 px-2.5 py-1 rounded-lg border border-rose-200/80 dark:border-rose-800 text-xs">
                                            {{ $row->jabatan }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-4 text-xs font-semibold text-slate-700 dark:text-slate-300">
                                        {{ $row->periode->nama_periode ?? '-' }}
                                    </td>

                                <!-- 7. PRESTASI ALUMNI -->
                                @elseif($table === 'prestasi_alumni')
                                    <td class="py-4 px-4">
                                        <div class="font-bold text-slate-900 dark:text-white leading-snug">{{ $row->nama_prestasi }}</div>
                                        <div class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 line-clamp-1">{{ $row->deskripsi }}</div>
                                    </td>
                                    <td class="py-4 px-4 font-semibold text-slate-800 dark:text-slate-200">{{ $row->alumni->nama_lengkap ?? '-' }}</td>
                                    <td class="py-4 px-4">
                                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                                            {{ $row->tingkat }} &bull; {{ $row->tahun_perolehan }}
                                        </span>
                                    </td>

                                <!-- 8. ARTIKELS -->
                                @elseif($table === 'artikels')
                                    <td class="py-4 px-4">
                                        <img src="{{ $row->gambar_utama }}" alt="Artikel" class="w-14 h-10 rounded-lg object-cover ring-1 ring-slate-200 dark:ring-slate-700 shadow-2xs">
                                    </td>
                                    <td class="py-4 px-4">
                                        <div class="font-bold text-slate-900 dark:text-white leading-snug">{{ $row->judul }}</div>
                                        <div class="text-[11px] text-slate-400 dark:text-slate-500 font-mono mt-0.5">slug: {{ $row->slug }}</div>
                                    </td>
                                    <td class="py-4 px-4">
                                        @if($row->status === 'published')
                                             <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">Published</span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800">Draft</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-4 text-xs font-medium text-slate-600 dark:text-slate-400">{{ $row->user->username ?? 'Admin' }}</td>

                                <!-- 9. ACARA -->
                                @elseif($table === 'acara')
                                    <td class="py-4 px-4">
                                        <img src="{{ $row->banner_image }}" alt="Event" class="w-14 h-10 rounded-lg object-cover ring-1 ring-slate-200 dark:ring-slate-700 shadow-2xs">
                                    </td>
                                    <td class="py-4 px-4 font-bold text-slate-900 dark:text-white">{{ $row->nama_acara ?? $row->nama_event }}</td>
                                    <td class="py-4 px-4 text-xs">
                                        <div class="font-semibold text-slate-800 dark:text-slate-200">
                                            {{ $row->tanggal_mulai ? \Carbon\Carbon::parse($row->tanggal_mulai)->format('d M Y, H:i') : '-' }}
                                        </div>
                                        <div class="text-slate-400 dark:text-slate-500 text-[11px]">
                                            s/d {{ $row->tanggal_selesai ? \Carbon\Carbon::parse($row->tanggal_selesai)->format('d M Y, H:i') : '-' }}
                                        </div>
                                    </td>
                                    <td class="py-4 px-4 text-slate-700 dark:text-slate-300 font-medium text-xs">{{ $row->lokasi }}</td>

                                <!-- 10. ALBUMS -->
                                @elseif($table === 'albums')
                                    <td class="py-4 px-4">
                                        <img src="{{ $row->cover_image }}" alt="Cover" class="w-14 h-10 rounded-lg object-cover ring-1 ring-slate-200 dark:ring-slate-700 shadow-2xs">
                                    </td>
                                    <td class="py-4 px-4 font-bold text-slate-900 dark:text-white">{{ $row->nama_album }}</td>
                                    <td class="py-4 px-4 text-slate-600 dark:text-slate-400 text-xs line-clamp-2 max-w-xs">{{ $row->deskripsi }}</td>
                                    <td class="py-4 px-4 font-semibold text-indigo-600 dark:text-indigo-400">{{ $row->galleries_count }} Foto</td>

                                <!-- 11. GALLERIES -->
                                @elseif($table === 'galleries')
                                    <td class="py-4 px-4">
                                        <img src="{{ $row->file_url }}" alt="Gallery" class="w-16 h-12 rounded-lg object-cover ring-1 ring-slate-200 dark:ring-slate-700 shadow-2xs">
                                    </td>
                                    <td class="py-4 px-4 font-semibold text-slate-900 dark:text-white">{{ $row->album->nama_album ?? '-' }}</td>
                                    <td class="py-4 px-4 text-slate-600 dark:text-slate-400 text-xs">{{ $row->caption }}</td>

                                <!-- 12. TESTIMONIES -->
                                @elseif($table === 'testimonies')
                                    <td class="py-4 px-4">
                                        <div class="flex items-center gap-2.5">
                                            <img src="{{ $row->alumni->foto_profil ?? 'https://i.pravatar.cc/100' }}" alt="Alumni" class="w-9 h-9 rounded-full object-cover">
                                            <div>
                                                <div class="font-bold text-slate-900 dark:text-white">{{ $row->alumni->nama_lengkap ?? '-' }}</div>
                                                <div class="text-[11px] text-slate-500">{{ $row->alumni->angkatan->nama_angkatan ?? '' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4 text-slate-600 dark:text-slate-400 text-xs italic max-w-sm">"{{ $row->pesan }}"</td>
                                    <td class="py-4 px-4">
                                        @if($row->status === 'approved')
                                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">Approved</span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800">Pending</span>
                                        @endif
                                    </td>

                                <!-- 13. CONTENTS -->
                                @elseif($table === 'contents')
                                    <td class="py-4 px-4 font-mono font-bold text-indigo-600 dark:text-indigo-400 text-xs">{{ $row->key_identifier }}</td>
                                    <td class="py-4 px-4 font-bold text-slate-900 dark:text-white">{{ $row->judul }}</td>
                                    <td class="py-4 px-4 text-slate-600 dark:text-slate-400 text-xs max-w-xs truncate">{{ $row->isi }}</td>
                                @endif

                                <!-- ACTION: DETAIL / EDIT / HAPUS (Matches Action Buttons in Screenshot 2 & 3) -->
                                @php
                                    $editData = ['id' => $row->id];
                                    foreach ($formFields as $f) {
                                        $val = $row->{$f['name']} ?? null;
                                        if ($f['type'] === 'datetime-local' && $val) {
                                            $val = \Illuminate\Support\Carbon::parse($val)->format('Y-m-d\TH:i');
                                        } elseif ($f['type'] === 'date' && $val) {
                                            $val = \Illuminate\Support\Carbon::parse($val)->format('Y-m-d');
                                        } elseif ($f['type'] === 'password') {
                                            $val = '';
                                        }
                                        $editData[$f['name']] = $val;
                                    }
                                    
                                    $labelField = [
                                        'alumni' => 'nama_lengkap', 'angkatan' => 'nama_angkatan', 'kelas' => 'nama_kelas',
                                        'users' => 'username', 'periode_kepengurusan' => 'nama_periode', 'pengurus_alumni' => 'jabatan',
                                        'prestasi_alumni' => 'nama_prestasi', 'artikels' => 'judul', 'acara' => 'nama_acara',
                                        'albums' => 'nama_album', 'galleries' => 'caption', 'testimonies' => 'pesan', 'contents' => 'judul',
                                    ][$table] ?? 'id';
                                    $deleteLabel = $row->{$labelField} ?? ('#' . $row->id);
                                @endphp
                                <td class="py-4 px-4 text-center">
                                    <div class="inline-flex items-center gap-1.5">
                                        <!-- View Button -->
                                        <button type="button" 
                                                @click="openDetail(@js($row->attributesToArray()))" 
                                                title="Lihat Detail"
                                                class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-blue-50 dark:hover:bg-blue-950/50 text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 flex items-center justify-center transition-colors cursor-pointer">
                                            <i class="fa-solid fa-eye text-xs"></i>
                                        </button>
                                        <!-- Edit Button -->
                                        <button type="button" 
                                                @click="openEdit(@js($editData))" 
                                                title="Edit Data"
                                                class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-950/40 hover:bg-amber-100 dark:hover:bg-amber-900/60 text-amber-600 dark:text-amber-400 flex items-center justify-center transition-colors cursor-pointer">
                                            <i class="fa-solid fa-pen text-xs"></i>
                                        </button>
                                        <!-- Delete Button -->
                                        <button type="button" 
                                                @click="openDelete({{ $row->id }}, @js(\Illuminate\Support\Str::limit($deleteLabel, 50)))" 
                                                title="Hapus Data"
                                                class="w-8 h-8 rounded-lg bg-rose-50 dark:bg-rose-950/40 hover:bg-rose-100 dark:hover:bg-rose-900/60 text-rose-600 dark:text-rose-400 flex items-center justify-center transition-colors cursor-pointer">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- PAGINATION -->
            <div class="p-4 sm:p-5 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-[#0e1626]">
                {{ $records->links() }}
            </div>

        @else
            <!-- EMPTY STATE -->
            <div class="py-16 text-center">
                <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 flex items-center justify-center text-2xl mx-auto mb-3">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
                <h4 class="font-bold text-slate-700 dark:text-slate-200 text-base">Tidak ada data ditemukan</h4>
                <p class="text-xs text-slate-400 dark:text-slate-500 mt-1 max-w-sm mx-auto">
                    @if($search)
                        Pencarian kata kunci "<span class="font-semibold text-slate-600 dark:text-slate-300">{{ $search }}</span>" tidak membuahkan hasil.
                    @else
                        Tabel ini saat ini masih belum memiliki data.
                    @endif
                </p>
                @if($search)
                    <a href="{{ route('table.show', $table) }}" class="inline-block mt-3 text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-800">
                        Reset Pencarian
                    </a>
                @endif
            </div>
        @endif

    </div>

    <!-- DETAIL RECORD MODAL -->
    <template x-teleport="body">
        <div x-cloak x-show="modalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 overflow-y-auto" role="dialog" aria-modal="true">
            <div x-show="modalOpen"
                 x-transition:enter="transition-opacity ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="modalOpen = false"
                 class="fixed inset-0 bg-slate-950/80 transition-opacity"></div>

            <div x-show="modalOpen"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-3 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-3 sm:scale-95"
                 class="relative w-full max-w-2xl bg-white dark:bg-[#0e1626] rounded-2xl shadow-2xl shadow-slate-950/60 border border-slate-200/90 dark:border-slate-800 overflow-hidden z-10 max-h-[90vh] flex flex-col">
                    
                    <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/80 dark:bg-[#121c2e] flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 border border-indigo-100 dark:border-indigo-800 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold text-sm shadow-xs">
                                <i class="fa-solid fa-database"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-800 dark:text-white text-sm">Detail Data</h3>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400">Tabel: <code class="font-mono text-indigo-600 dark:text-indigo-400 font-semibold">{{ $table }}</code></p>
                            </div>
                        </div>
                        <button @click="modalOpen = false" class="w-8 h-8 rounded-lg text-slate-400 hover:text-slate-700 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors flex items-center justify-center cursor-pointer">
                            <i class="fa-solid fa-xmark text-base"></i>
                        </button>
                    </div>

                    <div class="p-6 max-h-[70vh] overflow-y-auto space-y-4 custom-scrollbar">
                        <template x-if="selectedRecord">
                            <div class="space-y-3">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <template x-for="(value, key) in selectedRecord" :key="key">
                                        <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-[#131d31] border border-slate-200/70 dark:border-slate-800" x-show="typeof value !== 'object'">
                                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500" x-text="key"></p>
                                            <p class="text-xs sm:text-sm font-semibold text-slate-800 dark:text-slate-100 mt-1 break-words" x-text="value ?? '-'"></p>
                                        </div>
                                    </template>
                                </div>

                                <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800">
                                    <p class="text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 flex items-center gap-1.5">
                                        <i class="fa-solid fa-code text-indigo-500"></i> Raw JSON Output:
                                    </p>
                                    <pre class="p-3.5 bg-slate-900 text-emerald-400 rounded-xl text-[11px] font-mono overflow-x-auto leading-relaxed border border-slate-800" x-text="JSON.stringify(selectedRecord, null, 2)"></pre>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="px-6 py-3.5 border-t border-slate-100 dark:border-slate-800 bg-slate-50/80 dark:bg-[#121c2e] flex justify-end">
                        <button @click="modalOpen = false" class="px-4 py-2 bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-xl text-xs font-bold transition-colors cursor-pointer">
                            Tutup
                        </button>
                    </div>
            </div>
        </div>
    </template>

    <!-- CREATE MODAL -->
    <template x-teleport="body">
        <div x-cloak x-show="createOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 overflow-y-auto" role="dialog" aria-modal="true">
            <div x-show="createOpen"
                 x-transition:enter="transition-opacity ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="createOpen = false"
                 class="fixed inset-0 bg-slate-950/80 transition-opacity"></div>

            <div x-show="createOpen"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-3 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-3 sm:scale-95"
                 class="relative w-full max-w-xl bg-white dark:bg-[#0e1626] rounded-2xl shadow-2xl shadow-slate-950/60 border border-slate-200/90 dark:border-slate-800 overflow-hidden z-10">

                    <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/80 dark:bg-[#121c2e] flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 border border-indigo-200/80 dark:border-indigo-800 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold text-sm shadow-xs">
                                <i class="fa-solid fa-plus"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-800 dark:text-white text-sm">Tambah {{ $tableInfo['title'] }}</h3>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400">Tabel: <code class="font-mono text-indigo-600 dark:text-indigo-400 font-semibold">{{ $table }}</code></p>
                            </div>
                        </div>
                        <button @click="createOpen = false" type="button" class="w-8 h-8 rounded-lg text-slate-400 hover:text-slate-700 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors flex items-center justify-center cursor-pointer">
                            <i class="fa-solid fa-xmark text-base"></i>
                        </button>
                    </div>

                    <form method="POST" action="{{ route('table.store', $table) }}">
                        @csrf
                        <div class="p-6 max-h-[65vh] overflow-y-auto space-y-4 custom-scrollbar">
                            @foreach($formFields as $field)
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                                        {{ $field['label'] }} @if($field['required'])<span class="text-rose-500 font-bold">*</span>@endif
                                    </label>

                                    @if($field['type'] === 'select')
                                        <select name="{{ $field['name'] }}" {{ $field['required'] ? 'required' : '' }}
                                                class="w-full px-3.5 py-2.5 bg-slate-50/70 dark:bg-[#131d31] border border-slate-300 dark:border-slate-700 rounded-xl text-xs sm:text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:bg-white dark:focus:bg-[#18233a] transition-all shadow-xs">
                                            <option value="">-- Pilih {{ $field['label'] }} --</option>
                                            @foreach($field['options'] ?? [] as $optValue => $optLabel)
                                                <option value="{{ $optValue }}" {{ old($field['name']) == $optValue ? 'selected' : '' }}>{{ $optLabel }}</option>
                                            @endforeach
                                        </select>
                                    @elseif($field['type'] === 'textarea')
                                        <textarea name="{{ $field['name'] }}" rows="3" {{ $field['required'] ? 'required' : '' }}
                                                  class="w-full px-3.5 py-2.5 bg-slate-50/70 dark:bg-[#131d31] border border-slate-300 dark:border-slate-700 rounded-xl text-xs sm:text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:bg-white dark:focus:bg-[#18233a] transition-all shadow-xs">{{ old($field['name']) }}</textarea>
                                    @else
                                        <input type="{{ $field['type'] }}" name="{{ $field['name'] }}" value="{{ old($field['name']) }}" {{ $field['required'] ? 'required' : '' }}
                                               class="w-full px-3.5 py-2.5 bg-slate-50/70 dark:bg-[#131d31] border border-slate-300 dark:border-slate-700 rounded-xl text-xs sm:text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:bg-white dark:focus:bg-[#18233a] transition-all shadow-xs">
                                    @endif

                                    @error($field['name'])
                                        <p class="text-[11px] text-rose-500 mt-1 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endforeach
                        </div>

                        <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/80 dark:bg-[#121c2e] flex justify-end gap-2.5">
                            <button type="button" @click="createOpen = false" class="px-4 py-2.5 bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-xl text-xs font-bold transition-all cursor-pointer">
                                Batal
                            </button>
                            <button type="submit" class="px-5 py-2.5 bg-[#4f46e5] hover:bg-indigo-700 active:scale-[0.98] text-white rounded-xl text-xs font-bold transition-all shadow-md shadow-indigo-600/20 inline-flex items-center gap-1.5 cursor-pointer">
                                <i class="fa-solid fa-check"></i>
                                Simpan Data
                            </button>
                        </div>
                    </form>
            </div>
        </div>
    </template>

    <!-- EDIT MODAL -->
    <template x-teleport="body">
        <div x-cloak x-show="editOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 overflow-y-auto" role="dialog" aria-modal="true">
            <div x-show="editOpen"
                 x-transition:enter="transition-opacity ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="editOpen = false"
                 class="fixed inset-0 bg-slate-950/80 transition-opacity"></div>

            <div x-show="editOpen"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-3 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-3 sm:scale-95"
                 class="relative w-full max-w-xl bg-white dark:bg-[#0e1626] rounded-2xl shadow-2xl shadow-slate-950/60 border border-slate-200/90 dark:border-slate-800 overflow-hidden z-10">

                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/80 dark:bg-[#121c2e] flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-amber-50 dark:bg-amber-950/60 border border-amber-200/80 dark:border-amber-800 text-amber-600 dark:text-amber-400 flex items-center justify-center font-bold text-sm shadow-xs">
                            <i class="fa-solid fa-pen"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 dark:text-white text-sm">Ubah Data {{ $tableInfo['title'] }}</h3>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400">Tabel: <code class="font-mono text-amber-600 dark:text-amber-400 font-semibold">{{ $table }}</code> &bull; ID: <span class="font-mono font-bold" x-text="editRecord.id"></span></p>
                        </div>
                    </div>
                    <button @click="editOpen = false" type="button" class="w-8 h-8 rounded-lg text-slate-400 hover:text-slate-700 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors flex items-center justify-center cursor-pointer">
                        <i class="fa-solid fa-xmark text-base"></i>
                    </button>
                </div>

                <form method="POST" :action="'{{ url('table/'.$table) }}/' + (editRecord.id || '')">
                    @csrf
                    @method('PUT')
                    <div class="p-6 max-h-[65vh] overflow-y-auto space-y-4 custom-scrollbar">
                        @foreach($formFields as $field)
                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                                    {{ $field['label'] }} @if($field['required'] && $field['type'] !== 'password')<span class="text-rose-500 font-bold">*</span>@endif
                                </label>

                                @if($field['type'] === 'select')
                                    <select name="{{ $field['name'] }}" x-model="editRecord.{{ $field['name'] }}" {{ $field['required'] ? 'required' : '' }}
                                            class="w-full px-3.5 py-2.5 bg-slate-50/70 dark:bg-[#131d31] border border-slate-300 dark:border-slate-700 rounded-xl text-xs sm:text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 focus:bg-white dark:focus:bg-[#18233a] transition-all shadow-xs">
                                        <option value="">-- Pilih {{ $field['label'] }} --</option>
                                        @foreach($field['options'] ?? [] as $optValue => $optLabel)
                                            <option value="{{ $optValue }}">{{ $optLabel }}</option>
                                        @endforeach
                                    </select>
                                @elseif($field['type'] === 'textarea')
                                    <textarea name="{{ $field['name'] }}" x-model="editRecord.{{ $field['name'] }}" rows="3" {{ $field['required'] ? 'required' : '' }}
                                              class="w-full px-3.5 py-2.5 bg-slate-50/70 dark:bg-[#131d31] border border-slate-300 dark:border-slate-700 rounded-xl text-xs sm:text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 focus:bg-white dark:focus:bg-[#18233a] transition-all shadow-xs"></textarea>
                                @elseif($field['type'] === 'password')
                                    <input type="password" name="{{ $field['name'] }}" x-model="editRecord.{{ $field['name'] }}"
                                           placeholder="Kosongkan jika tidak ingin mengganti password"
                                           class="w-full px-3.5 py-2.5 bg-slate-50/70 dark:bg-[#131d31] border border-slate-300 dark:border-slate-700 rounded-xl text-xs sm:text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 focus:bg-white dark:focus:bg-[#18233a] transition-all shadow-xs">
                                @else
                                    <input type="{{ $field['type'] }}" name="{{ $field['name'] }}" x-model="editRecord.{{ $field['name'] }}" {{ $field['required'] ? 'required' : '' }}
                                           class="w-full px-3.5 py-2.5 bg-slate-50/70 dark:bg-[#131d31] border border-slate-300 dark:border-slate-700 rounded-xl text-xs sm:text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 focus:bg-white dark:focus:bg-[#18233a] transition-all shadow-xs">
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/80 dark:bg-[#121c2e] flex justify-end gap-2.5">
                        <button type="button" @click="editOpen = false" class="px-4 py-2.5 bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-xl text-xs font-bold transition-all cursor-pointer">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 active:scale-[0.98] text-white rounded-xl text-xs font-bold transition-all shadow-md shadow-amber-500/20 inline-flex items-center gap-1.5 cursor-pointer">
                            <i class="fa-solid fa-check"></i>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>

    <!-- DELETE CONFIRM MODAL -->
    <template x-teleport="body">
        <div x-cloak x-show="deleteOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 overflow-y-auto" role="dialog" aria-modal="true">
            <div x-show="deleteOpen"
                 x-transition:enter="transition-opacity ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="deleteOpen = false"
                 class="fixed inset-0 bg-slate-950/80 transition-opacity"></div>

            <div x-show="deleteOpen"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-3 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-3 sm:scale-95"
                 class="relative w-full max-w-sm bg-white dark:bg-[#0e1626] rounded-2xl shadow-2xl shadow-slate-950/60 border border-slate-200/90 dark:border-slate-800 overflow-hidden z-10">

                <div class="p-6 text-center">
                    <div class="w-14 h-14 rounded-2xl bg-rose-50 dark:bg-rose-950/60 border border-rose-200/80 dark:border-rose-800 text-rose-500 dark:text-rose-400 flex items-center justify-center text-2xl mx-auto mb-4 shadow-xs">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <h3 class="font-bold text-slate-800 dark:text-white text-base mb-1.5">Hapus data ini?</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                        Anda akan menghapus <span class="font-bold text-slate-800 dark:text-white" x-text="deleteRecord?.label"></span> secara permanen dari tabel <code class="font-mono text-indigo-600 dark:text-indigo-400 font-semibold">{{ $table }}</code>. Tindakan ini tidak dapat dibatalkan.
                    </p>
                </div>

                <form method="POST" :action="'{{ url('table/'.$table) }}/' + (deleteRecord ? deleteRecord.id : '')" class="px-6 pb-6 flex gap-2.5">
                    @csrf
                    @method('DELETE')
                    <button type="button" @click="deleteOpen = false" class="flex-1 px-4 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-xl text-xs font-bold transition-all cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2.5 bg-rose-600 hover:bg-rose-700 active:scale-[0.98] text-white rounded-xl text-xs font-bold transition-all shadow-md shadow-rose-600/20 inline-flex items-center justify-center gap-1.5 cursor-pointer">
                        <i class="fa-solid fa-trash text-xs"></i>
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </template>

</div>
@endsection
