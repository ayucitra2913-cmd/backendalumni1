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
use App\Models\PeriodeKepengurusan;
use App\Models\PrestasiAlumni;
use App\Models\Testimony;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Get summary counts for the sidebar and dashboard with caching.
     */
    protected function getTableCounts(): array
    {
        return Cache::remember('dashboard_table_counts', 10, function () {
            return [
                'alumni' => Alumni::count(),
                'angkatan' => Angkatan::count(),
                'kelas' => Kelas::count(),
                'users' => User::count(),
                'periode_kepengurusan' => PeriodeKepengurusan::count(),
                'pengurus_alumni' => PengurusAlumni::count(),
                'prestasi_alumni' => PrestasiAlumni::count(),
                'artikels' => Artikel::count(),
                'acara' => Acara::count(),
                'albums' => Album::count(),
                'galleries' => Gallery::count(),
                'testimonies' => Testimony::count(),
                'contents' => Content::count(),
            ];
        });
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
            'alumni' => ['title' => 'Data Alumni', 'icon' => 'fa-user-graduate', 'desc' => 'Data lengkap profil alumni, kontak, dan pekerjaan'],
            'angkatan' => ['title' => 'Tahun Angkatan', 'icon' => 'fa-graduation-cap', 'desc' => 'Daftar tahun angkatan kelulusan alumni'],
            'kelas' => ['title' => 'Daftar Kelas', 'icon' => 'fa-chalkboard-user', 'desc' => 'Daftar pembagian kelas berdasarkan angkatan'],
            'users' => ['title' => 'Users / Akun', 'icon' => 'fa-users-gear', 'desc' => 'Akun pengguna sistem dan otentikasi login'],
            'periode_kepengurusan' => ['title' => 'Periode Kepengurusan', 'icon' => 'fa-calendar-check', 'desc' => 'Daftar masa bakti dan periode jabatan kepengurusan alumni'],
            'pengurus_alumni' => ['title' => 'Pengurus Alumni', 'icon' => 'fa-sitemap', 'desc' => 'Susunan struktur organisasi dan pengurus ikatan alumni'],
            'prestasi_alumni' => ['title' => 'Prestasi Alumni', 'icon' => 'fa-trophy', 'desc' => 'Daftar penghargaan dan pencapaian membanggakan para alumni'],
            'artikels' => ['title' => 'Artikel & Berita', 'icon' => 'fa-newspaper', 'desc' => 'Publikasi artikel, berita informasi, dan inspirasi alumni'],
            'acara' => ['title' => 'Acara & Agenda', 'icon' => 'fa-calendar-days', 'desc' => 'Jadwal temu alumni, reuni akbar, seminar, dan bakti sosial'],
            'albums' => ['title' => 'Album Foto', 'icon' => 'fa-images', 'desc' => 'Koleksi album foto kegiatan dan dokumentasi alumni'],
            'galleries' => ['title' => 'Galeri Foto', 'icon' => 'fa-camera-retro', 'desc' => 'Foto-foto dokumentasi dalam setiap album kegiatan'],
            'testimonies' => ['title' => 'Testimoni Alumni', 'icon' => 'fa-comment-dots', 'desc' => 'Ulasan, kesan, dan pesan inspiratif dari para alumni'],
            'contents' => ['title' => 'Konten Halaman', 'icon' => 'fa-file-lines', 'desc' => 'Pengaturan konten dinamis portal website institusi'],
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
                          ->orWhere('sosial_media', 'like', "%{$search}%");
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
                    $query->where('keterangan', 'like', "%{$search}%")
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

            case 'periode_kepengurusan':
                $query = PeriodeKepengurusan::withCount('pengurus');
                if ($search) {
                    $query->where('nama_periode', 'like', "%{$search}%");
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
