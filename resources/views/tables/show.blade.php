@extends('layouts.app')

@section('title', $tableInfo['title'])
@section('page_title', $tableInfo['title'])

@section('content')
<div class="max-w-7xl mx-auto space-y-6" x-data="{
        selectedRecord: null,
        modalOpen: false,
        createOpen: {{ $errors->any() && old('_token') ? 'true' : 'false' }},
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

    <!-- HEADER SECTION -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-start sm:items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center text-2xl shadow-inner flex-shrink-0">
                <i class="fa-solid {{ $tableInfo['icon'] }}"></i>
            </div>
            <div>
                <div class="flex items-center gap-2.5 flex-wrap">
                    <h2 class="text-xl sm:text-2xl font-black text-slate-800 tracking-tight">{{ $tableInfo['title'] }}</h2>
                    </code>
                    <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-bold">
                        {{ $records->total() }} Total Data
                    </span>
                </div>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">{{ $tableInfo['desc'] }}</p>
            </div>
        </div>

        <!-- SEARCH BAR + TAMBAH DATA -->
        <div class="flex items-center gap-2 w-full md:w-auto">
            <form method="GET" action="{{ route('table.show', $table) }}" class="flex items-center gap-2 flex-1 md:flex-none">
                <div class="relative flex-1 md:w-64">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" 
                           name="search" 
                           value="{{ $search }}" 
                           placeholder="Cari data {{ $tableInfo['title'] }}..." 
                           class="w-full pl-9 pr-8 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                    @if($search)
                        <a href="{{ route('table.show', $table) }}" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                            <i class="fa-solid fa-xmark text-xs"></i>
                        </a>
                    @endif
                </div>
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition-colors shadow-sm">
                    Cari
                </button>
            </form>

            <button type="button" 
                    @click="createOpen = true"
                    class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition-colors shadow-sm inline-flex items-center gap-1.5 whitespace-nowrap">
                <i class="fa-solid fa-plus"></i>
                <span class="hidden sm:inline">Tambah Data</span>
            </button>
        </div>
    </div>

    <!-- MAIN TABLE CARD -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        
        @if($records->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-200 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                            <th class="py-3.5 px-4 sm:px-6 w-16">#ID</th>
                            
                            @if($table === 'alumni')
                                <th class="py-3.5 px-4">Foto & Nama Alumni</th>
                                <th class="py-3.5 px-4">Angkatan & Kelas</th>
                                <th class="py-3.5 px-4">Kontak & Alamat</th>
                                <th class="py-3.5 px-4">Pekerjaan Saat Ini</th>
                            @elseif($table === 'angkatan')
                                <th class="py-3.5 px-4">Tahun Angkatan</th>
                                <th class="py-3.5 px-4">Nama / Julukan Angkatan</th>
                                <th class="py-3.5 px-4">Total Kelas</th>
                                <th class="py-3.5 px-4">Total Alumni</th>
                            @elseif($table === 'kelas')
                                <th class="py-3.5 px-4">Nama Kelas</th>
                                <th class="py-3.5 px-4">Tahun Angkatan</th>
                                <th class="py-3.5 px-4">Total Siswa Terdata</th>
                            @elseif($table === 'users')
                                <th class="py-3.5 px-4">Username</th>
                                <th class="py-3.5 px-4">Email</th>
                                <th class="py-3.5 px-4">Role Akses</th>
                                <th class="py-3.5 px-4">Status Alumni</th>
                            @elseif($table === 'albums')
                                <th class="py-3.5 px-4">Cover</th>
                                <th class="py-3.5 px-4">Nama Album</th>
                                <th class="py-3.5 px-4">Deskripsi</th>
                                <th class="py-3.5 px-4">Jumlah Foto</th>
                            @elseif($table === 'galleries')
                                <th class="py-3.5 px-4">Preview Foto</th>
                                <th class="py-3.5 px-4">Album</th>
                                <th class="py-3.5 px-4">Caption Foto</th>
                            @elseif($table === 'artikels')
                                <th class="py-3.5 px-4">Cover</th>
                                <th class="py-3.5 px-4">Judul Artikel</th>
                                <th class="py-3.5 px-4">Status</th>
                                <th class="py-3.5 px-4">Author</th>
                            @elseif($table === 'acara')
                                <th class="py-3.5 px-4">Banner</th>
                                <th class="py-3.5 px-4">Nama Acara</th>
                                <th class="py-3.5 px-4">Jadwal Pelaksanaan</th>
                                <th class="py-3.5 px-4">Lokasi</th>
                            @elseif($table === 'pengurus_alumni')
                                <th class="py-3.5 px-4">Nama Alumni</th>
                                <th class="py-3.5 px-4">Jabatan Organisasi</th>
                                <th class="py-3.5 px-4">Periode Menjabat</th>
                            @elseif($table === 'prestasi_alumni')
                                <th class="py-3.5 px-4">Nama Prestasi & Penghargaan</th>
                                <th class="py-3.5 px-4">Pemenang (Alumni)</th>
                                <th class="py-3.5 px-4">Tingkat & Tahun</th>
                            @elseif($table === 'testimonies')
                                <th class="py-3.5 px-4">Alumni</th>
                                <th class="py-3.5 px-4">Pesan Testimoni</th>
                                <th class="py-3.5 px-4">Status</th>
                            @elseif($table === 'contents')
                                <th class="py-3.5 px-4">Key Identifier</th>
                                <th class="py-3.5 px-4">Judul Konten</th>
                                <th class="py-3.5 px-4">Isi Konten Ringkas</th>
                            @endif

                            <th class="py-3.5 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs sm:text-sm text-slate-700">
                        @foreach($records as $row)
                            <tr class="hover:bg-indigo-50/40 transition-colors">
                                <!-- ID -->
                                <td class="py-4 px-4 sm:px-6 font-mono text-xs font-bold text-slate-400">
                                    #{{ $row->id }}
                                </td>

                                <!-- 1. ALUMNI -->
                                @if($table === 'alumni')
                                    <td class="py-4 px-4">
                                        <div class="flex items-center gap-3">
                                            <img src="{{ $row->foto_profil }}" alt="{{ $row->nama_lengkap }}" class="w-10 h-10 rounded-xl object-cover ring-1 ring-slate-200 shadow-sm flex-shrink-0">
                                            <div>
                                                <div class="font-bold text-slate-900 leading-snug">{{ $row->nama_lengkap }}</div>
                                                <div class="text-[11px] text-slate-500 font-mono">NISN: {{ $row->nisn ?? '-' }} • {{ $row->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4">
                                        <div class="font-semibold text-slate-800">{{ $row->angkatan->nama_angkatan ?? '-' }}</div>
                                        <div class="text-xs text-indigo-600 font-medium">{{ $row->kelas->nama_kelas ?? '-' }}</div>
                                    </td>
                                    <td class="py-4 px-4">
                                        <div class="text-slate-800 font-medium"><i class="fa-solid fa-phone text-slate-400 text-[10px] mr-1"></i> {{ $row->telepon ?? '-' }}</div>
                                        <div class="text-[11px] text-slate-500 truncate max-w-xs"><i class="fa-solid fa-location-dot text-slate-400 text-[10px] mr-1"></i> {{ $row->alamat ?? '-' }}</div>
                                    </td>
                                    <td class="py-4 px-4">
                                        <span class="inline-block font-medium text-slate-800 bg-slate-100 px-2.5 py-1 rounded-lg border border-slate-200">
                                            {{ $row->pekerjaan_saat_ini ?? 'Belum ada data' }}
                                        </span>
                                    </td>

                                <!-- 2. ANGKATAN -->
                                @elseif($table === 'angkatan')
                                    <td class="py-4 px-4">
                                        <span class="font-bold text-indigo-700 bg-indigo-50 px-3 py-1 rounded-lg border border-indigo-200">
                                            {{ $row->tahun_angkatan }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-4 font-bold text-slate-900">{{ $row->nama_angkatan }}</td>
                                    <td class="py-4 px-4">
                                        <span class="font-semibold text-slate-700">{{ $row->kelas_count }} Kelas</span>
                                    </td>
                                    <td class="py-4 px-4">
                                        <span class="font-semibold text-slate-700">{{ $row->alumni_count }} Alumni</span>
                                    </td>

                                <!-- 3. KELAS -->
                                @elseif($table === 'kelas')
                                    <td class="py-4 px-4 font-bold text-slate-900">{{ $row->nama_kelas }}</td>
                                    <td class="py-4 px-4">
                                        <span class="bg-amber-50 text-amber-700 border border-amber-200 font-semibold px-2.5 py-1 rounded-md text-xs">
                                            {{ $row->angkatan->nama_angkatan ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-4 font-medium text-slate-700">{{ $row->alumni_count }} Siswa Terdaftar</td>

                                <!-- 4. USERS -->
                                @elseif($table === 'users')
                                    <td class="py-4 px-4 font-bold text-slate-900">
                                        <div class="flex items-center gap-2">
                                            <i class="fa-regular fa-user text-slate-400"></i>
                                            <span>{{ $row->username }}</span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4 text-slate-600 font-mono text-xs">{{ $row->email }}</td>
                                    <td class="py-4 px-4">
                                        @if($row->role === 'admin')
                                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200">ADMIN</span>
                                        @elseif($row->role === 'moderator')
                                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">MODERATOR</span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">USER</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-4">
                                        @if($row->alumni)
                                            <span class="text-xs text-emerald-600 font-semibold flex items-center gap-1">
                                                <i class="fa-solid fa-check-circle"></i> {{ $row->alumni->nama_lengkap }}
                                            </span>
                                        @else
                                            <span class="text-xs text-slate-400 italic">Bukan Profil Alumni</span>
                                        @endif
                                    </td>

                                <!-- 5. ALBUMS -->
                                @elseif($table === 'albums')
                                    <td class="py-4 px-4">
                                        <img src="{{ $row->cover_image }}" alt="Cover" class="w-14 h-10 rounded-lg object-cover ring-1 ring-slate-200 shadow-sm">
                                    </td>
                                    <td class="py-4 px-4 font-bold text-slate-900">{{ $row->nama_album }}</td>
                                    <td class="py-4 px-4 text-slate-600 text-xs line-clamp-2 max-w-xs">{{ $row->deskripsi }}</td>
                                    <td class="py-4 px-4 font-semibold text-indigo-600">{{ $row->galleries_count }} Foto</td>

                                <!-- 6. GALLERIES -->
                                @elseif($table === 'galleries')
                                    <td class="py-4 px-4">
                                        <img src="{{ $row->file_url }}" alt="Gallery" class="w-16 h-12 rounded-lg object-cover ring-1 ring-slate-200 shadow-sm">
                                    </td>
                                    <td class="py-4 px-4 font-semibold text-slate-900">{{ $row->album->nama_album ?? '-' }}</td>
                                    <td class="py-4 px-4 text-slate-600 text-xs">{{ $row->caption }}</td>

                                <!-- 7. ARTIKELS -->
                                @elseif($table === 'artikels')
                                    <td class="py-4 px-4">
                                        <img src="{{ $row->gambar_utama }}" alt="Artikel" class="w-14 h-10 rounded-lg object-cover ring-1 ring-slate-200 shadow-sm">
                                    </td>
                                    <td class="py-4 px-4">
                                        <div class="font-bold text-slate-900 leading-snug">{{ $row->judul }}</div>
                                        <div class="text-[11px] text-slate-400 font-mono mt-0.5">slug: {{ $row->slug }}</div>
                                    </td>
                                    <td class="py-4 px-4">
                                        @if($row->status === 'published')
                                             <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Published</span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">Draft</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-4 text-xs font-medium text-slate-600">{{ $row->user->username ?? 'Admin' }}</td>

                                <!-- 8. ACARA -->
                                @elseif($table === 'acara')
                                    <td class="py-4 px-4">
                                        <img src="{{ $row->banner_image }}" alt="Event" class="w-14 h-10 rounded-lg object-cover ring-1 ring-slate-200 shadow-sm">
                                    </td>
                                    <td class="py-4 px-4 font-bold text-slate-900">{{ $row->nama_acara ?? $row->nama_event }}</td>
                                    <td class="py-4 px-4 text-xs">
                                        <div class="font-semibold text-slate-800">
                                            {{ $row->tanggal_mulai ? $row->tanggal_mulai->format('d M Y, H:i') : '-' }}
                                        </div>
                                        <div class="text-slate-400 text-[11px]">
                                            s/d {{ $row->tanggal_selesai ? $row->tanggal_selesai->format('d M Y, H:i') : '-' }}
                                        </div>
                                    </td>
                                    <td class="py-4 px-4 text-slate-700 font-medium text-xs">{{ $row->lokasi }}</td>

                                <!-- 9. PENGURUS ALUMNI -->
                                @elseif($table === 'pengurus_alumni')
                                    <td class="py-4 px-4">
                                        <div class="flex items-center gap-2.5">
                                            <img src="{{ $row->alumni->foto_profil ?? 'https://i.pravatar.cc/100' }}" alt="Alumni" class="w-9 h-9 rounded-full object-cover">
                                            <div>
                                                <div class="font-bold text-slate-900">{{ $row->alumni->nama_lengkap ?? '-' }}</div>
                                                <div class="text-[11px] text-slate-500">{{ $row->alumni->angkatan->nama_angkatan ?? '' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4 font-bold text-rose-700 bg-rose-50/50 px-3 py-1 rounded-lg">
                                        {{ $row->jabatan }}
                                    </td>
                                    <td class="py-4 px-4 text-xs font-mono text-slate-600">
                                        {{ $row->periode->nama_periode ?? '-' }}
                                    </td>

                                <!-- 10. PRESTASI ALUMNI -->
                                @elseif($table === 'prestasi_alumni')
                                    <td class="py-4 px-4">
                                        <div class="font-bold text-slate-900 leading-snug">{{ $row->nama_prestasi }}</div>
                                        <div class="text-xs text-slate-500 mt-0.5 line-clamp-1">{{ $row->deskripsi }}</div>
                                    </td>
                                    <td class="py-4 px-4 font-semibold text-slate-800">{{ $row->alumni->nama_lengkap ?? '-' }}</td>
                                    <td class="py-4 px-4">
                                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                            {{ $row->tingkat }} • {{ $row->tahun_perolehan }}
                                        </span>
                                    </td>

                                <!-- 11. TESTIMONIES -->
                                @elseif($table === 'testimonies')
                                    <td class="py-4 px-4">
                                        <div class="flex items-center gap-2.5">
                                            <img src="{{ $row->alumni->foto_profil ?? 'https://i.pravatar.cc/100' }}" alt="Alumni" class="w-9 h-9 rounded-full object-cover">
                                            <div>
                                                <div class="font-bold text-slate-900">{{ $row->alumni->nama_lengkap ?? '-' }}</div>
                                                <div class="text-[11px] text-slate-500">{{ $row->alumni->angkatan->nama_angkatan ?? '' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4 text-slate-600 text-xs italic max-w-sm">"{{ $row->pesan }}"</td>
                                    <td class="py-4 px-4">
                                        @if($row->status === 'approved')
                                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Approved</span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">Pending</span>
                                        @endif
                                    </td>

                                <!-- 12. CONTENTS -->
                                @elseif($table === 'contents')
                                    <td class="py-4 px-4 font-mono font-bold text-indigo-600 text-xs">{{ $row->key_identifier }}</td>
                                    <td class="py-4 px-4 font-bold text-slate-900">{{ $row->judul }}</td>
                                    <td class="py-4 px-4 text-slate-600 text-xs max-w-xs truncate">{{ $row->isi }}</td>
                                @endif

                                <!-- ACTION: DETAIL / EDIT / HAPUS -->
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
                                @endphp
                                <td class="py-4 px-4 text-right">
                                    <div class="inline-flex items-center gap-1.5">
                                        <button type="button" 
                                                @click="openDetail(@js($row->attributesToArray()))" 
                                                title="Lihat Detail"
                                                class="px-2.5 py-1.5 rounded-lg bg-slate-100 hover:bg-indigo-600 hover:text-white text-slate-600 text-xs font-semibold transition-colors inline-flex items-center gap-1.5 shadow-sm">
                                            <i class="fa-solid fa-eye text-[11px]"></i>
                                        </button>
                                        <button type="button" 
                                                @click="openEdit(@js($editData))" 
                                                title="Edit Data"
                                                class="px-2.5 py-1.5 rounded-lg bg-slate-100 hover:bg-amber-500 hover:text-white text-slate-600 text-xs font-semibold transition-colors inline-flex items-center gap-1.5 shadow-sm">
                                            <i class="fa-solid fa-pen text-[11px]"></i>
                                        </button>
                                        @php
                            $labelField = [
                                'angkatan' => 'nama_angkatan', 'kelas' => 'nama_kelas', 'alumni' => 'nama_lengkap',
                                'users' => 'username', 'albums' => 'nama_album', 'galleries' => 'caption',
                                'artikels' => 'judul', 'acara' => 'nama_acara', 'pengurus_alumni' => 'jabatan',
                                'prestasi_alumni' => 'nama_prestasi', 'testimonies' => 'pesan', 'contents' => 'judul',
                            ][$table] ?? 'id';
                            $deleteLabel = $row->{$labelField} ?? ($row->nama_event ?? ('#' . $row->id));
                        @endphp
                                        <button type="button" 
                                                @click="openDelete({{ $row->id }}, @js(\Illuminate\Support\Str::limit($deleteLabel, 60)))" 
                                                title="Hapus Data"
                                                class="px-2.5 py-1.5 rounded-lg bg-slate-100 hover:bg-rose-600 hover:text-white text-slate-600 text-xs font-semibold transition-colors inline-flex items-center gap-1.5 shadow-sm">
                                            <i class="fa-solid fa-trash text-[11px]"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- PAGINATION -->
            <div class="p-4 sm:p-6 border-t border-slate-100 bg-slate-50/50">
                {{ $records->links() }}
            </div>

        @else
            <!-- EMPTY STATE -->
            <div class="py-16 text-center">
                <div class="w-16 h-16 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center text-2xl mx-auto mb-3">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
                <h4 class="font-bold text-slate-700 text-base">Tidak ada data ditemukan</h4>
                <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">
                    @if($search)
                        Pencarian kata kunci "<span class="font-semibold text-slate-600">{{ $search }}</span>" tidak membuahkan hasil.
                    @else
                        Tabel ini saat ini masih belum memiliki data.
                    @endif
                </p>
                @if($search)
                    <a href="{{ route('table.show', $table) }}" class="inline-block mt-3 text-xs font-bold text-indigo-600 hover:text-indigo-800">
                        Reset Pencarian
                    </a>
                @endif
            </div>
        @endif

    </div>

    <!-- DETAIL RECORD MODAL -->
    <div x-cloak x-show="modalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 overflow-y-auto" role="dialog" aria-modal="true">
        <!-- Backdrop Overlay -->
        <div x-show="modalOpen"
             x-transition:enter="transition-opacity ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="modalOpen = false"
             class="fixed inset-0 bg-slate-950/80"></div>

        <!-- Modal Dialog -->
        <div x-show="modalOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-3 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-3 sm:scale-95"
             class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl shadow-slate-950/30 border border-slate-200/90 overflow-hidden z-10 max-h-[90vh] flex flex-col">
                
                <!-- Modal Header -->
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/80 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-sm shadow-xs">
                            <i class="fa-solid fa-database"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 text-sm">Detail Data</h3>
                            <p class="text-[11px] text-slate-500">Tabel: <code class="font-mono text-indigo-600 font-semibold">{{ $table }}</code></p>
                        </div>
                    </div>
                    <button @click="modalOpen = false" class="w-8 h-8 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors flex items-center justify-center">
                        <i class="fa-solid fa-xmark text-base"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 max-h-[70vh] overflow-y-auto space-y-4 custom-scrollbar">
                    <template x-if="selectedRecord">
                        <div class="space-y-3">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <template x-for="(value, key) in selectedRecord" :key="key">
                                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/70" x-show="typeof value !== 'object'">
                                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400" x-text="key"></p>
                                        <p class="text-xs sm:text-sm font-semibold text-slate-800 mt-1 break-words" x-text="value ?? '-'"></p>
                                    </div>
                                </template>
                            </div>

                            <!-- Raw JSON View -->
                            <div class="mt-4 pt-3 border-t border-slate-100">
                                <p class="text-xs font-bold text-slate-700 mb-1.5 flex items-center gap-1.5">
                                    <i class="fa-solid fa-code text-indigo-500"></i> Raw JSON Output:
                                </p>
                                <pre class="p-3.5 bg-slate-900 text-emerald-400 rounded-xl text-[11px] font-mono overflow-x-auto leading-relaxed border border-slate-800" x-text="JSON.stringify(selectedRecord, null, 2)"></pre>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Modal Footer -->
                <div class="px-6 py-3.5 border-t border-slate-100 bg-slate-50/80 flex justify-end">
                    <button @click="modalOpen = false" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-xl text-xs font-bold transition-colors">
                        Tutup
                    </button>
                </div>
        </div>
    </div>

    <!-- CREATE MODAL -->
    <div x-cloak x-show="createOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 overflow-y-auto" role="dialog" aria-modal="true">
        <!-- Backdrop Overlay -->
        <div x-show="createOpen"
             x-transition:enter="transition-opacity ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="createOpen = false"
             class="fixed inset-0 bg-slate-950/80"></div>

        <!-- Modal Dialog -->
        <div x-show="createOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-3 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-3 sm:scale-95"
             class="relative w-full max-w-xl bg-white rounded-2xl shadow-2xl shadow-slate-950/30 border border-slate-200/90 overflow-hidden z-10">

                <!-- Header -->
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/80 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-emerald-50 border border-emerald-200/80 text-emerald-600 flex items-center justify-center font-bold text-sm shadow-xs">
                            <i class="fa-solid fa-plus"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 text-sm">Tambah Data Baru</h3>
                            <p class="text-[11px] text-slate-500">Tabel: <code class="font-mono text-emerald-600 font-semibold">{{ $table }}</code></p>
                        </div>
                    </div>
                    <button @click="createOpen = false" type="button" class="w-8 h-8 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors flex items-center justify-center">
                        <i class="fa-solid fa-xmark text-base"></i>
                    </button>
                </div>

                <form method="POST" action="{{ route('table.store', $table) }}">
                    @csrf
                    <div class="p-6 max-h-[65vh] overflow-y-auto space-y-4 custom-scrollbar">
                        @foreach($formFields as $field)
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                    {{ $field['label'] }} @if($field['required'])<span class="text-rose-500 font-bold">*</span>@endif
                                </label>

                                @if($field['type'] === 'select')
                                    <select name="{{ $field['name'] }}" {{ $field['required'] ? 'required' : '' }}
                                            class="w-full px-3.5 py-2.5 bg-slate-50/70 border border-slate-300 rounded-xl text-xs sm:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white transition-all shadow-xs">
                                        <option value="">-- Pilih {{ $field['label'] }} --</option>
                                        @foreach($field['options'] ?? [] as $optValue => $optLabel)
                                            <option value="{{ $optValue }}" {{ old($field['name']) == $optValue ? 'selected' : '' }}>{{ $optLabel }}</option>
                                        @endforeach
                                    </select>
                                @elseif($field['type'] === 'textarea')
                                    <textarea name="{{ $field['name'] }}" rows="3" {{ $field['required'] ? 'required' : '' }}
                                              class="w-full px-3.5 py-2.5 bg-slate-50/70 border border-slate-300 rounded-xl text-xs sm:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white transition-all shadow-xs">{{ old($field['name']) }}</textarea>
                                @else
                                    <input type="{{ $field['type'] }}" name="{{ $field['name'] }}" value="{{ old($field['name']) }}" {{ $field['required'] ? 'required' : '' }}
                                           class="w-full px-3.5 py-2.5 bg-slate-50/70 border border-slate-300 rounded-xl text-xs sm:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white transition-all shadow-xs">
                                @endif

                                @error($field['name'])
                                    <p class="text-[11px] text-rose-500 mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                        @endforeach
                    </div>

                    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/80 flex justify-end gap-2.5">
                        <button type="button" @click="createOpen = false" class="px-4 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-xl text-xs font-bold transition-all">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 active:scale-[0.98] text-white rounded-xl text-xs font-bold transition-all shadow-md shadow-emerald-600/20 inline-flex items-center gap-1.5">
                            <i class="fa-solid fa-check"></i>
                            Simpan Data
                        </button>
                    </div>
                </form>
        </div>
    </div>

    <!-- EDIT MODAL -->
    <div x-cloak x-show="editOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 overflow-y-auto" role="dialog" aria-modal="true">
        <!-- Backdrop Overlay -->
        <div x-show="editOpen"
             x-transition:enter="transition-opacity ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="editOpen = false"
             class="fixed inset-0 bg-slate-950/80"></div>

        <!-- Modal Dialog -->
        <div x-show="editOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-3 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-3 sm:scale-95"
             class="relative w-full max-w-xl bg-white rounded-2xl shadow-2xl shadow-slate-950/30 border border-slate-200/90 overflow-hidden z-10">

            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/80 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-amber-50 border border-amber-200/80 text-amber-600 flex items-center justify-center font-bold text-sm shadow-xs">
                        <i class="fa-solid fa-pen"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 text-sm">Ubah Data</h3>
                        <p class="text-[11px] text-slate-500">Tabel: <code class="font-mono text-amber-600 font-semibold">{{ $table }}</code> &bull; ID: <span class="font-mono font-bold" x-text="editRecord.id"></span></p>
                    </div>
                </div>
                <button @click="editOpen = false" type="button" class="w-8 h-8 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors flex items-center justify-center">
                    <i class="fa-solid fa-xmark text-base"></i>
                </button>
            </div>

            <form method="POST" :action="'{{ url('table/'.$table) }}/' + (editRecord.id || '')">
                @csrf
                @method('PUT')
                <div class="p-6 max-h-[65vh] overflow-y-auto space-y-4 custom-scrollbar">
                    @foreach($formFields as $field)
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                {{ $field['label'] }} @if($field['required'] && $field['type'] !== 'password')<span class="text-rose-500 font-bold">*</span>@endif
                            </label>

                            @if($field['type'] === 'select')
                                <select name="{{ $field['name'] }}" x-model="editRecord.{{ $field['name'] }}" {{ $field['required'] ? 'required' : '' }}
                                        class="w-full px-3.5 py-2.5 bg-slate-50/70 border border-slate-300 rounded-xl text-xs sm:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 focus:bg-white transition-all shadow-xs">
                                    <option value="">-- Pilih {{ $field['label'] }} --</option>
                                    @foreach($field['options'] ?? [] as $optValue => $optLabel)
                                        <option value="{{ $optValue }}">{{ $optLabel }}</option>
                                    @endforeach
                                </select>
                            @elseif($field['type'] === 'textarea')
                                <textarea name="{{ $field['name'] }}" x-model="editRecord.{{ $field['name'] }}" rows="3" {{ $field['required'] ? 'required' : '' }}
                                          class="w-full px-3.5 py-2.5 bg-slate-50/70 border border-slate-300 rounded-xl text-xs sm:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 focus:bg-white transition-all shadow-xs"></textarea>
                            @elseif($field['type'] === 'password')
                                <input type="password" name="{{ $field['name'] }}" x-model="editRecord.{{ $field['name'] }}"
                                       placeholder="Kosongkan jika tidak ingin mengganti password"
                                       class="w-full px-3.5 py-2.5 bg-slate-50/70 border border-slate-300 rounded-xl text-xs sm:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 focus:bg-white transition-all shadow-xs">
                            @else
                                <input type="{{ $field['type'] }}" name="{{ $field['name'] }}" x-model="editRecord.{{ $field['name'] }}" {{ $field['required'] ? 'required' : '' }}
                                       class="w-full px-3.5 py-2.5 bg-slate-50/70 border border-slate-300 rounded-xl text-xs sm:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 focus:bg-white transition-all shadow-xs">
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/80 flex justify-end gap-2.5">
                    <button type="button" @click="editOpen = false" class="px-4 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-xl text-xs font-bold transition-all">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 active:scale-[0.98] text-white rounded-xl text-xs font-bold transition-all shadow-md shadow-amber-500/20 inline-flex items-center gap-1.5">
                        <i class="fa-solid fa-check"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- DELETE CONFIRM MODAL -->
    <div x-cloak x-show="deleteOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 overflow-y-auto" role="dialog" aria-modal="true">
        <!-- Backdrop Overlay -->
        <div x-show="deleteOpen"
             x-transition:enter="transition-opacity ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="deleteOpen = false"
             class="fixed inset-0 bg-slate-950/80"></div>

        <!-- Modal Dialog -->
        <div x-show="deleteOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-3 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-3 sm:scale-95"
             class="relative w-full max-w-sm bg-white rounded-2xl shadow-2xl shadow-slate-950/30 border border-slate-200/90 overflow-hidden z-10">

            <div class="p-6 text-center">
                <div class="w-14 h-14 rounded-2xl bg-rose-50 border border-rose-200/80 text-rose-500 flex items-center justify-center text-2xl mx-auto mb-4 shadow-xs">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <h3 class="font-bold text-slate-800 text-base mb-1.5">Hapus data ini?</h3>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Anda akan menghapus <span class="font-bold text-slate-800" x-text="deleteRecord?.label"></span> secara permanen dari tabel <code class="font-mono text-indigo-600 font-semibold">{{ $table }}</code>. Tindakan ini tidak dapat dibatalkan.
                </p>
            </div>

            <form method="POST" :action="'{{ url('table/'.$table) }}/' + (deleteRecord ? deleteRecord.id : '')" class="px-6 pb-6 flex gap-2.5">
                @csrf
                @method('DELETE')
                <button type="button" @click="deleteOpen = false" class="flex-1 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all">
                    Batal
                </button>
                <button type="submit" class="flex-1 px-4 py-2.5 bg-rose-600 hover:bg-rose-700 active:scale-[0.98] text-white rounded-xl text-xs font-bold transition-all shadow-md shadow-rose-600/20 inline-flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-trash text-xs"></i>
                    Ya, Hapus
                </button>
            </form>
        </div>
    </div>

</div>
@endsection
