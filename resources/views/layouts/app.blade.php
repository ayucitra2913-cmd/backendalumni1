@php
    try {
        \Illuminate\Support\Facades\DB::connection()->getPdo();
        $dbConnected = true;
        $dbName = \Illuminate\Support\Facades\DB::connection()->getDatabaseName();
    } catch (\Throwable $e) {
        $dbConnected = false;
        $dbName = null;
    }

    $sidebarItems = [
        ['key' => 'alumni', 'title' => 'Data Alumni', 'icon' => 'fa-user-graduate', 'color' => 'text-blue-400'],
        ['key' => 'angkatan', 'title' => 'Tahun Angkatan', 'icon' => 'fa-graduation-cap', 'color' => 'text-amber-400'],
        ['key' => 'kelas', 'title' => 'Daftar Kelas', 'icon' => 'fa-chalkboard-user', 'color' => 'text-emerald-400'],
        ['key' => 'users', 'title' => 'Users / Akun', 'icon' => 'fa-users', 'color' => 'text-purple-400'],
        ['key' => 'periode_kepengurusan', 'title' => 'Periode Kepengurusan', 'icon' => 'fa-calendar-check', 'color' => 'text-teal-400'],
        ['key' => 'pengurus_alumni', 'title' => 'Pengurus Alumni', 'icon' => 'fa-user-tie', 'color' => 'text-rose-400'],
        ['key' => 'prestasi_alumni', 'title' => 'Prestasi Alumni', 'icon' => 'fa-trophy', 'color' => 'text-yellow-400'],
        ['key' => 'artikels', 'title' => 'Artikel & Berita', 'icon' => 'fa-newspaper', 'color' => 'text-cyan-400'],
        ['key' => 'acara', 'title' => 'Acara & Agenda', 'icon' => 'fa-calendar-days', 'color' => 'text-orange-400'],
        ['key' => 'albums', 'title' => 'Album Foto', 'icon' => 'fa-images', 'color' => 'text-pink-400'],
        ['key' => 'galleries', 'title' => 'Galeri Foto', 'icon' => 'fa-camera-retro', 'color' => 'text-indigo-400'],
        ['key' => 'testimonies', 'title' => 'Testimoni Alumni', 'icon' => 'fa-comment-dots', 'color' => 'text-teal-300'],
        ['key' => 'contents', 'title' => 'Konten Halaman', 'icon' => 'fa-file-lines', 'color' => 'text-sky-400'],
    ];
