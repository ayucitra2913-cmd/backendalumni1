<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Admin\AdminCrudController;
use App\Models\Album;
use App\Models\Alumni;
use App\Models\Angkatan;
use App\Models\Artikel;
use App\Models\Content;
use App\Models\Acara;
use App\Models\Gallery;
use App\Models\Kelas;
use App\Models\PengurusAlumni;
use App\Models\PrestasiAlumni;
use App\Models\Testimony;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Get summary counts for the sidebar and dashboard.
     */
    protected function getTableCounts(): array
    {
        return [
            'angkatan' => Angkatan::count(),
            'kelas' => Kelas::count(),
            'alumni' => Alumni::count(),
            'users' => User::count(),
            'albums' => Album::count(),
            'galleries' => Gallery::count(),
            'artikels' => Artikel::count(),
            'acara' => Acara::count(),
            'pengurus_alumni' => PengurusAlumni::count(),
            'prestasi_alumni' => PrestasiAlumni::count(),
            'testimonies' => Testimony::count(),
            'contents' => Content::count(),
        ];
    }

    /**
     * Display Dashboard Overview.
     */
    public function index()
    {
        $counts = $this->getTableCounts();
        $recentAlumni = Alumni::with(['angkatan', 'kelas', 'user'])->latest()->take(6)->get();
        $upcomingAcara = Acara::latest()->take(4)->get();
        $latestArticles = Artikel::latest()->take(4)->get();
        $recentTestimonies = Testimony::with('alumni')->latest()->take(4)->get();
        $recentPrestasi = PrestasiAlumni::with('alumni')->latest()->take(4)->get();

        return view('dashboard', compact(
            'counts',
            'recentAlumni',
            'upcomingAcara',
            'latestArticles',
            'recentTestimonies',
            'recentPrestasi'
        ));
    }

    /**
     * Display data for a specific database table.
     */
    public function showTable(Request $request, string $table)
    {
        $validTables = [
            'angkatan' => ['title' => 'Tahun Angkatan', 'icon' => 'fa-graduation-cap', 'desc' => 'Daftar tahun angkatan kelulusan alumni'],
            'kelas' => ['title' => 'Daftar Kelas', 'icon' => 'fa-chalkboard-user', 'desc' => 'Daftar pembagian kelas berdasarkan angkatan'],
            'alumni' => ['title' => 'Data Alumni', 'icon' => 'fa-user-graduate', 'desc' => 'Data lengkap profil alumni, kontak, dan pekerjaan'],
            'users' => ['title' => 'Pengguna & Akun', 'icon' => 'fa-users-gear', 'desc' => 'Akun pengguna sistem dan otentikasi login'],
            'albums' => ['title' => 'Album Foto Kegiatan', 'icon' => 'fa-images', 'desc' => 'Koleksi album kegiatan dan dokumentasi alumni'],
            'galleries' => ['title' => 'Galeri Foto', 'icon' => 'fa-camera-retro', 'desc' => 'Foto-foto dalam setiap album kegiatan'],
            'artikels' => ['title' => 'Artikel & Berita', 'icon' => 'fa-newspaper', 'desc' => 'Publikasi artikel, berita, dan inspirasi alumni'],
            'acara' => ['title' => 'Acara & Agenda Kegiatan', 'icon' => 'fa-calendar-days', 'desc' => 'Jadwal temu alumni, reuni, seminar, dan bakti sosial'],
            'pengurus_alumni' => ['title' => 'Struktur Pengurus Alumni', 'icon' => 'fa-sitemap', 'desc' => 'Susunan pengurus dan organisasi ikatan alumni'],
            'prestasi_alumni' => ['title' => 'Prestasi & Penghargaan', 'icon' => 'fa-trophy', 'desc' => 'Daftar pencapaian membanggakan para alumni'],
            'testimonies' => ['title' => 'Testimoni Alumni', 'icon' => 'fa-comment-dots', 'desc' => 'Ulasan dan kesan pesan dari alumni'],
            'contents' => ['title' => 'Konten Halaman Web', 'icon' => 'fa-file-lines', 'desc' => 'Konten dinamis website (Visi, Misi, Kontak, Hero)'],
        ];

        if (!array_key_exists($table, $validTables)) {
            abort(404, 'Tabel tidak ditemukan');
        }

        $counts = $this->getTableCounts();
        $tableInfo = $validTables[$table];
        $search = $request->query('search');

        $query = null;
        switch ($table) {
            case 'angkatan':
                $query = Angkatan::withCount(['kelas', 'alumni']);
                if ($search) {
                    $query->where('nama_angkatan', 'like', "%{$search}%")
                          ->orWhere('tahun_angkatan', 'like', "%{$search}%");
                }
                break;

            case 'kelas':
                $query = Kelas::with('angkatan')->withCount('alumni');
                if ($search) {
                    $query->where('nama_kelas', 'like', "%{$search}%")
                          ->orWhereHas('angkatan', fn($q) => $q->where('nama_angkatan', 'like', "%{$search}%"));
                }
                break;

            case 'alumni':
                $query = Alumni::with(['angkatan', 'kelas', 'user', 'pengurus', 'prestasi']);
                if ($search) {
                    $query->where('nama_lengkap', 'like', "%{$search}%")
                          ->orWhere('nisn', 'like', "%{$search}%")
                          ->orWhere('pekerjaan_saat_ini', 'like', "%{$search}%")
                          ->orWhere('alamat', 'like', "%{$search}%");
                }
                break;

            case 'users':
                $query = User::with('alumni');
                if ($search) {
                    $query->where('username', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%")
                          ->orWhere('role', 'like', "%{$search}%");
                }
                break;

            case 'albums':
                $query = Album::with('user')->withCount('galleries');
                if ($search) {
                    $query->where('nama_album', 'like', "%{$search}%")
                          ->orWhere('deskripsi', 'like', "%{$search}%");
                }
                break;

            case 'galleries':
                $query = Gallery::with('album');
                if ($search) {
                    $query->where('caption', 'like', "%{$search}%")
                          ->orWhereHas('album', fn($q) => $q->where('nama_album', 'like', "%{$search}%"));
                }
                break;

            case 'artikels':
                $query = Artikel::with('user');
                if ($search) {
                    $query->where('judul', 'like', "%{$search}%")
                          ->orWhere('konten', 'like', "%{$search}%")
                          ->orWhere('status', 'like', "%{$search}%");
                }
                break;

            case 'acara':
                $query = Acara::with('user');
                if ($search) {
                    $query->where('nama_acara', 'like', "%{$search}%")
                          ->orWhere('lokasi', 'like', "%{$search}%")
                          ->orWhere('deskripsi', 'like', "%{$search}%");
                }
                break;

           case 'pengurus_alumni':
                $query = PengurusAlumni::with(['alumni.angkatan', 'alumni.kelas', 'periode']);
                if ($search) {
                    $query->where('jabatan', 'like', "%{$search}%")
                        ->orWhereHas('alumni', fn($q) => $q->where('nama_lengkap', 'like', "%{$search}%"))
                        ->orWhereHas('periode', fn($q) => $q->where('nama_periode', 'like', "%{$search}%"));
                }
                break;

            case 'prestasi_alumni':
                $query = PrestasiAlumni::with(['alumni.angkatan']);
                if ($search) {
                    $query->where('nama_prestasi', 'like', "%{$search}%")
                          ->orWhere('tingkat', 'like', "%{$search}%")
                          ->orWhere('deskripsi', 'like', "%{$search}%")
                          ->orWhereHas('alumni', fn($q) => $q->where('nama_lengkap', 'like', "%{$search}%"));
                }
                break;

            case 'testimonies':
                $query = Testimony::with(['alumni.angkatan']);
                if ($search) {
                    $query->where('pesan', 'like', "%{$search}%")
                          ->orWhere('status', 'like', "%{$search}%")
                          ->orWhereHas('alumni', fn($q) => $q->where('nama_lengkap', 'like', "%{$search}%"));
                }
                break;

            case 'contents':
                $query = Content::query();
                if ($search) {
                    $query->where('key_identifier', 'like', "%{$search}%")
                          ->orWhere('judul', 'like', "%{$search}%")
                          ->orWhere('isi', 'like', "%{$search}%");
                }
                break;
        }

        $records = $query->latest('id')->paginate(10)->withQueryString();

        $formFields = (new AdminCrudController())->fieldsFor($table);

        return view('tables.show', compact('table', 'tableInfo', 'records', 'counts', 'search', 'validTables', 'formFields'));
    }
}
