{{--
    Partial: tables/partials/form-fields.blade.php
    Dipakai oleh modal Create dan Edit di tables/show.blade.php
    Variables tersedia: $table, $angkatanOptions, $kelasOptions, $alumniOptions, $albumOptions
    Mode: 'create' | 'edit'
    Untuk mode edit, nilai field diambil dari Alpine.js editRecord object.
--}}

@php
    $isEdit = ($mode === 'edit');
    // Helper closure untuk value & x-bind
    // Untuk create: value='', untuk edit: :value="editRecord.key"
@endphp

{{-- ──────────────── 1. ANGKATAN ──────────────────── --}}
@if($table === 'angkatan')
    <div>
        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Tahun Angkatan <span class="text-red-500">*</span></label>
        <input type="text" name="tahun_angkatan" placeholder="Contoh: 2024"
               @if($isEdit) :value="editRecord.tahun_angkatan" @endif
               class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-slate-50 focus:bg-white" required>
    </div>
    <div>
        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama / Julukan Angkatan <span class="text-red-500">*</span></label>
        <input type="text" name="nama_angkatan" placeholder="Contoh: Angkatan Garuda 2024"
               @if($isEdit) :value="editRecord.nama_angkatan" @endif
               class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-slate-50 focus:bg-white" required>
    </div>