@endphp
<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AlumniHub') - Portal Database Alumni</title>
    
    <!-- Google Fonts: Plus Jakarta Sans & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Vite compiled Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js v3.14.1 -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>

    <!-- Theme & Text Size Persistent Script (Prevents FOUC) -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }

            const savedSize = localStorage.getItem('textSize') || 'normal';
            const scaleMap = { 'small': '14px', 'normal': '16px', 'large': '18px', 'xlarge': '20px' };
            if (scaleMap[savedSize]) {
                document.documentElement.style.fontSize = scaleMap[savedSize];
            }
        })();
    </script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="h-full text-slate-800 dark:text-slate-100 antialiased flex flex-col bg-slate-50/80 dark:bg-[#070b13] transition-colors duration-200" 
      x-data="{ 
          mobileSidebarOpen: false,
          darkMode: document.documentElement.classList.contains('dark'),
          textSize: localStorage.getItem('textSize') || 'normal',
          get textSizeLabel() {
              switch(this.textSize) {
                  case 'large': return 'Teks: Besar';
                  case 'xlarge': return 'Teks: Ekstra';
                  case 'small': return 'Teks: Kecil';
                  default: return 'Teks: Normal';
              }
          },
          toggleDarkMode() {
              this.darkMode = !this.darkMode;
              localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
              if (this.darkMode) {
                  document.documentElement.classList.add('dark');
              } else {
                  document.documentElement.classList.remove('dark');
              }
          },
          cycleTextSize() {
              const sizes = ['normal', 'large', 'xlarge', 'small'];
              const nextIdx = (sizes.indexOf(this.textSize) + 1) % sizes.length;
              this.textSize = sizes[nextIdx];
              localStorage.setItem('textSize', this.textSize);
              const scaleMap = { 'small': '14px', 'normal': '16px', 'large': '18px', 'xlarge': '20px' };
              document.documentElement.style.fontSize = scaleMap[this.textSize] || '16px';
          }
      }">

    <div class="flex h-screen overflow-hidden">
        <!-- SIDEBAR FOR DESKTOP -->
        <aside class="hidden lg:flex lg:flex-col lg:w-64 bg-[#0d1322] text-slate-200 border-r border-slate-800/80 shadow-2xl z-20">
            <!-- Brand Logo -->
            <div class="h-16 flex items-center justify-between px-5 border-b border-slate-800/60 bg-[#090d17]">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-500 flex items-center justify-center text-white shadow-lg shadow-indigo-500/25 group-hover:scale-105 transition-transform">
                        <i class="fa-solid fa-graduation-cap text-base"></i>
                    </div>
                    <div>
                        <h1 class="font-bold text-sm text-white tracking-tight leading-tight">AlumniHub</h1>
                        <p class="text-[10px] text-indigo-300 font-medium">Portal Database Alumni</p>
                    </div>
                </a>
            </div>

            <!-- Navigation Links -->
            <div class="flex-1 overflow-y-auto px-3.5 py-4 space-y-5 custom-scrollbar">
                
                <!-- Overview Category -->
                <div>
                    <div class="px-3 mb-2 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                        Overview
                    </div>
                    <a href="{{ route('dashboard') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-xl font-medium text-xs transition-all duration-150 {{ request()->routeIs('dashboard') ? 'bg-[#4f46e5] text-white shadow-md shadow-indigo-500/30 font-semibold' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white' }}">
                        <i class="fa-solid fa-chart-pie w-4 text-center {{ request()->routeIs('dashboard') ? 'text-white' : 'text-indigo-400' }}"></i>
                        <span>Dashboard Utama</span>
                    </a>
                </div>

                <!-- Master Data Tables Category -->
                <div>
                    <div class="px-3 mb-2 text-[10px] font-bold uppercase tracking-wider text-slate-400 flex items-center justify-between">
                        <span>Master Database</span>
                        <span class="text-[10px] bg-slate-800/90 text-slate-300 px-2 py-0.5 rounded font-mono font-medium">13 TABEL</span>
                    </div>

                    <div class="space-y-0.5">
                        @foreach($sidebarItems as $item)
                            @php
                                $isActive = request()->is('table/' . $item['key']);
                                $count = $counts[$item['key']] ?? 0;
                            @endphp
                            <a href="{{ route('table.show', $item['key']) }}" 
                               class="flex items-center justify-between px-3 py-2 rounded-xl font-medium text-xs transition-all duration-150 group {{ $isActive ? 'bg-[#4f46e5] text-white shadow-md shadow-indigo-500/30 font-semibold' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white' }}">
                                <div class="flex items-center gap-2.5 truncate">
                                    <i class="fa-solid {{ $item['icon'] }} w-4 text-center {{ $isActive ? 'text-white' : $item['color'] }}"></i>
                                    <span class="truncate">{{ $item['title'] }}</span>
                                </div>
                                <span class="text-[11px] font-semibold px-2 py-0.5 rounded-md transition-colors {{ $isActive ? 'bg-indigo-800 text-white' : 'bg-slate-800/80 text-slate-400 group-hover:text-slate-200' }}">
                                    {{ $count }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>

            </div>

            <!-- Bottom User Profile Card -->
            <div class="p-3 border-t border-slate-800/60 bg-[#090d17]">
                <div class="flex items-center justify-between px-2 py-1.5 rounded-xl bg-slate-900/60 border border-slate-800/50">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <div class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold text-xs flex-shrink-0 shadow-sm">
                            AD
                        </div>
                        <div class="truncate">
                            <p class="text-xs font-bold text-white leading-tight truncate">{{ Auth::user()->username ?? 'admin' }}</p>
                            <p class="text-[10px] text-slate-400 font-medium capitalize">{{ Auth::user()->role ?? 'Admin' }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" title="Logout" class="p-1.5 text-slate-400 hover:text-rose-400 rounded-lg hover:bg-slate-800 transition-colors">
                            <i class="fa-solid fa-arrow-right-from-bracket text-xs"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- MOBILE SIDEBAR DRAWER -->
        <div x-cloak x-show="mobileSidebarOpen" class="fixed inset-0 z-50 lg:hidden flex" role="dialog" aria-modal="true">
            <div x-show="mobileSidebarOpen" 
                 x-transition:enter="transition-opacity ease-linear duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-linear duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="mobileSidebarOpen = false"
                 class="fixed inset-0 bg-slate-950/80"></div>

            <div x-show="mobileSidebarOpen" 
                 x-transition:enter="transition ease-in-out duration-300 transform"
                 x-transition:enter-start="-translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in-out duration-300 transform"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="-translate-x-full"
                 class="relative flex-1 flex flex-col max-w-xs w-full bg-[#0d1322] text-slate-200">
                
                <div class="h-16 flex items-center justify-between px-5 border-b border-slate-800 bg-[#090d17]">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-blue-600 flex items-center justify-center text-white">
                            <i class="fa-solid fa-graduation-cap"></i>
                        </div>
                        <span class="font-bold text-white text-sm">AlumniHub</span>
                    </div>
                    <button @click="mobileSidebarOpen = false" class="text-slate-400 hover:text-white p-2">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto px-3.5 py-4 space-y-4 custom-scrollbar">
                    <a href="{{ route('dashboard') }}" 
                       @click="mobileSidebarOpen = false"
                       class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-medium {{ request()->routeIs('dashboard') ? 'bg-[#4f46e5] text-white font-semibold' : 'text-slate-300 hover:bg-slate-800' }}">
                        <div class="flex items-center gap-2.5">
                            <i class="fa-solid fa-chart-pie"></i>
                            <span>Dashboard Utama</span>
                        </div>
                    </a>

                    <div class="space-y-0.5">
                        @foreach($sidebarItems as $item)
                            <a href="{{ route('table.show', $item['key']) }}" 
                               @click="mobileSidebarOpen = false"
                               class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-medium {{ request()->is('table/' . $item['key']) ? 'bg-[#4f46e5] text-white font-semibold' : 'text-slate-300 hover:bg-slate-800' }}">
                                <div class="flex items-center gap-2.5 truncate">
                                    <i class="fa-solid {{ $item['icon'] }} w-4 text-center"></i>
                                    <span>{{ $item['title'] }}</span>
                                </div>
                                <span class="text-[11px] bg-slate-800 px-2 py-0.5 rounded">{{ $counts[$item['key']] ?? 0 }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- MAIN CONTENT WRAPPER -->
        <div class="flex-1 flex flex-col overflow-hidden bg-slate-50/80 dark:bg-[#070b13]">
            
            <!-- TOP NAVBAR -->
            <header class="h-16 bg-white dark:bg-[#0e1626] border-b border-slate-200/80 dark:border-slate-800/80 px-4 sm:px-8 flex items-center justify-between z-10 shadow-xs transition-colors duration-200">
                <!-- Left: Mobile Toggle & Breadcrumb -->
                <div class="flex items-center gap-3 sm:gap-4">
                    <button @click="mobileSidebarOpen = true" class="lg:hidden text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800">
                        <i class="fa-solid fa-bars-staggered text-base"></i>
                    </button>

                    <div class="flex items-center gap-2 text-xs sm:text-sm text-slate-500 dark:text-slate-400 font-medium">
                        <a href="{{ route('dashboard') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 flex items-center gap-1.5">
                            <i class="fa-solid fa-house text-xs"></i>
                            <span>Home</span>
                        </a>
                        <i class="fa-solid fa-chevron-right text-[10px] text-slate-300 dark:text-slate-600"></i>
                        <span class="font-bold text-slate-800 dark:text-slate-100">@yield('page_title', 'Dashboard Overview')</span>
                    </div>
                </div>

                <!-- Middle: Live Search Bar (Desktop) -->
                <div class="hidden md:flex items-center flex-1 max-w-md mx-6">
                    <div class="relative w-full">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="text" 
                               placeholder="Live search database (alumni, event, album...)..." 
                               class="w-full pl-9 pr-12 py-1.5 bg-slate-50 dark:bg-[#131d31] border border-slate-200 dark:border-slate-700/80 rounded-xl text-xs text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-[#18233a] transition-all">
                        <span class="absolute right-2.5 top-1/2 -translate-y-1/2 px-1.5 py-0.5 rounded text-[10px] font-mono text-slate-400 dark:text-slate-500 bg-slate-200/60 dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
                            ⌘ K
                        </span>
                    </div>
                </div>

                <!-- Right Header Actions -->
                <div class="flex items-center gap-2.5 sm:gap-3">
                    <!-- Text Size Toggle Button -->
                    <button type="button" 
                            @click="cycleTextSize()" 
                            title="Klik untuk mengubah ukuran teks tampilan"
                            class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700/80 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors shadow-2xs cursor-pointer">
                        <span class="font-black text-indigo-600 dark:text-indigo-400 text-xs">A</span>
                        <span class="text-[11px]" x-text="textSizeLabel">Teks: Normal</span>
                    </button>


                    <!-- Dark Mode Toggle Button -->
                    <button type="button" 
                            @click="toggleDarkMode()" 
                            :title="darkMode ? 'Beralih ke Mode Terang' : 'Beralih ke Mode Gelap'" 
                            class="h-8 w-8 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700/80 text-slate-600 dark:text-slate-300 flex items-center justify-center transition-colors cursor-pointer shadow-2xs">
                        <i :class="darkMode ? 'fa-solid fa-sun text-amber-400 text-xs' : 'fa-solid fa-moon text-slate-600 text-xs'"></i>
                    </button>

                    <!-- Refresh Button -->
                    <a href="{{ url()->current() }}" 
                       title="Refresh Halaman" 
                       class="h-8 w-8 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700/80 text-slate-600 dark:text-slate-300 flex items-center justify-center transition-colors shadow-2xs">
                        <i class="fa-solid fa-rotate text-xs"></i>
                    </a>

                    <!-- Admin Profile Badge -->
                    <div class="flex items-center gap-2 pl-2 border-l border-slate-200 dark:border-slate-800" x-data="{ profileOpen: false }">
                        <div class="relative">
                            <button @click="profileOpen = !profileOpen" @click.outside="profileOpen = false" class="flex items-center gap-2 group">
                                <div class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold text-xs ring-2 ring-indigo-500/20 shadow-sm">
                                    AD
                                </div>
                                <div class="hidden xl:block text-left">
                                    <p class="text-xs font-bold text-slate-800 dark:text-slate-100 leading-tight">{{ Auth::user()->username ?? 'admin' }}</p>
                                    <p class="text-[10px] text-slate-500 dark:text-slate-400 font-medium capitalize">{{ Auth::user()->role ?? 'Admin' }}</p>
                                </div>
                            </button>

                            <div x-cloak x-show="profileOpen"
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 -translate-y-1"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 class="absolute right-0 mt-3 w-48 bg-white dark:bg-[#0e1626] rounded-2xl shadow-xl border border-slate-200 dark:border-slate-800 py-1.5 z-30">
                                <div class="px-3.5 py-2 border-b border-slate-100 dark:border-slate-800">
                                    <p class="text-xs font-bold text-slate-800 dark:text-slate-100 truncate">{{ Auth::user()->email ?? 'admin@alumni.com' }}</p>
                                    <p class="text-[10px] text-slate-400">Masuk sebagai Administrator</p>
                                </div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-3.5 py-2 text-xs font-semibold text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/30 flex items-center gap-2">
                                        <i class="fa-solid fa-right-from-bracket"></i>
                                        <span>Logout</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- MAIN BODY CONTENT -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-7 custom-scrollbar">
                @if(session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                         class="max-w-7xl mx-auto mb-5 px-4 py-3 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800/60 text-emerald-700 dark:text-emerald-300 text-xs sm:text-sm font-semibold flex items-center gap-2 shadow-xs">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>

</body>
</html>
