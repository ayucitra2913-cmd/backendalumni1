@if($records->count() > 0)
    @foreach($records as $row)
        <tr class="hover:bg-slate-50/80 dark:hover:bg-[#131d31] transition-colors">
            <!-- ID -->
            <td class="py-4 px-4 sm:px-6 font-mono text-xs font-semibold text-slate-400 dark:text-slate-500">
                #{{ $row->id }}
            </td>

            <!-- 1. ALUMNI -->
            @if($table === 'alumni')
                <td class="py-4 px-4">
                    <div class="flex items-center gap-3">
                        <img src="{{ $row->foto_profil ?: 'https://i.pravatar.cc/100' }}" alt="{{ $row->nama_lengkap }}" class="w-10 h-10 rounded-full object-cover ring-1 ring-slate-200 dark:ring-slate-700 shadow-2xs flex-shrink-0">
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
                    @if(!empty($row->telepon))
                        <span class="text-xs font-medium text-slate-700 dark:text-slate-300 font-mono flex items-center gap-1.5">
                            <i class="fa-solid fa-phone text-[10px] text-slate-400"></i>
                            <span>{{ $row->telepon }}</span>
                        </span>
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

            <!-- 3. KELAS -->
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
@else
    <tr>
        <td colspan="6" class="py-16 text-center">
            <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 flex items-center justify-center text-2xl mx-auto mb-3">
                <i class="fa-solid fa-magnifying-glass"></i>
            </div>
            <h4 class="font-bold text-slate-700 dark:text-slate-200 text-base">Tidak ada data ditemukan</h4>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-1 max-w-sm mx-auto">
                Kueri pencarian tidak mencocokkan data apapun pada tabel ini.
            </p>
        </td>
    </tr>
@endif
