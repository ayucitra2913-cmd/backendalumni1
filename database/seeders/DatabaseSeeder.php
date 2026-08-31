<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Angkatan;
use App\Models\Kelas;
use App\Models\Alumni;
use App\Models\Album;
use App\Models\Gallery;
use App\Models\Artikel;
use App\Models\Acara;
use App\Models\PengurusAlumni;
use App\Models\PeriodeKepengurusan;
use App\Models\PrestasiAlumni;
use App\Models\Testimony;
use App\Models\Content;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ─── USERS ───────────────────────────────────────────────
        $admin = User::create([
            'username' => 'admin',
            'email'    => 'admin@alumni.com',
            'password' => Hash::make('admin123'),
            'role'     => 'admin',
        ]);

        $mod = User::create([
            'username' => 'moderator',
            'email'    => 'mod@alumni.com',
            'password' => Hash::make('password'),
            'role'     => 'moderator',
        ]);

        // ─── ANGKATAN ─────────────────────────────────────────────
        $angkatan2019 = Angkatan::create(['tahun_angkatan' => '2019', 'nama_angkatan' => 'Angkatan Garuda 2019']);
        $angkatan2020 = Angkatan::create(['tahun_angkatan' => '2020', 'nama_angkatan' => 'Angkatan Elang 2020']);
        $angkatan2021 = Angkatan::create(['tahun_angkatan' => '2021', 'nama_angkatan' => 'Angkatan Rajawali 2021']);
        $angkatan2022 = Angkatan::create(['tahun_angkatan' => '2022', 'nama_angkatan' => 'Angkatan Cendrawasih 2022']);

        // ─── KELAS ───────────────────────────────────────────────
        $kelas = [];
        foreach ([$angkatan2019, $angkatan2020, $angkatan2021, $angkatan2022] as $ang) {
            $kelas[] = Kelas::create(['angkatan_id' => $ang->id, 'nama_kelas' => 'XII RPL 1']);
            $kelas[] = Kelas::create(['angkatan_id' => $ang->id, 'nama_kelas' => 'XII RPL 2']);
            $kelas[] = Kelas::create(['angkatan_id' => $ang->id, 'nama_kelas' => 'XII TKJ 1']);
        }

        // ─── ALUMNI ───────────────────────────────────────────────
        $alumniData = [
            ['nama' => 'Budi Santoso',      'nisn' => '1234567890', 'jk' => 'L', 'telepon' => '081234567890', 'alamat' => 'Jl. Merdeka No. 1, Jakarta', 'pekerjaan' => 'Software Engineer di Gojek',        'foto' => 'https://i.pravatar.cc/150?img=1',  'angkatan' => $angkatan2019, 'kelas' => $kelas[0]],
            ['nama' => 'Siti Rahayu',       'nisn' => '1234567891', 'jk' => 'P', 'telepon' => '082345678901', 'alamat' => 'Jl. Sudirman No. 5, Bandung',  'pekerjaan' => 'UI/UX Designer di Tokopedia',      'foto' => 'https://i.pravatar.cc/150?img=5',  'angkatan' => $angkatan2019, 'kelas' => $kelas[1]],
            ['nama' => 'Ahmad Fajar',       'nisn' => '1234567892', 'jk' => 'L', 'telepon' => '083456789012', 'alamat' => 'Jl. Thamrin No. 10, Surabaya', 'pekerjaan' => 'Data Analyst di Shopee',            'foto' => 'https://i.pravatar.cc/150?img=12', 'angkatan' => $angkatan2020, 'kelas' => $kelas[3]],
            ['nama' => 'Dewi Lestari',      'nisn' => '1234567893', 'jk' => 'P', 'telepon' => '084567890123', 'alamat' => 'Jl. Diponegoro No. 7, Semarang','pekerjaan' => 'Backend Developer di Bukalapak',    'foto' => 'https://i.pravatar.cc/150?img=9',  'angkatan' => $angkatan2020, 'kelas' => $kelas[4]],
            ['nama' => 'Rizky Pratama',     'nisn' => '1234567894', 'jk' => 'L', 'telepon' => '085678901234', 'alamat' => 'Jl. Gatot Subroto No. 3, Medan','pekerjaan' => 'Network Engineer di Telkom',       'foto' => 'https://i.pravatar.cc/150?img=15', 'angkatan' => $angkatan2021, 'kelas' => $kelas[6]],
            ['nama' => 'Putri Handayani',   'nisn' => '1234567895', 'jk' => 'P', 'telepon' => '086789012345', 'alamat' => 'Jl. Ahmad Yani No. 2, Yogyakarta','pekerjaan' => 'Full Stack Developer Freelance', 'foto' => 'https://i.pravatar.cc/150?img=20', 'angkatan' => $angkatan2021, 'kelas' => $kelas[7]],
            ['nama' => 'Hendra Wijaya',     'nisn' => '1234567896', 'jk' => 'L', 'telepon' => '087890123456', 'alamat' => 'Jl. Pemuda No. 6, Malang',       'pekerjaan' => 'Game Developer Indie',             'foto' => 'https://i.pravatar.cc/150?img=3',  'angkatan' => $angkatan2022, 'kelas' => $kelas[9]],
            ['nama' => 'Maya Kusuma',       'nisn' => '1234567897', 'jk' => 'P', 'telepon' => '088901234567', 'alamat' => 'Jl. Pahlawan No. 8, Solo',        'pekerjaan' => 'Product Manager di startup',      'foto' => 'https://i.pravatar.cc/150?img=25', 'angkatan' => $angkatan2022, 'kelas' => $kelas[10]],
        ];

        $alumniModels = [];
        foreach ($alumniData as $d) {
            $alumniModels[] = Alumni::create([
                'user_id'           => null,
                'angkatan_id'       => $d['angkatan']->id,
                'kelas_id'          => $d['kelas']->id,
                'nisn'              => $d['nisn'],
                'nama_lengkap'      => $d['nama'],
                'jenis_kelamin'     => $d['jk'],
                'telepon'           => $d['telepon'],
                'alamat'            => $d['alamat'],
                'pekerjaan_saat_ini'=> $d['pekerjaan'],
                'foto_profil'       => $d['foto'],
            ]);
        }

        // ─── ALBUMS & GALLERIES ──────────────────────────────────
        $album1 = Album::create(['user_id' => $admin->id, 'nama_album' => 'Reuni Akbar 2023', 'deskripsi' => 'Dokumentasi reuni akbar alumni tahun 2023 di GOR Sekolah', 'cover_image' => 'https://picsum.photos/seed/reuni/400/300']);
        $album2 = Album::create(['user_id' => $admin->id, 'nama_album' => 'Wisuda Angkatan 2022', 'deskripsi' => 'Momen wisuda dan pelepasan angkatan 2022', 'cover_image' => 'https://picsum.photos/seed/wisuda/400/300']);
        $album3 = Album::create(['user_id' => $mod->id,  'nama_album' => 'Bakti Sosial 2024', 'deskripsi' => 'Kegiatan bakti sosial ke panti asuhan', 'cover_image' => 'https://picsum.photos/seed/baksos/400/300']);

        foreach ([$album1, $album2, $album3] as $i => $album) {
            for ($g = 1; $g <= 4; $g++) {
                Gallery::create([
                    'album_id' => $album->id,
                    'file_url'  => "https://picsum.photos/seed/gal{$i}{$g}/800/600",
                    'caption'   => "Foto dokumentasi kegiatan {$album->nama_album} #{$g}",
                ]);
            }
        }

        // ─── ARTIKELS ─────────────────────────────────────────────
        $artikelData = [
            ['judul' => 'Tips Sukses di Dunia Kerja untuk Alumni SMK', 'konten' => 'Dunia kerja bagi lulusan SMK sangatlah kompetitif. Namun dengan persiapan yang matang dan attitude yang baik, kesuksesan bisa diraih. Pertama, terus tingkatkan skill teknis sesuai bidang keahlian. Kedua, bangun jaringan profesional sejak dini. Ketiga, jangan takut untuk mencoba hal baru dan keluar dari zona nyaman.', 'status' => 'published', 'gambar' => 'https://picsum.photos/seed/art1/800/400'],
            ['judul' => 'Alumni Berprestasi: Dari Ruang Kelas Menuju Silicon Valley', 'konten' => 'Kisah inspiratif alumni kita yang berhasil menembus perusahaan teknologi terkemuka di dunia. Dengan ketekunan dan semangat belajar yang tidak pernah padam, mereka membuktikan bahwa mimpi bisa menjadi kenyataan.', 'status' => 'published', 'gambar' => 'https://picsum.photos/seed/art2/800/400'],
            ['judul' => 'Program Beasiswa Alumni untuk Adik Kelas', 'konten' => 'Ikatan Alumni dengan bangga memperkenalkan program beasiswa baru untuk siswa berprestasi yang membutuhkan dukungan finansial. Program ini adalah wujud nyata kepedulian alumni terhadap generasi penerus.', 'status' => 'draft', 'gambar' => 'https://picsum.photos/seed/art3/800/400'],
            ['judul' => 'Seminar Karir: Mempersiapkan Gen Z Memasuki Era Industri 4.0', 'konten' => 'Revolusi industri 4.0 membawa perubahan besar dalam dunia kerja. Otomasi, AI, dan big data mengubah cara kita bekerja. Alumni diundang untuk berbagi pengalaman dan wawasan kepada para siswa aktif.', 'status' => 'published', 'gambar' => 'https://picsum.photos/seed/art4/800/400'],
        ];

        foreach ($artikelData as $a) {
            Artikel::create([
                'user_id'      => $admin->id,
                'judul'        => $a['judul'],
                'slug'         => \Illuminate\Support\Str::slug($a['judul']),
                'konten'       => $a['konten'],
                'gambar_utama' => $a['gambar'],
                'status'       => $a['status'],
            ]);
        }

        // ─── EVENTS ──────────────────────────────────────────────
        $eventData = [
            ['nama' => 'Reuni Akbar Alumni 2025', 'deskripsi' => 'Pertemuan tahunan seluruh alumni lintas angkatan. Mari kumpul bersama, berbagi cerita sukses dan kenangan indah selama di sekolah.', 'mulai' => '2025-06-15 08:00:00', 'selesai' => '2025-06-15 17:00:00', 'lokasi' => 'GOR Kota Malang', 'banner' => 'https://picsum.photos/seed/ev1/800/400'],
            ['nama' => 'Seminar Teknologi & Karir 2025', 'deskripsi' => 'Seminar nasional yang menghadirkan pakar teknologi dan HRD perusahaan terkemuka. Gratis untuk alumni dan siswa aktif.', 'mulai' => '2025-07-20 09:00:00', 'selesai' => '2025-07-20 16:00:00', 'lokasi' => 'Aula Utama Sekolah', 'banner' => 'https://picsum.photos/seed/ev2/800/400'],
            ['nama' => 'Bakti Sosial & Donor Darah', 'deskripsi' => 'Kegiatan sosial berupa bakti sosial ke panti asuhan dan penyelenggaraan donor darah untuk masyarakat sekitar.', 'mulai' => '2025-08-10 07:00:00', 'selesai' => '2025-08-10 13:00:00', 'lokasi' => 'Panti Asuhan Harapan Bunda', 'banner' => 'https://picsum.photos/seed/ev3/800/400'],
            ['nama' => 'Workshop UI/UX Design Gratis', 'deskripsi' => 'Workshop intensif desain UI/UX yang dipandu oleh alumni yang bekerja di perusahaan teknologi terkemuka. Terbatas 30 peserta.', 'mulai' => '2025-09-05 08:30:00', 'selesai' => '2025-09-05 17:00:00', 'lokasi' => 'Lab Komputer Sekolah', 'banner' => 'https://picsum.photos/seed/ev4/800/400'],
        ];

        foreach ($eventData as $e) {
            Acara::create([
                'user_id'         => $admin->id,
                'nama_acara'      => $e['nama'],
                'deskripsi'       => $e['deskripsi'],
                'tanggal_mulai'   => $e['mulai'],
                'tanggal_selesai' => $e['selesai'],
                'lokasi'          => $e['lokasi'],
                'banner_image'    => $e['banner'],
            ]);
        }

        // ─── PERIODE KEPENGURUSAN ────────────────────────────────
        $periode1 = PeriodeKepengurusan::create([
            'nama_periode'    => 'Periode 2023 - 2025',
            'tanggal_mulai'   => '2023-01-01',
            'tanggal_selesai' => '2025-12-31',
        ]);

        $periode2 = PeriodeKepengurusan::create([
            'nama_periode'    => 'Periode 2025 - 2027',
            'tanggal_mulai'   => '2025-01-01',
            'tanggal_selesai' => '2027-12-31',
        ]);

        // ─── PENGURUS ALUMNI ──────────────────────────────────────
        $pengurusData = [
            ['alumni' => $alumniModels[0], 'periode' => $periode1, 'jabatan' => 'Ketua Umum',          'mulai' => '2023-01-01', 'selesai' => '2025-12-31'],
            ['alumni' => $alumniModels[1], 'periode' => $periode1, 'jabatan' => 'Wakil Ketua Umum',    'mulai' => '2023-01-01', 'selesai' => '2025-12-31'],
            ['alumni' => $alumniModels[2], 'periode' => $periode1, 'jabatan' => 'Sekretaris Jenderal', 'mulai' => '2023-01-01', 'selesai' => '2025-12-31'],
            ['alumni' => $alumniModels[3], 'periode' => $periode1, 'jabatan' => 'Bendahara Umum',      'mulai' => '2023-01-01', 'selesai' => '2025-12-31'],
            ['alumni' => $alumniModels[4], 'periode' => $periode1, 'jabatan' => 'Ketua Divisi IT & Digital', 'mulai' => '2023-01-01', 'selesai' => '2025-12-31'],
            ['alumni' => $alumniModels[5], 'periode' => $periode1, 'jabatan' => 'Ketua Divisi Humas & Jaringan', 'mulai' => '2023-01-01', 'selesai' => '2025-12-31'],
        ];

        foreach ($pengurusData as $p) {
            PengurusAlumni::create([
                'alumni_id'     => $p['alumni']->id,
                'periode_id'    => $p['periode']->id,
                'jabatan'       => $p['jabatan'],
                'periode_mulai' => $p['mulai'],
                'periode_selesai' => $p['selesai'],
            ]);
        }

        // ─── PRESTASI ALUMNI ─────────────────────────────────────
        PrestasiAlumni::create(['alumni_id' => $alumniModels[0]->id, 'nama_prestasi' => 'Juara 1 Olimpiade Informatika Nasional', 'tingkat' => 'Nasional', 'tahun_perolehan' => 2023, 'deskripsi' => 'Meraih juara pertama dalam kompetisi informatika tingkat nasional yang diikuti oleh 500+ peserta dari seluruh Indonesia.', 'sertifikat_url' => null]);
        PrestasiAlumni::create(['alumni_id' => $alumniModels[1]->id, 'nama_prestasi' => 'Best UI Design Award - Google Design Challenge', 'tingkat' => 'Internasional', 'tahun_perolehan' => 2024, 'deskripsi' => 'Memenangkan penghargaan desain internasional yang diselenggarakan oleh Google.', 'sertifikat_url' => null]);
        PrestasiAlumni::create(['alumni_id' => $alumniModels[2]->id, 'nama_prestasi' => 'Hackathon Winner - Data Fest 2024', 'tingkat' => 'Nasional', 'tahun_perolehan' => 2024, 'deskripsi' => 'Tim yang dipimpin berhasil memenangkan hackathon data science tingkat nasional.', 'sertifikat_url' => null]);
        PrestasiAlumni::create(['alumni_id' => $alumniModels[4]->id, 'nama_prestasi' => 'Sertifikasi CCNA - Cisco Certified', 'tingkat' => 'Internasional', 'tahun_perolehan' => 2023, 'deskripsi' => 'Memperoleh sertifikasi internasional Cisco CCNA dengan nilai sempurna.', 'sertifikat_url' => null]);

        // ─── TESTIMONIES ─────────────────────────────────────────
        Testimony::create(['alumni_id' => $alumniModels[0]->id, 'pesan' => 'Sekolah ini benar-benar membentuk karakter dan skill saya. Guru-guru yang berdedikasi membuat saya siap terjun ke dunia kerja. Terima kasih!', 'status' => 'approved']);
        Testimony::create(['alumni_id' => $alumniModels[1]->id, 'pesan' => 'Ilmu yang saya dapatkan di sini sangat relevan dengan pekerjaan saya sekarang. Kurikulum yang up-to-date dan fasilitas memadai menjadi bekal terbaik.', 'status' => 'approved']);
        Testimony::create(['alumni_id' => $alumniModels[2]->id, 'pesan' => 'Berkat bimbingan guru dan program magang yang disiapkan sekolah, saya langsung diterima bekerja di perusahaan impian. Semoga program ini terus berlanjut!', 'status' => 'approved']);
        Testimony::create(['alumni_id' => $alumniModels[3]->id, 'pesan' => 'Pengalaman berorganisasi di OSIS dan kegiatan extrakurikuler membentuk soft skill saya yang kini sangat dibutuhkan di dunia profesional.', 'status' => 'pending']);

        // ─── CONTENTS ────────────────────────────────────────────
        Content::create(['key_identifier' => 'hero_title',    'judul' => 'Judul Hero Section',    'isi' => 'Selamat Datang di Portal Alumni SMK Negeri 1', 'gambar' => null]);
        Content::create(['key_identifier' => 'hero_subtitle', 'judul' => 'Subtitle Hero Section', 'isi' => 'Bersama membangun generasi unggul dan berdedikasi untuk kemajuan bangsa', 'gambar' => null]);
        Content::create(['key_identifier' => 'visi',          'judul' => 'Visi Sekolah',          'isi' => 'Menjadi lembaga pendidikan kejuruan terbaik yang menghasilkan lulusan berkarakter, kompeten, dan berdaya saing global.', 'gambar' => null]);
        Content::create(['key_identifier' => 'misi',          'judul' => 'Misi Sekolah',          'isi' => "1. Menyelenggarakan pendidikan berkualitas\n2. Mengembangkan kompetensi kejuruan\n3. Membangun karakter dan akhlak mulia\n4. Menjalin kemitraan dengan dunia industri", 'gambar' => null]);
        Content::create(['key_identifier' => 'kontak',        'judul' => 'Informasi Kontak',      'isi' => "Jl. Pendidikan No. 1, Kota Malang 65100\nTelp: (0341) 123456\nEmail: info@smkn1malang.sch.id", 'gambar' => null]);
    }
}
