<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use App\Models\Acara;
use App\Models\Artikel;
use App\Models\PrestasiAlumni;
use App\Models\Kelas;
use App\Models\Album;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Handle global dynamic live search across models.
     */
    public function search(Request $request): JsonResponse
    {
        $query = trim($request->input('q', ''));

        if (mb_strlen($query) < 2) {
            return response()->json([
                'query' => $query,
                'total' => 0,
                'categories' => [],
            ]);
        }

        $categories = [];
        $totalMatches = 0;

        // 1. ALUMNI
        $alumni = Alumni::with(['angkatan', 'kelas'])
            ->where('nama_lengkap', 'like', "%{$query}%")
            ->orWhere('nisn', 'like', "%{$query}%")
            ->orWhere('pekerjaan_saat_ini', 'like', "%{$query}%")
            ->take(4)
            ->get()
            ->map(function ($a) {
                $sub = [];
                if ($a->kelas && $a->kelas->nama_kelas) {
                    $sub[] = $a->kelas->nama_kelas;
                }
                if ($a->angkatan && $a->angkatan->nama_angkatan) {
                    $sub[] = $a->angkatan->nama_angkatan;
                }
                if ($a->pekerjaan_saat_ini) {
                    $sub[] = $a->pekerjaan_saat_ini;
                }
                return [
                    'id' => $a->id,
                    'title' => $a->nama_lengkap,
                    'subtitle' => implode(' • ', $sub) ?: 'NISN: ' . ($a->nisn ?? '-'),
                    'image' => $a->foto_profil ?: 'https://i.pravatar.cc/100',
                    'url' => route('table.show', ['table' => 'alumni', 'search' => $a->nama_lengkap]),
                ];
            });

        if ($alumni->isNotEmpty()) {
            $categories[] = [
                'key' => 'alumni',
                'label' => 'Data Alumni',
                'icon' => 'fa-user-graduate',
                'color' => 'text-blue-500',
                'badgeBg' => 'bg-blue-50 text-blue-700 dark:bg-blue-950/60 dark:text-blue-300',
                'items' => $alumni,
            ];
            $totalMatches += $alumni->count();
        }

        // 2. ACARA & AGENDA
        $acara = Acara::where('nama_acara', 'like', "%{$query}%")
            ->orWhere('lokasi', 'like', "%{$query}%")
            ->orWhere('deskripsi', 'like', "%{$query}%")
            ->take(3)
            ->get()
            ->map(function ($e) {
                $sub = [];
                if ($e->lokasi) {
                    $sub[] = $e->lokasi;
                }
                if ($e->tanggal_mulai) {
                    $sub[] = \Carbon\Carbon::parse($e->tanggal_mulai)->format('d M Y');
                }
                return [
                    'id' => $e->id,
                    'title' => $e->nama_acara ?? $e->nama_event,
                    'subtitle' => implode(' • ', $sub) ?: 'Kegiatan Alumni',
                    'image' => $e->banner_image,
                    'url' => route('table.show', ['table' => 'acara', 'search' => $e->nama_acara ?? $e->nama_event]),
                ];
            });

        if ($acara->isNotEmpty()) {
            $categories[] = [
                'key' => 'acara',
                'label' => 'Acara & Agenda',
                'icon' => 'fa-calendar-days',
                'color' => 'text-orange-500',
                'badgeBg' => 'bg-orange-50 text-orange-700 dark:bg-orange-950/60 dark:text-orange-300',
                'items' => $acara,
            ];
            $totalMatches += $acara->count();
        }

        // 3. ARTIKEL & BERITA
        $artikels = Artikel::where('judul', 'like', "%{$query}%")
            ->orWhere('konten', 'like', "%{$query}%")
            ->take(3)
            ->get()
            ->map(function ($art) {
                return [
                    'id' => $art->id,
                    'title' => $art->judul,
                    'subtitle' => 'Status: ' . ucfirst($art->status),
                    'image' => $art->gambar_utama,
                    'url' => route('table.show', ['table' => 'artikels', 'search' => $art->judul]),
                ];
            });

        if ($artikels->isNotEmpty()) {
            $categories[] = [
                'key' => 'artikels',
                'label' => 'Artikel & Berita',
                'icon' => 'fa-newspaper',
                'color' => 'text-cyan-500',
                'badgeBg' => 'bg-cyan-50 text-cyan-700 dark:bg-cyan-950/60 dark:text-cyan-300',
                'items' => $artikels,
            ];
            $totalMatches += $artikels->count();
        }

        // 4. PRESTASI ALUMNI
        $prestasi = PrestasiAlumni::with('alumni')
            ->where('nama_prestasi', 'like', "%{$query}%")
            ->orWhere('deskripsi', 'like', "%{$query}%")
            ->orWhere('tingkat', 'like', "%{$query}%")
            ->take(3)
            ->get()
            ->map(function ($p) {
                $sub = [];
                if ($p->alumni && $p->alumni->nama_lengkap) {
                    $sub[] = $p->alumni->nama_lengkap;
                }
                if ($p->tingkat) {
                    $sub[] = $p->tingkat;
                }
                if ($p->tahun_perolehan) {
                    $sub[] = $p->tahun_perolehan;
                }
                return [
                    'id' => $p->id,
                    'title' => $p->nama_prestasi,
                    'subtitle' => implode(' • ', $sub) ?: 'Prestasi',
                    'image' => null,
                    'url' => route('table.show', ['table' => 'prestasi_alumni', 'search' => $p->nama_prestasi]),
                ];
            });

        if ($prestasi->isNotEmpty()) {
            $categories[] = [
                'key' => 'prestasi_alumni',
                'label' => 'Prestasi Alumni',
                'icon' => 'fa-trophy',
                'color' => 'text-yellow-500',
                'badgeBg' => 'bg-yellow-50 text-yellow-700 dark:bg-yellow-950/60 dark:text-yellow-300',
                'items' => $prestasi,
            ];
            $totalMatches += $prestasi->count();
        }

        // 5. KELAS
        $kelas = Kelas::with('angkatan')
            ->where('nama_kelas', 'like', "%{$query}%")
            ->take(3)
            ->get()
            ->map(function ($k) {
                return [
                    'id' => $k->id,
                    'title' => $k->nama_kelas,
                    'subtitle' => 'Angkatan: ' . ($k->angkatan->nama_angkatan ?? '-'),
                    'image' => null,
                    'url' => route('table.show', ['table' => 'kelas', 'search' => $k->nama_kelas]),
                ];
            });

        if ($kelas->isNotEmpty()) {
            $categories[] = [
                'key' => 'kelas',
                'label' => 'Daftar Kelas',
                'icon' => 'fa-chalkboard-user',
                'color' => 'text-emerald-500',
                'badgeBg' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300',
                'items' => $kelas,
            ];
            $totalMatches += $kelas->count();
        }

        // 6. ALBUM FOTO
        $albums = Album::where('nama_album', 'like', "%{$query}%")
            ->orWhere('deskripsi', 'like', "%{$query}%")
            ->take(3)
            ->get()
            ->map(function ($alb) {
                return [
                    'id' => $alb->id,
                    'title' => $alb->nama_album,
                    'subtitle' => $alb->deskripsi ?: 'Album Foto Alumni',
                    'image' => $alb->cover_image,
                    'url' => route('table.show', ['table' => 'albums', 'search' => $alb->nama_album]),
                ];
            });

        if ($albums->isNotEmpty()) {
            $categories[] = [
                'key' => 'albums',
                'label' => 'Album Foto',
                'icon' => 'fa-images',
                'color' => 'text-pink-500',
                'badgeBg' => 'bg-pink-50 text-pink-700 dark:bg-pink-950/60 dark:text-pink-300',
                'items' => $albums,
            ];
            $totalMatches += $albums->count();
        }

        return response()->json([
            'query' => $query,
            'total' => $totalMatches,
            'categories' => $categories,
        ]);
    }
}
