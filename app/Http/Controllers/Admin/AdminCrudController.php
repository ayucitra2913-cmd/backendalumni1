<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Acara;
use App\Models\Album;
use App\Models\Alumni;
use App\Models\Angkatan;
use App\Models\Artikel;
use App\Models\Content;
use App\Models\Gallery;
use App\Models\Kelas;
use App\Models\PengurusAlumni;
use App\Models\PeriodeKepengurusan;
use App\Models\PrestasiAlumni;
use App\Models\Testimony;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminCrudController extends Controller
{
    /**
     * Mapping nama tabel (slug) ke class Model.
     */
    protected function modelFor(string $table): string
    {
        return match ($table) {
            'angkatan' => Angkatan::class,
            'kelas' => Kelas::class,
            'alumni' => Alumni::class,
            'users' => User::class,
            'albums' => Album::class,
            'galleries' => Gallery::class,
            'artikels', 'artikel' => Artikel::class,
            'acara', 'events' => Acara::class,
            'pengurus_alumni' => PengurusAlumni::class,
            'prestasi_alumni' => PrestasiAlumni::class,
            'testimonies' => Testimony::class,
            'contents' => Content::class,
            'periode_kepengurusan' => PeriodeKepengurusan::class,
            default => abort(404, 'Tabel tidak ditemukan'),
        };
    }

    /**
     * Definisi kolom form (dipakai untuk generate modal Create & Edit secara dinamis).
     */
    public function fieldsFor(string $table): array
    {
        return match ($table) {
            'angkatan' => [
                ['name' => 'tahun_angkatan', 'label' => 'Tahun Angkatan', 'type' => 'number', 'required' => true],
                ['name' => 'nama_angkatan', 'label' => 'Nama / Julukan Angkatan', 'type' => 'text', 'required' => true],
            ],
            'kelas' => [
                ['name' => 'angkatan_id', 'label' => 'Angkatan', 'type' => 'select', 'required' => true, 'options' => $this->optionsFor('angkatan')],
                ['name' => 'nama_kelas', 'label' => 'Nama Kelas', 'type' => 'text', 'required' => true],
            ],
            'alumni' => [
                ['name' => 'user_id', 'label' => 'Akun User (opsional)', 'type' => 'select', 'required' => false, 'options' => $this->optionsFor('users')],
                ['name' => 'angkatan_id', 'label' => 'Angkatan', 'type' => 'select', 'required' => true, 'options' => $this->optionsFor('angkatan')],
                ['name' => 'kelas_id', 'label' => 'Kelas', 'type' => 'select', 'required' => true, 'options' => $this->optionsFor('kelas')],
                ['name' => 'nisn', 'label' => 'NISN', 'type' => 'text', 'required' => false],
                ['name' => 'nama_lengkap', 'label' => 'Nama Lengkap', 'type' => 'text', 'required' => true],
                ['name' => 'jenis_kelamin', 'label' => 'Jenis Kelamin', 'type' => 'select', 'required' => false, 'options' => ['L' => 'Laki-laki', 'P' => 'Perempuan']],
                ['name' => 'telepon', 'label' => 'Telepon', 'type' => 'text', 'required' => false],
                ['name' => 'alamat', 'label' => 'Alamat', 'type' => 'textarea', 'required' => false],
                ['name' => 'pekerjaan_saat_ini', 'label' => 'Pekerjaan Saat Ini', 'type' => 'text', 'required' => false],
                ['name' => 'foto_profil', 'label' => 'URL Foto Profil', 'type' => 'text', 'required' => false],
            ],
            'users' => [
                ['name' => 'username', 'label' => 'Username', 'type' => 'text', 'required' => true],
                ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'required' => true],
                ['name' => 'password', 'label' => 'Password', 'type' => 'password', 'required' => true],
                ['name' => 'role', 'label' => 'Role Akses', 'type' => 'select', 'required' => true, 'options' => ['admin' => 'Admin', 'moderator' => 'Moderator', 'user' => 'User']],
            ],
            'albums' => [
                ['name' => 'user_id', 'label' => 'Pemilik (User)', 'type' => 'select', 'required' => false, 'options' => $this->optionsFor('users')],
                ['name' => 'nama_album', 'label' => 'Nama Album', 'type' => 'text', 'required' => true],
                ['name' => 'deskripsi', 'label' => 'Deskripsi', 'type' => 'textarea', 'required' => false],
                ['name' => 'cover_image', 'label' => 'URL Cover Image', 'type' => 'text', 'required' => false],
            ],
            'galleries' => [
                ['name' => 'album_id', 'label' => 'Album', 'type' => 'select', 'required' => true, 'options' => $this->optionsFor('albums')],
                ['name' => 'file_url', 'label' => 'URL Foto', 'type' => 'text', 'required' => true],
                ['name' => 'caption', 'label' => 'Caption', 'type' => 'text', 'required' => false],
            ],
            'artikels', 'artikel' => [
                ['name' => 'user_id', 'label' => 'Penulis (User)', 'type' => 'select', 'required' => false, 'options' => $this->optionsFor('users')],
                ['name' => 'judul', 'label' => 'Judul Artikel', 'type' => 'text', 'required' => true],
                ['name' => 'slug', 'label' => 'Slug (URL)', 'type' => 'text', 'required' => true],
                ['name' => 'konten', 'label' => 'Konten', 'type' => 'textarea', 'required' => true],
                ['name' => 'gambar_utama', 'label' => 'URL Gambar Utama', 'type' => 'text', 'required' => false],
                ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'required' => true, 'options' => ['draft' => 'Draft', 'published' => 'Published']],
            ],
            'acara', 'events' => [
                ['name' => 'user_id', 'label' => 'Penyelenggara (User)', 'type' => 'select', 'required' => false, 'options' => $this->optionsFor('users')],
                ['name' => 'nama_acara', 'label' => 'Nama Acara', 'type' => 'text', 'required' => true],
                ['name' => 'deskripsi', 'label' => 'Deskripsi', 'type' => 'textarea', 'required' => false],
                ['name' => 'tanggal_mulai', 'label' => 'Tanggal Mulai', 'type' => 'datetime-local', 'required' => false],
                ['name' => 'tanggal_selesai', 'label' => 'Tanggal Selesai', 'type' => 'datetime-local', 'required' => false],
                ['name' => 'lokasi', 'label' => 'Lokasi', 'type' => 'text', 'required' => false],
                ['name' => 'banner_image', 'label' => 'URL Banner', 'type' => 'text', 'required' => false],
            ],
            'pengurus_alumni' => [
                ['name' => 'alumni_id', 'label' => 'Alumni', 'type' => 'select', 'required' => true, 'options' => $this->optionsFor('alumni')],
                ['name' => 'periode_id', 'label' => 'Periode Kepengurusan', 'type' => 'select', 'required' => true, 'options' => $this->optionsFor('periode_kepengurusan')],
                ['name' => 'jabatan', 'label' => 'Jabatan', 'type' => 'text', 'required' => true],
            ],
            'prestasi_alumni' => [
                ['name' => 'alumni_id', 'label' => 'Alumni', 'type' => 'select', 'required' => true, 'options' => $this->optionsFor('alumni')],
                ['name' => 'nama_prestasi', 'label' => 'Nama Prestasi', 'type' => 'text', 'required' => true],
                ['name' => 'tingkat', 'label' => 'Tingkat', 'type' => 'text', 'required' => false],
                ['name' => 'tahun_perolehan', 'label' => 'Tahun Perolehan', 'type' => 'number', 'required' => false],
                ['name' => 'deskripsi', 'label' => 'Deskripsi', 'type' => 'textarea', 'required' => false],
                ['name' => 'sertifikat_url', 'label' => 'URL Sertifikat', 'type' => 'text', 'required' => false],
            ],
            'testimonies' => [
                ['name' => 'alumni_id', 'label' => 'Alumni', 'type' => 'select', 'required' => true, 'options' => $this->optionsFor('alumni')],
                ['name' => 'pesan', 'label' => 'Pesan Testimoni', 'type' => 'textarea', 'required' => true],
                ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'required' => true, 'options' => ['pending' => 'Pending', 'approved' => 'Approved']],
            ],
            'periode_kepengurusan' => [
                ['name' => 'nama_periode', 'label' => 'Nama Periode Kepengurusan', 'type' => 'text', 'required' => true],
                ['name' => 'tanggal_mulai', 'label' => 'Tanggal Mulai', 'type' => 'date', 'required' => false],
                ['name' => 'tanggal_selesai', 'label' => 'Tanggal Selesai', 'type' => 'date', 'required' => false],
            ],
            'contents' => [
                ['name' => 'key_identifier', 'label' => 'Key Identifier', 'type' => 'text', 'required' => true],
                ['name' => 'judul', 'label' => 'Judul Konten', 'type' => 'text', 'required' => true],
                ['name' => 'isi', 'label' => 'Isi Konten', 'type' => 'textarea', 'required' => false],
                ['name' => 'gambar', 'label' => 'URL Gambar', 'type' => 'text', 'required' => false],
            ],
            default => [],
        };
    }

    /**
     * Ambil daftar opsi [id => label] dari tabel relasi untuk dropdown select secara efisien.
     */
    protected function optionsFor(string $table): array
    {
        return match ($table) {
            'angkatan' => Angkatan::orderByDesc('tahun_angkatan')->pluck('nama_angkatan', 'id')->toArray(),
            'kelas' => Kelas::join('angkatan', 'kelas.angkatan_id', '=', 'angkatan.id')
                ->select('kelas.id', 'kelas.nama_kelas', 'angkatan.nama_angkatan')
                ->get()
                ->mapWithKeys(fn ($k) => [$k->id => ($k->nama_kelas ?? '-') . ' (' . ($k->nama_angkatan ?? '-') . ')'])
                ->toArray(),
            'users' => User::orderBy('username')->pluck('username', 'id')->toArray(),
            'alumni' => Alumni::orderBy('nama_lengkap')->pluck('nama_lengkap', 'id')->toArray(),
            'albums' => Album::orderBy('nama_album')->pluck('nama_album', 'id')->toArray(),
            'periode_kepengurusan' => PeriodeKepengurusan::orderByDesc('tanggal_mulai')->pluck('nama_periode', 'id')->toArray(),
            default => [],
        };
    }

    /**
     * Aturan validasi per tabel. $ignoreId dipakai saat update agar unique rule mengabaikan record itu sendiri.
     */
    protected function rules(string $table, ?int $ignoreId = null): array
    {
        return match ($table) {
            'angkatan' => [
                'tahun_angkatan' => ['required', 'integer', 'digits:4'],
                'nama_angkatan' => ['required', 'string', 'max:255'],
            ],
            'kelas' => [
                'angkatan_id' => ['required', 'exists:angkatan,id'],
                'nama_kelas' => ['required', 'string', 'max:255'],
            ],
            'alumni' => [
                'user_id' => ['nullable', 'exists:users,id'],
                'angkatan_id' => ['required', 'exists:angkatan,id'],
                'kelas_id' => ['required', 'exists:kelas,id'],
                'nisn' => ['nullable', 'string', 'max:50'],
                'nama_lengkap' => ['required', 'string', 'max:255'],
                'jenis_kelamin' => ['nullable', Rule::in(['L', 'P'])],
                'telepon' => ['nullable', 'string', 'max:30'],
                'alamat' => ['nullable', 'string'],
                'pekerjaan_saat_ini' => ['nullable', 'string', 'max:255'],
                'foto_profil' => ['nullable', 'string', 'max:2048'],
            ],
            'users' => [
                'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($ignoreId)],
                'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($ignoreId)],
                'password' => [$ignoreId ? 'nullable' : 'required', 'string', 'min:6'],
                'role' => ['required', Rule::in(['admin', 'moderator', 'user'])],
            ],
            'albums' => [
                'user_id' => ['nullable', 'exists:users,id'],
                'nama_album' => ['required', 'string', 'max:255'],
                'deskripsi' => ['nullable', 'string'],
                'cover_image' => ['nullable', 'string', 'max:2048'],
            ],
            'galleries' => [
                'album_id' => ['required', 'exists:albums,id'],
                'file_url' => ['required', 'string', 'max:2048'],
                'caption' => ['nullable', 'string', 'max:255'],
            ],
            'artikels', 'artikel' => [
                'user_id' => ['nullable', 'exists:users,id'],
                'judul' => ['required', 'string', 'max:255'],
                'slug' => ['required', 'string', 'max:255', Rule::unique('artikels', 'slug')->ignore($ignoreId)],
                'konten' => ['required', 'string'],
                'gambar_utama' => ['nullable', 'string', 'max:2048'],
                'status' => ['required', Rule::in(['draft', 'published'])],
            ],
            'acara' => [
                'user_id' => ['nullable', 'exists:users,id'],
                'nama_acara' => ['required', 'string', 'max:255'],
                'deskripsi' => ['nullable', 'string'],
                'tanggal_mulai' => ['nullable', 'date'],
                'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
                'lokasi' => ['nullable', 'string', 'max:255'],
                'banner_image' => ['nullable', 'string', 'max:2048'],
            ],
            'pengurus_alumni' => [
                'alumni_id' => ['required', 'exists:alumni,id'],
                'periode_id' => ['required', 'exists:periode_kepengurusan,id'],
                'jabatan' => ['required', 'string', 'max:225'],
            ],
            'prestasi_alumni' => [
                'alumni_id' => ['required', 'exists:alumni,id'],
                'nama_prestasi' => ['required', 'string', 'max:255'],
                'tingkat' => ['nullable', 'string', 'max:100'],
                'tahun_perolehan' => ['nullable', 'integer', 'digits:4'],
                'deskripsi' => ['nullable', 'string'],
                'sertifikat_url' => ['nullable', 'string', 'max:2048'],
            ],
            'testimonies' => [
                'alumni_id' => ['required', 'exists:alumni,id'],
                'pesan' => ['required', 'string'],
                'status' => ['required', Rule::in(['pending', 'approved'])],
            ],
            'periode_kepengurusan' => [
                'nama_periode' => ['required', 'string', 'max:100'],
                'tanggal_mulai' => ['nullable', 'date'],
                'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
            ],
            'contents' => [
                'key_identifier' => ['required', 'string', 'max:255', Rule::unique('contents', 'key_identifier')->ignore($ignoreId)],
                'judul' => ['required', 'string', 'max:255'],
                'isi' => ['nullable', 'string'],
                'gambar' => ['nullable', 'string', 'max:2048'],
            ],
            default => [],
        };
    }

    /**
     * Simpan record baru.
     */
    public function store(Request $request, string $table)
    {
        $modelClass = $this->modelFor($table);
        $data = $request->validate($this->rules($table));

        $modelClass::create($data);

        return redirect()
            ->route('table.show', $table)
            ->with('success', 'Data berhasil ditambahkan.');
    }

    /**
     * Update record yang sudah ada.
     */
    public function update(Request $request, string $table, int $id)
    {
        $modelClass = $this->modelFor($table);
        $record = $modelClass::findOrFail($id);

        $data = $request->validate($this->rules($table, $id));

        if ($table === 'users' && empty($data['password'])) {
            unset($data['password']);
        }

        $record->update($data);

        return redirect()
            ->route('table.show', $table)
            ->with('success', 'Data berhasil diperbarui.');
    }

    /**
     * Hapus record.
     */
    public function destroy(string $table, int $id)
    {
        $modelClass = $this->modelFor($table);
        $record = $modelClass::findOrFail($id);
        $record->delete();

        return redirect()
            ->route('table.show', $table)
            ->with('success', 'Data berhasil dihapus.');
    }
}
