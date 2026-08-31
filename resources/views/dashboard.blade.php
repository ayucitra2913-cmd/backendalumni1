@extends('layouts.app')

@section('title', 'Dashboard Overview')
@section('page_title', 'Dashboard Overview')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">

    <!-- WELCOME HERO BANNER -->
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white p-6 sm:p-8 shadow-xl shadow-slate-900/10 border border-slate-800">
        <div class="relative z-10 max-w-2xl">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/20 border border-indigo-400/30 text-indigo-300 text-xs font-semibold mb-3">
                <i class="fa-solid fa-sparkles text-amber-300"></i> Sistem Database Alumni Terintegrasi
            </div>
            <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white">
                Selamat Datang di <span class="bg-gradient-to-r from-indigo-400 to-cyan-300 bg-clip-text text-transparent">Portal Database Alumni</span>
            </h2>
            <p class="mt-2 text-sm sm:text-base text-slate-300 leading-relaxed">
                Kelola, pantau, dan eksplorasi data alumni, kepengurusan, prestasi, serta konten informasi institusi secara terpadu melalui panel manajemen database.
            </p>
        </div>
        
        <!-- Subtle Pattern Background -->
        <div class="absolute -right-12 -bottom-12 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute right-12 top-4 opacity-10 hidden sm:block">
            <i class="fa-solid fa-graduation-cap text-9xl"></i>
        </div>
    </div>

    <!-- KPI STATS CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <!-- Alumni Card -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Alumni</p>
                    <h3 class="text-3xl font-black text-slate-800 mt-1">{{ $counts['alumni'] }}</h3>
                    <p class="text-xs text-emerald-600 font-medium mt-1 flex items-center gap-1">
                        <i class="fa-solid fa-arrow-trend-up"></i> Terdata Lengkap
                    </p>
                </div>
                <div class="w-13 h-13 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center p-3 text-xl shadow-inner">
                    <i class="fa-solid fa-user-graduate"></i>
                </div>
            </div>
        </div>

        <!-- Angkatan Card -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Angkatan</p>
                    <h3 class="text-3xl font-black text-slate-800 mt-1">{{ $counts['angkatan'] }}</h3>
                    <p class="text-xs text-indigo-600 font-medium mt-1">
                        {{ $counts['kelas'] }} Kelas Terdaftar
                    </p>
                </div>
                <div class="w-13 h-13 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center p-3 text-xl shadow-inner">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
            </div>
        </div>

        <!-- Acara Card -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Agenda & Event</p>
                    <h3 class="text-3xl font-black text-slate-800 mt-1">{{ $counts['acara'] }}</h3>
                    <p class="text-xs text-orange-600 font-medium mt-1">
                        Kegiatan Aktif
                    </p>
                </div>
                <div class="w-13 h-13 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center p-3 text-xl shadow-inner">
                    <i class="fa-solid fa-calendar-days"></i>
                </div>
            </div>
        </div>

        <!-- Prestasi Card -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Prestasi Alumni</p>
                    <h3 class="text-3xl font-black text-slate-800 mt-1">{{ $counts['prestasi_alumni'] }}</h3>
                    <p class="text-xs text-yellow-600 font-medium mt-1">
                        Nasional & Internasional
                    </p>
                </div>
                <div class="w-13 h-13 rounded-2xl bg-yellow-50 text-yellow-600 flex items-center justify-center p-3 text-xl shadow-inner">
                    <i class="fa-solid fa-trophy"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- QUICK TABLE ACCESS GRID (ALL 12 TABLES) -->
    <div>
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Eksplorasi 12 Tabel Database</h3>
                <p class="text-xs text-slate-500">Klik salah satu kartu tabel untuk melihat, memfilter, dan mencari data</p>
            </div>
        </div>

        @php
            $tablesList = [
                ['key' => 'alumni', 'name' => 'alumni', 'label' => 'Data Alumni', 'icon' => 'fa-user-graduate', 'color' => 'from-blue-500 to-indigo-600', 'bg' => 'bg-blue-50 text-blue-700', 'desc' => 'Biodata alumni, NISN, no hp, alamat, & pekerjaan'],
                ['key' => 'angkatan', 'name' => 'angkatan', 'label' => 'Angkatan', 'icon' => 'fa-graduation-cap', 'color' => 'from-amber-500 to-orange-600', 'bg' => 'bg-amber-50 text-amber-700', 'desc' => 'Daftar tahun & nama julukan angkatan kelulusan'],
                ['key' => 'kelas', 'name' => 'kelas', 'label' => 'Kelas', 'icon' => 'fa-chalkboard-user', 'color' => 'from-emerald-500 to-teal-600', 'bg' => 'bg-emerald-50 text-emerald-700', 'desc' => 'Pembagian kelas jurusan dan relasi angkatan'],
                ['key' => 'users', 'name' => 'users', 'label' => 'Pengguna (Users)', 'icon' => 'fa-users-gear', 'color' => 'from-purple-500 to-pink-600', 'bg' => 'bg-purple-50 text-purple-700', 'desc' => 'Akun autentikasi, role admin, moderator & user'],
                ['key' => 'pengurus_alumni', 'name' => 'pengurus_alumni', 'label' => 'Pengurus Alumni', 'icon' => 'fa-sitemap', 'color' => 'from-rose-500 to-red-600', 'bg' => 'bg-rose-50 text-rose-700', 'desc' => 'Struktur organisasi dan masa bakti pengurus'],
                ['key' => 'prestasi_alumni', 'name' => 'prestasi_alumni', 'label' => 'Prestasi Alumni', 'icon' => 'fa-trophy', 'color' => 'from-yellow-500 to-amber-600', 'bg' => 'bg-yellow-50 text-yellow-700', 'desc' => 'Penghargaan dan sertifikat capaian alumni'],
                ['key' => 'artikels', 'name' => 'artikels', 'label' => 'Artikel & Berita', 'icon' => 'fa-newspaper', 'color' => 'from-cyan-500 to-blue-600', 'bg' => 'bg-cyan-50 text-cyan-700', 'desc' => 'Tulisan, tips karir, dan rilis berita kegiatan'],
                ['key' => 'acara', 'name' => 'acara', 'label' => 'Acara & Acara', 'icon' => 'fa-calendar-days', 'color' => 'from-orange-500 to-amber-600', 'bg' => 'bg-orange-50 text-orange-700', 'desc' => 'Jadwal reuni, seminar teknologi, & bakti sosial'],
                ['key' => 'albums', 'name' => 'albums', 'label' => 'Album Foto', 'icon' => 'fa-images', 'color' => 'from-pink-500 to-rose-600', 'bg' => 'bg-pink-50 text-pink-700', 'desc' => 'Koleksi album dokumentasi kegiatan alumni'],
                ['key' => 'galleries', 'name' => 'galleries', 'label' => 'Galeri Foto', 'icon' => 'fa-camera-retro', 'color' => 'from-indigo-500 to-purple-600', 'bg' => 'bg-indigo-50 text-indigo-700', 'desc' => 'File gambar dan caption foto dalam album'],
                ['key' => 'testimonies', 'name' => 'testimonies', 'label' => 'Testimoni', 'icon' => 'fa-comment-dots', 'color' => 'from-teal-500 to-emerald-600', 'bg' => 'bg-teal-50 text-teal-700', 'desc' => 'Kesan, pesan & ulasan pengalaman para alumni'],
                ['key' => 'contents', 'name' => 'contents', 'label' => 'Konten Halaman', 'icon' => 'fa-file-lines', 'color' => 'from-sky-500 to-cyan-600', 'bg' => 'bg-sky-50 text-sky-700', 'desc' => 'Teks konten dinamis (Hero, Visi-Misi, Kontak)'],
            ];
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($tablesList as $t)
                <a href="{{ route('table.show', $t['key']) }}" 
                   class="group bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-200 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br {{ $t['color'] }} text-white flex items-center justify-center text-base shadow-md group-hover:scale-110 transition-transform">
                                <i class="fa-solid {{ $t['icon'] }}"></i>
                            </div>
                            <span class="text-xs font-bold px-2.5 py-1 rounded-full {{ $t['bg'] }}">
                                {{ $counts[$t['key']] ?? 0 }} Data
                            </span>
                        </div>
                        <h4 class="font-bold text-slate-800 text-base group-hover:text-indigo-600 transition-colors">
                            {{ $t['label'] }}
                        </h4>
                        <code class="text-[11px] text-slate-400 font-mono">table: {{ $t['name'] }}</code>
                        <p class="text-xs text-slate-500 mt-2 leading-relaxed line-clamp-2">
                            {{ $t['desc'] }}
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-semibold text-indigo-600">
                        <span>Buka Tabel</span>
                        <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    <!-- SPLIT CONTENT SECTION: RECENT ALUMNI & EVENTS -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Recent Alumni Spotlight (2 Cols) -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-base font-bold text-slate-800">Profil Alumni Terbaru</h3>
                    <p class="text-xs text-slate-500">Data alumni yang telah terdata dalam sistem</p>
                </div>
                <a href="{{ route('table.show', 'alumni') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
                    Lihat Semua <i class="fa-solid fa-chevron-right text-[10px]"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach($recentAlumni as $alumni)
                    <div class="p-4 rounded-xl bg-slate-50/70 border border-slate-200/70 flex items-start gap-3 hover:bg-slate-50 transition-colors">
                        <img src="{{ $alumni->foto_profil }}" alt="{{ $alumni->nama_lengkap }}" class="w-12 h-12 rounded-xl object-cover ring-2 ring-indigo-500/20 shadow-sm flex-shrink-0">
                        <div class="min-w-0 flex-1">
                            <h4 class="text-sm font-bold text-slate-800 truncate leading-snug">{{ $alumni->nama_lengkap }}</h4>
                            <p class="text-xs text-indigo-600 font-medium truncate mt-0.5">{{ $alumni->pekerjaan_saat_ini ?? 'Belum Diisi' }}</p>
                            <div class="flex items-center gap-2 mt-2 text-[11px] text-slate-500">
                                <span class="bg-slate-200/80 px-2 py-0.5 rounded font-medium">{{ $alumni->angkatan->nama_angkatan ?? '-' }}</span>
                                <span class="truncate">{{ $alumni->kelas->nama_kelas ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Upcoming Acara Timeline (1 Col) -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="text-base font-bold text-slate-800">Agenda & Acara</h3>
                        <p class="text-xs text-slate-500">Jadwal kegiatan terdekat</p>
                    </div>
                    <a href="{{ route('table.show', 'acara') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">
                        Detail
                    </a>
                </div>

                <div class="space-y-4">
                    @foreach($upcomingAcara as $ev)
                        <div class="p-3.5 rounded-xl border border-slate-100 bg-slate-50/50 hover:bg-slate-50 transition-colors">
                            <div class="flex items-center justify-between text-xs text-slate-500 mb-1">
                                <span class="font-semibold text-orange-600 flex items-center gap-1">
                                    <i class="fa-regular fa-calendar"></i>
                                    {{ $ev->tanggal_mulai ? $ev->tanggal_mulai->format('d M Y') : '-' }}
                                </span>
                                <span class="truncate text-[11px] max-w-[120px] text-slate-400">
                                    <i class="fa-solid fa-location-dot"></i> {{ $ev->lokasi }}
                                </span>
                            </div>
                            <h4 class="font-bold text-slate-800 text-sm leading-snug line-clamp-1">{{ $ev->nama_acara }}</h4>
                            <p class="text-xs text-slate-500 mt-1 line-clamp-2">{{ $ev->deskripsi }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-4 pt-3 border-t border-slate-100">
                <a href="{{ route('table.show', 'events') }}" class="w-full py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-semibold text-xs rounded-xl flex items-center justify-center gap-2 transition-colors">
                    <i class="fa-solid fa-calendar-plus"></i>
                    <span>Kelola Semua Acara</span>
                </a>
            </div>
        </div>

    </div>

</div>
@endsection