{{-- ──────────────── 2. KELAS ──────────────────────── --}}
@elseif($table === 'kelas')
    <div>
        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Angkatan <span class="text-red-500">*</span></label>
        <select name="angkatan_id"
                @if($isEdit) x-init="$el.value = editRecord.angkatan_id" @endif
                class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-slate-50 focus:bg-white" required>
            <option value="">— Pilih Angkatan —</option>
            @foreach($angkatanOptions as $ang)
                <option value="{{ $ang->id }}">{{ $ang->tahun_angkatan }} — {{ $ang->nama_angkatan }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Kelas <span class="text-red-500">*</span></label>
        <input type="text" name="nama_kelas" placeholder="Contoh: XII RPL 1"
               @if($isEdit) :value="editRecord.nama_kelas" @endif
               class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-slate-50 focus:bg-white" required>
    </div>

{{-- ──────────────── 3. ALUMNI ─────────────────────── --}}
@elseif($table === 'alumni')
    <div class="grid grid-cols-2 gap-3">
        <div class="col-span-2">
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
            <input type="text" name="nama_lengkap" placeholder="Nama lengkap alumni"
                   @if($isEdit) :value="editRecord.nama_lengkap" @endif
                   class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-slate-50 focus:bg-white" required>
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Jenis Kelamin <span class="text-red-500">*</span></label>
            <select name="jenis_kelamin"
                    @if($isEdit) x-init="$el.value = editRecord.jenis_kelamin" @endif
                    class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-slate-50 focus:bg-white" required>
                <option value="L">Laki-laki</option>
                <option value="P">Perempuan</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">NISN</label>
            <input type="text" name="nisn" placeholder="Nomor NISN"
                   @if($isEdit) :value="editRecord.nisn" @endif
                   class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-slate-50 focus:bg-white">
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Angkatan</label>
            <select name="angkatan_id"
                    @if($isEdit) x-init="$el.value = editRecord.angkatan_id" @endif
                    class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-slate-50 focus:bg-white">
                <option value="">— Pilih Angkatan —</option>
                @foreach($angkatanOptions as $ang)
                    <option value="{{ $ang->id }}">{{ $ang->tahun_angkatan }} — {{ $ang->nama_angkatan }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Kelas</label>
            <select name="kelas_id"
                    @if($isEdit) x-init="$el.value = editRecord.kelas_id" @endif
                    class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-slate-50 focus:bg-white">
                <option value="">— Pilih Kelas —</option>
                @foreach($kelasOptions as $kls)
                    <option value="{{ $kls->id }}">{{ $kls->nama_kelas }} ({{ $kls->angkatan->nama_angkatan ?? '' }})</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Telepon</label>
            <input type="text" name="telepon" placeholder="08xxxxxxxxxx"
                   @if($isEdit) :value="editRecord.telepon" @endif
                   class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-slate-50 focus:bg-white">
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Pekerjaan Saat Ini</label>
            <input type="text" name="pekerjaan_saat_ini" placeholder="Software Engineer di Gojek"
                   @if($isEdit) :value="editRecord.pekerjaan_saat_ini" @endif
                   class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-slate-50 focus:bg-white">
        </div>
        <div class="col-span-2">
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Alamat</label>
            <textarea name="alamat" rows="2" placeholder="Alamat lengkap"
                      @if($isEdit) x-init="$el.value = editRecord.alamat ?? ''" @endif
                      class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-slate-50 focus:bg-white resize-none"></textarea>
        </div>
        <div class="col-span-2">
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">URL Foto Profil</label>
            <input type="text" name="foto_profil" placeholder="https://..."
                   @if($isEdit) :value="editRecord.foto_profil" @endif
                   class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-slate-50 focus:bg-white">
        </div>
    </div>

{{-- ──────────────── 4. USERS ──────────────────────── --}}
@elseif($table === 'users')
    <div>
        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Username <span class="text-red-500">*</span></label>
        <input type="text" name="username" placeholder="Masukkan username"
               @if($isEdit) :value="editRecord.username" @endif
               class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-slate-50 focus:bg-white" required>
    </div>
    <div>
        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Email <span class="text-red-500">*</span></label>
        <input type="email" name="email" placeholder="user@example.com"
               @if($isEdit) :value="editRecord.email" @endif
               class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-slate-50 focus:bg-white" required>
    </div>
    <div>
        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
            Password {{ $isEdit ? '(kosongkan jika tidak ingin ubah)' : '' }} @if(!$isEdit)<span class="text-red-500">*</span>@endif
        </label>
        <input type="password" name="password" placeholder="Minimal 6 karakter"
               class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-slate-50 focus:bg-white"
               @if(!$isEdit)required@endif>
    </div>
    <div>
        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Role <span class="text-red-500">*</span></label>
        <select name="role"
                @if($isEdit) x-init="$el.value = editRecord.role" @endif
                class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-slate-50 focus:bg-white" required>
            <option value="user">User</option>
            <option value="moderator">Moderator</option>
            <option value="admin">Admin</option>
        </select>
    </div>

{{-- ──────────────── 5. ALBUMS ─────────────────────── --}}
@elseif($table === 'albums')
    <div>
        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Album <span class="text-red-500">*</span></label>
        <input type="text" name="nama_album" placeholder="Nama album foto"
               @if($isEdit) :value="editRecord.nama_album" @endif
               class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-slate-50 focus:bg-white" required>
    </div>
    <div>
        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Deskripsi</label>
        <textarea name="deskripsi" rows="3" placeholder="Deskripsi album..."
                  @if($isEdit) x-init="$el.value = editRecord.deskripsi ?? ''" @endif
                  class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-slate-50 focus:bg-white resize-none"></textarea>
    </div>
    <div>
        <label class="block text-xs font-semibold text-slate-700 mb-1.5">URL Cover Image</label>
        <input type="text" name="cover_image" placeholder="https://..."
               @if($isEdit) :value="editRecord.cover_image" @endif
               class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-slate-50 focus:bg-white">
    </div>

{{-- ──────────────── 6. GALLERIES ──────────────────── --}}
@elseif($table === 'galleries')
    <div>
        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Album <span class="text-red-500">*</span></label>
        <select name="album_id"
                @if($isEdit) x-init="$el.value = editRecord.album_id" @endif
                class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-slate-50 focus:bg-white" required>
            <option value="">— Pilih Album —</option>
            @foreach($albumOptions as $alb)
                <option value="{{ $alb->id }}">{{ $alb->nama_album }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-xs font-semibold text-slate-700 mb-1.5">URL File Foto <span class="text-red-500">*</span></label>
        <input type="text" name="file_url" placeholder="https://..."
               @if($isEdit) :value="editRecord.file_url" @endif
               class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-slate-50 focus:bg-white" required>
    </div>
    <div>
        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Caption</label>
        <input type="text" name="caption" placeholder="Deskripsi singkat foto"
               @if($isEdit) :value="editRecord.caption" @endif
               class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-slate-50 focus:bg-white">
    </div>

{{-- ──────────────── 7. ARTIKELS ───────────────────── --}}
@elseif($table === 'artikel')
    <div>
        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Judul Artikel <span class="text-red-500">*</span></label>
        <input type="text" name="judul" placeholder="Judul artikel..."
               @if($isEdit) :value="editRecord.judul" @endif
               class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-slate-50 focus:bg-white" required>
    </div>
    <div>
        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Konten / Isi Artikel</label>
        <textarea name="konten" rows="5" placeholder="Tulis isi artikel di sini..."
                  @if($isEdit) x-init="$el.value = editRecord.konten ?? ''" @endif
                  class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-slate-50 focus:bg-white resize-none"></textarea>
    </div>
    <div>
        <label class="block text-xs font-semibold text-slate-700 mb-1.5">URL Gambar Utama</label>
        <input type="text" name="gambar_utama" placeholder="https://..."
               @if($isEdit) :value="editRecord.gambar_utama" @endif
               class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-slate-50 focus:bg-white">
    </div>
    <div>
        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Status <span class="text-red-500">*</span></label>
        <select name="status"
                @if($isEdit) x-init="$el.value = editRecord.status" @endif
                class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-slate-50 focus:bg-white" required>
            <option value="draft">Draft</option>
            <option value="published">Published</option>
        </select>
    </div>

{{-- ──────────────── 8. ACARA ─────────────────────── --}}
@elseif($table === 'acara')
    <div>
        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Acara <span class="text-red-500">*</span></label>
        <input type="text" name="nama_acara" placeholder="Nama kegiatan / acara"
               @if($isEdit) :value="editRecord.nama_acara" @endif
               class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-slate-50 focus:bg-white" required>
    </div>
    <div>
        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Deskripsi</label>
        <textarea name="deskripsi" rows="3" placeholder="Deskripsi acara..."
                  @if($isEdit) x-init="$el.value = editRecord.deskripsi ?? ''" @endif
                  class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-slate-50 focus:bg-white resize-none"></textarea>
    </div>
    <div class="grid grid-cols-2 gap-3">
        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Tanggal Mulai</label>
            <input type="datetime-local" name="tanggal_mulai"
                   @if($isEdit) :value="editRecord.tanggal_mulai ? editRecord.tanggal_mulai.substring(0,16) : ''" @endif
                   class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-slate-50 focus:bg-white">
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Tanggal Selesai</label>
            <input type="datetime-local" name="tanggal_selesai"
                   @if($isEdit) :value="editRecord.tanggal_selesai ? editRecord.tanggal_selesai.substring(0,16) : ''" @endif
                   class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-slate-50 focus:bg-white">
        </div>
    </div>
    <div>
        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Lokasi</label>
        <input type="text" name="lokasi" placeholder="Nama tempat / lokasi acara"
               @if($isEdit) :value="editRecord.lokasi" @endif
               class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-slate-50 focus:bg-white">
    </div>
    <div>
        <label class="block text-xs font-semibold text-slate-700 mb-1.5">URL Banner Image</label>
        <input type="text" name="banner_image" placeholder="https://..."
               @if($isEdit) :value="editRecord.banner_image" @endif
               class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-slate-50 focus:bg-white">
    </div>

{{-- ──────────────── 9. PENGURUS ALUMNI ───────────── --}}
@elseif($table === 'pengurus_alumni')
    <div>
        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Alumni <span class="text-red-500">*</span></label>
        <select name="alumni_id"
                @if($isEdit) x-init="$el.value = editRecord.alumni_id" @endif
                class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-slate-50 focus:bg-white" required>
            <option value="">— Pilih Alumni —</option>
            @foreach($alumniOptions as $alm)
                <option value="{{ $alm->id }}">{{ $alm->nama_lengkap }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Jabatan <span class="text-red-500">*</span></label>
        <input type="text" name="jabatan" placeholder="Contoh: Ketua Umum"
               @if($isEdit) :value="editRecord.jabatan" @endif
               class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-slate-50 focus:bg-white" required>
    </div>
    <div class="grid grid-cols-2 gap-3">
        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Periode Mulai</label>
            <input type="date" name="periode_mulai"
                   @if($isEdit) :value="editRecord.periode_mulai ? editRecord.periode_mulai.substring(0,10) : ''" @endif
                   class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-slate-50 focus:bg-white">
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Periode Selesai</label>
            <input type="date" name="periode_selesai"
                   @if($isEdit) :value="editRecord.periode_selesai ? editRecord.periode_selesai.substring(0,10) : ''" @endif
                   class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-slate-50 focus:bg-white">
            <p class="text-[11px] text-slate-400 mt-1">Kosongkan jika masih menjabat</p>
        </div>
    </div>

{{-- ──────────────── 10. PRESTASI ALUMNI ─────────── --}}
@elseif($table === 'prestasi_alumni')
    <div>
        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Alumni <span class="text-red-500">*</span></label>
        <select name="alumni_id"
                @if($isEdit) x-init="$el.value = editRecord.alumni_id" @endif
                class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-slate-50 focus:bg-white" required>
            <option value="">— Pilih Alumni —</option>
            @foreach($alumniOptions as $alm)
                <option value="{{ $alm->id }}">{{ $alm->nama_lengkap }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Prestasi <span class="text-red-500">*</span></label>
        <input type="text" name="nama_prestasi" placeholder="Nama penghargaan / prestasi"
               @if($isEdit) :value="editRecord.nama_prestasi" @endif
               class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-slate-50 focus:bg-white" required>
    </div>
    <div class="grid grid-cols-2 gap-3">
        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Tingkat</label>
            <input type="text" name="tingkat" placeholder="Nasional / Internasional"
                   @if($isEdit) :value="editRecord.tingkat" @endif
                   class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-slate-50 focus:bg-white">
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Tahun Perolehan</label>
            <input type="number" name="tahun_perolehan" placeholder="2024" min="2000" max="2099"
                   @if($isEdit) :value="editRecord.tahun_perolehan" @endif
                   class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-slate-50 focus:bg-white">
        </div>
    </div>
    <div>
        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Deskripsi</label>
        <textarea name="deskripsi" rows="3" placeholder="Ceritakan tentang prestasi ini..."
                  @if($isEdit) x-init="$el.value = editRecord.deskripsi ?? ''" @endif
                  class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-slate-50 focus:bg-white resize-none"></textarea>
    </div>
    <div>
        <label class="block text-xs font-semibold text-slate-700 mb-1.5">URL Sertifikat</label>
        <input type="text" name="sertifikat_url" placeholder="https://..."
               @if($isEdit) :value="editRecord.sertifikat_url" @endif
               class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-slate-50 focus:bg-white">
    </div>

{{-- ──────────────── 11. TESTIMONIES ──────────────── --}}
@elseif($table === 'testimonies')
    <div>
        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Alumni <span class="text-red-500">*</span></label>
        <select name="alumni_id"
                @if($isEdit) x-init="$el.value = editRecord.alumni_id" @endif
                class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-slate-50 focus:bg-white" required>
            <option value="">— Pilih Alumni —</option>
            @foreach($alumniOptions as $alm)
                <option value="{{ $alm->id }}">{{ $alm->nama_lengkap }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Pesan Testimoni <span class="text-red-500">*</span></label>
        <textarea name="pesan" rows="4" placeholder="Tulis pesan testimoni alumni..."
                  @if($isEdit) x-init="$el.value = editRecord.pesan ?? ''" @endif
                  class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-slate-50 focus:bg-white resize-none" required></textarea>
    </div>
    <div>
        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Status <span class="text-red-500">*</span></label>
        <select name="status"
                @if($isEdit) x-init="$el.value = editRecord.status" @endif
                class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-slate-50 focus:bg-white" required>
            <option value="pending">Pending</option>
            <option value="approved">Approved</option>
        </select>
    </div>

{{-- ──────────────── 12. CONTENTS ─────────────────── --}}
@elseif($table === 'contents')
    <div>
        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Key Identifier <span class="text-red-500">*</span></label>
        <input type="text" name="key_identifier" placeholder="Contoh: hero_title"
               @if($isEdit) :value="editRecord.key_identifier" @endif
               class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-slate-50 focus:bg-white" required>
        <p class="text-[11px] text-slate-400 mt-1">Gunakan underscore, tanpa spasi. Contoh: hero_title, visi, misi</p>
    </div>
    <div>
        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Judul Konten</label>
        <input type="text" name="judul" placeholder="Judul section"
               @if($isEdit) :value="editRecord.judul" @endif
               class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-slate-50 focus:bg-white">
    </div>
    <div>
        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Isi Konten</label>
        <textarea name="isi" rows="5" placeholder="Isi konten halaman..."
                  @if($isEdit) x-init="$el.value = editRecord.isi ?? ''" @endif
                  class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-slate-50 focus:bg-white resize-none"></textarea>
    </div>
    <div>
        <label class="block text-xs font-semibold text-slate-700 mb-1.5">URL Gambar (opsional)</label>
        <input type="text" name="gambar" placeholder="https://..."
               @if($isEdit) :value="editRecord.gambar" @endif
               class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-slate-50 focus:bg-white">
    </div>
@else
    <div class="text-center py-4 text-slate-400 text-sm">
        <i class="fa-solid fa-triangle-exclamation mb-2 text-xl"></i>
        <p>Form untuk tabel ini belum tersedia.</p>
    </div>
@endif
