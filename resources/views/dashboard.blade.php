@extends('layouts.app')

@section('title', 'Dashboard Overview')
@section('page_title', 'Dashboard Overview')

@section('content')
<div class="max-w-7xl mx-auto space-y-7">

    <!-- WELCOME HERO BANNER -->
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-[#11192e] via-[#1a233d] to-[#11192e] dark:from-[#0b101d] dark:via-[#131b2e] dark:to-[#0b101d] text-white p-7 sm:p-9 shadow-lg border border-slate-800/80">
        <div class="relative z-10 max-w-2xl">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/20 border border-indigo-400/30 text-indigo-300 text-xs font-semibold mb-3.5">
                Sistem Database Alumni Terintegrasi
            </div>
            <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white leading-tight">
                Selamat Datang di <span class="bg-gradient-to-r from-indigo-400 to-blue-400 bg-clip-text text-transparent">Portal Database Alumni</span>
            </h2>
            <p class="mt-2.5 text-xs sm:text-sm text-slate-300 leading-relaxed font-normal">
                Kelola, pantau, dan eksplorasi data alumni, kepengurusan, prestasi, serta konten informasi institusi secara terpadu melalui panel manajemen database.
            </p>
        </div>
        
        <!-- Subtle Pattern Background -->
        <div class="absolute -right-10 -bottom-10 w-60 h-60 bg-indigo-500/10 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute right-8 top-1/2 -translate-y-1/2 opacity-15 hidden md:block pointer-events-none">
            <i class="fa-solid fa-graduation-cap text-8xl text-indigo-300"></i>
        </div>
    </div>

    <!-- 4 KPI STATS CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        <!-- 1. Alumni Card -->
        <div class="bg-white dark:bg-[#0e1626] rounded-2xl p-5 border border-slate-200/80 dark:border-slate-800/80 shadow-xs hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">TOTAL ALUMNI</p>
                    <h3 class="text-2xl sm:text-3xl font-black text-slate-800 dark:text-white mt-1">{{ $counts['alumni'] ?? 0 }}</h3>
                    <p class="text-xs text-emerald-600 dark:text-emerald-400 font-semibold mt-1 flex items-center gap-1">
                        <i class="fa-solid fa-arrow-trend-up text-[11px]"></i> <span>Terdata Lengkap</span>
                    </p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-blue-500 text-white flex items-center justify-center text-xl shadow-md shadow-blue-500/20">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
            </div>
        </div>

        <!-- 2. Angkatan Card -->
        <div class="bg-white dark:bg-[#0e1626] rounded-2xl p-5 border border-slate-200/80 dark:border-slate-800/80 shadow-xs hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">TOTAL ANGKATAN</p>
                    <h3 class="text-2xl sm:text-3xl font-black text-slate-800 dark:text-white mt-1">{{ $counts['angkatan'] ?? 0 }}</h3>
                    <p class="text-xs text-indigo-600 dark:text-indigo-400 font-semibold mt-1">
                        {{ $counts['kelas'] ?? 0 }} Kelas Terdaftar
                    </p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-amber-500 text-white flex items-center justify-center text-xl shadow-md shadow-amber-500/20">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
            </div>
        </div>

        <!-- 3. Acara Card -->
        <div class="bg-white dark:bg-[#0e1626] rounded-2xl p-5 border border-slate-200/80 dark:border-slate-800/80 shadow-xs hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">ACARA & AGENDA</p>
                    <h3 class="text-2xl sm:text-3xl font-black text-slate-800 dark:text-white mt-1">{{ $counts['acara'] ?? 0 }}</h3>
                    <p class="text-xs text-orange-600 dark:text-orange-400 font-semibold mt-1">
                        Kegiatan Aktif
                    </p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-orange-500 text-white flex items-center justify-center text-xl shadow-md shadow-orange-500/20">
                    <i class="fa-solid fa-calendar-days"></i>
                </div>
            </div>
        </div>

        <!-- 4. Prestasi Card -->
        <div class="bg-white dark:bg-[#0e1626] rounded-2xl p-5 border border-slate-200/80 dark:border-slate-800/80 shadow-xs hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">PRESTASI ALUMNI</p>
                    <h3 class="text-2xl sm:text-3xl font-black text-slate-800 dark:text-white mt-1">{{ $counts['prestasi_alumni'] ?? 0 }}</h3>
                    <p class="text-xs text-amber-600 dark:text-amber-400 font-semibold mt-1">
                        Nasional & Internasional
                    </p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-yellow-500 text-white flex items-center justify-center text-xl shadow-md shadow-yellow-500/20">
                    <i class="fa-solid fa-trophy"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- QUICK TABLE ACCESS GRID (ALL 13 TABLES) -->
    <div>
        <div class="mb-4">
            <h3 class="text-lg font-bold text-slate-800 dark:text-white tracking-tight">Eksplorasi 13 Tabel Database</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Klik salah satu kartu tabel untuk melihat, memfilter, dan mencari data</p>
        </div>

        @php
            $tablesList = [
                ['key' => 'alumni', 'name' => 'alumni', 'label' => 'Data Alumni', 'icon' => 'fa-user-graduate', 'iconBg' => 'bg-blue-600 text-white', 'badgeBg' => 'bg-blue-50 dark:bg-blue-950/50 text-blue-700 dark:text-blue-300', 'desc' => 'Biodata alumni, NISN, sosial media, & pekerjaan'],
                ['key' => 'angkatan', 'name' => 'angkatan', 'label' => 'Angkatan', 'icon' => 'fa-graduation-cap', 'iconBg' => 'bg-amber-500 text-white', 'badgeBg' => 'bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-300', 'desc' => 'Daftar tahun & nama julukan angkatan kelulusan'],
                ['key' => 'kelas', 'name' => 'kelas', 'label' => 'Kelas', 'icon' => 'fa-chalkboard-user', 'iconBg' => 'bg-emerald-600 text-white', 'badgeBg' => 'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300', 'desc' => 'Pembagian kelas jurusan dan relasi angkatan'],
                ['key' => 'users', 'name' => 'users', 'label' => 'Pengguna (Users)', 'icon' => 'fa-users', 'iconBg' => 'bg-purple-600 text-white', 'badgeBg' => 'bg-purple-50 dark:bg-purple-950/50 text-purple-700 dark:text-purple-300', 'desc' => 'Akun autentikasi, role admin, moderator & user'],
                ['key' => 'periode_kepengurusan', 'name' => 'periode_kepengurusan', 'label' => 'Periode Kepengurusan', 'icon' => 'fa-calendar-check', 'iconBg' => 'bg-teal-600 text-white', 'badgeBg' => 'bg-teal-50 dark:bg-teal-950/50 text-teal-700 dark:text-teal-300', 'desc' => 'Masa bakti dan periode kepengurusan alumni'],
                ['key' => 'pengurus_alumni', 'name' => 'pengurus_alumni', 'label' => 'Pengurus Alumni', 'icon' => 'fa-user-tie', 'iconBg' => 'bg-rose-600 text-white', 'badgeBg' => 'bg-rose-50 dark:bg-rose-950/50 text-rose-700 dark:text-rose-300', 'desc' => 'Struktur organisasi dan masa bakti pengurus'],
                ['key' => 'prestasi_alumni', 'name' => 'prestasi_alumni', 'label' => 'Prestasi Alumni', 'icon' => 'fa-trophy', 'iconBg' => 'bg-yellow-500 text-white', 'badgeBg' => 'bg-yellow-50 dark:bg-yellow-950/50 text-yellow-700 dark:text-yellow-300', 'desc' => 'Penghargaan dan sertifikat capaian alumni'],
                ['key' => 'artikels', 'name' => 'artikels', 'label' => 'Artikel & Berita', 'icon' => 'fa-newspaper', 'iconBg' => 'bg-cyan-600 text-white', 'badgeBg' => 'bg-cyan-50 dark:bg-cyan-950/50 text-cyan-700 dark:text-cyan-300', 'desc' => 'Tulisan, tips karir, dan rilis berita kegiatan'],
                ['key' => 'acara', 'name' => 'acara', 'label' => 'Acara & Agenda', 'icon' => 'fa-calendar-days', 'iconBg' => 'bg-orange-500 text-white', 'badgeBg' => 'bg-orange-50 dark:bg-orange-950/50 text-orange-700 dark:text-orange-300', 'desc' => 'Jadwal reuni, seminar teknologi, & bakti sosial'],
                ['key' => 'albums', 'name' => 'albums', 'label' => 'Album Foto', 'icon' => 'fa-images', 'iconBg' => 'bg-pink-600 text-white', 'badgeBg' => 'bg-pink-50 dark:bg-pink-950/50 text-pink-700 dark:text-pink-300', 'desc' => 'Koleksi album dokumentasi kegiatan alumni'],
                ['key' => 'galleries', 'name' => 'galleries', 'label' => 'Galeri Foto', 'icon' => 'fa-camera-retro', 'iconBg' => 'bg-indigo-600 text-white', 'badgeBg' => 'bg-indigo-50 dark:bg-indigo-950/50 text-indigo-700 dark:text-indigo-300', 'desc' => 'File gambar dan caption foto dalam album'],
                ['key' => 'testimonies', 'name' => 'testimonies', 'label' => 'Testimoni Alumni', 'icon' => 'fa-comment-dots', 'iconBg' => 'bg-teal-500 text-white', 'badgeBg' => 'bg-teal-50 dark:bg-teal-950/50 text-teal-700 dark:text-teal-300', 'desc' => 'Kesan, pesan & ulasan pengalaman para alumni'],
                ['key' => 'contents', 'name' => 'contents', 'label' => 'Konten Halaman', 'icon' => 'fa-file-lines', 'iconBg' => 'bg-sky-600 text-white', 'badgeBg' => 'bg-sky-50 dark:bg-sky-950/50 text-sky-700 dark:text-sky-300', 'desc' => 'Teks konten dinamis (Hero, Visi-Misi, Kontak)'],
            ];
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-5">
            @foreach($tablesList as $t)
                <a href="{{ route('table.show', $t['key']) }}" 
                   class="group bg-white dark:bg-[#0e1626] rounded-2xl p-5 border border-slate-200/80 dark:border-slate-800/80 shadow-2xs hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-3.5">
                            <div class="w-10 h-10 rounded-xl {{ $t['iconBg'] }} flex items-center justify-center text-sm shadow-sm group-hover:scale-105 transition-transform">
                                <i class="fa-solid {{ $t['icon'] }}"></i>
                            </div>
                            <span class="text-[11px] font-bold px-2.5 py-0.5 rounded-full {{ $t['badgeBg'] }}">
                                {{ $counts[$t['key']] ?? 0 }} Data
                            </span>
                        </div>
                        <h4 class="font-bold text-slate-800 dark:text-white text-sm group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                            {{ $t['label'] }}
                        </h4>
                        <code class="text-[10px] text-slate-400 dark:text-slate-500 font-mono block mt-0.5">table: {{ $t['name'] }}</code>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 leading-relaxed line-clamp-2">
                            {{ $t['desc'] }}
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800/80 flex items-center justify-between text-xs font-semibold text-indigo-600 dark:text-indigo-400">
                        <span>Buka Tabel</span>
                        <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

</div>
@endsection
