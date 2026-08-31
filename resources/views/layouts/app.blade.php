<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Alumni Portal') - Database Explorer & Dashboard</title>
    
    <!-- Google Fonts: Inter & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'Inter', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            300: '#a5b4fc',
                            400: '#818cf8',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                            950: '#1e1b4b',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #0f172a;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 9999px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #475569;
        }
    </style>
</head>
<body class="h-full text-slate-800 antialiased flex flex-col" x-data="{ mobileSidebarOpen: false }">

    <div class="flex h-screen overflow-hidden">
        <!-- SIDEBAR FOR DESKTOP -->
        <aside class="hidden lg:flex lg:flex-col lg:w-72 bg-slate-900 text-slate-200 border-r border-slate-800 shadow-xl z-20">
            <!-- Brand Logo -->
            <div class="h-16 flex items-center justify-between px-6 border-b border-slate-800/80 bg-slate-950/40">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 via-indigo-500 to-cyan-400 flex items-center justify-center text-white shadow-lg shadow-indigo-500/25 group-hover:scale-105 transition-transform duration-200">
                        <i class="fa-solid fa-graduation-cap text-lg"></i>
                    </div>
                    <div>
                        <h1 class="font-bold text-base text-white tracking-tight leading-tight">AlumniHub</h1>
                        <p class="text-[11px] text-indigo-300/80 font-medium">Database Management</p>
                    </div>
                </a>
            </div>

            <!-- Navigation Links -->
            <div class="flex-1 overflow-y-auto px-4 py-5 space-y-6 custom-scrollbar">
                
                <!-- Main Category -->
                <div>
                    <div class="px-3 mb-2 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                        Overview
                    </div>
                    <a href="{{ route('dashboard') }}" 
                       class="flex items-center justify-between px-3 py-2.5 rounded-xl font-medium text-sm transition-all duration-150 {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/30 font-semibold' : 'text-slate-300 hover:bg-slate-800/80 hover:text-white' }}">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-chart-pie w-5 text-center {{ request()->routeIs('dashboard') ? 'text-white' : 'text-indigo-400' }}"></i>
                            <span>Dashboard Utama</span>
                        </div>
                        <span class="text-[10px] uppercase font-bold tracking-wider px-2 py-0.5 rounded-full {{ request()->routeIs('dashboard') ? 'bg-indigo-800 text-white' : 'bg-slate-800 text-slate-400' }}">Live</span>
                    </a>
                </div>

                <!-- Master Data Tables Category -->
                <div>
                    <div class="px-3 mb-2 text-[11px] font-bold uppercase tracking-wider text-slate-400 flex items-center justify-between">
                        <span>Master Database</span>
                        <span class="text-[10px] text-slate-500 font-normal">12 Tabel</span>
                    </div>

                    <div class="space-y-1">
                        @php
                            $sidebarItems = [
                                ['key' => 'alumni', 'title' => 'Data Alumni', 'icon' => 'fa-user-graduate', 'color' => 'text-blue-400'],
                                ['key' => 'angkatan', 'title' => 'Angkatan', 'icon' => 'fa-graduation-cap', 'color' => 'text-amber-400'],
                                ['key' => 'kelas', 'title' => 'Kelas', 'icon' => 'fa-chalkboard-user', 'color' => 'text-emerald-400'],
                                ['key' => 'users', 'title' => 'Users / Akun', 'icon' => 'fa-users-gear', 'color' => 'text-purple-400'],
                                ['key' => 'pengurus_alumni', 'title' => 'Pengurus Alumni', 'icon' => 'fa-sitemap', 'color' => 'text-rose-400'],
                                ['key' => 'prestasi_alumni', 'title' => 'Prestasi Alumni', 'icon' => 'fa-trophy', 'color' => 'text-yellow-400'],
                                ['key' => 'artikels', 'title' => 'Artikel & Berita', 'icon' => 'fa-newspaper', 'color' => 'text-cyan-400'],
                                ['key' => 'acara', 'title' => 'Acara & Acara', 'icon' => 'fa-calendar-days', 'color' => 'text-orange-400'],
                                ['key' => 'albums', 'title' => 'Album Foto', 'icon' => 'fa-images', 'color' => 'text-pink-400'],
                                ['key' => 'galleries', 'title' => 'Galeri Foto', 'icon' => 'fa-camera-retro', 'color' => 'text-indigo-400'],
                                ['key' => 'testimonies', 'title' => 'Testimoni', 'icon' => 'fa-comment-dots', 'color' => 'text-teal-400'],
                                ['key' => 'contents', 'title' => 'Konten Halaman', 'icon' => 'fa-file-lines', 'color' => 'text-sky-400'],
                            ];
                        @endphp

                        @foreach($sidebarItems as $item)
                            @php
                                $isActive = request()->is('table/' . $item['key']);
                                $count = $counts[$item['key']] ?? 0;
                            @endphp
                            <a href="{{ route('table.show', $item['key']) }}" 
                               class="flex items-center justify-between px-3 py-2.5 rounded-xl font-medium text-sm transition-all duration-150 group {{ $isActive ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/30 font-semibold' : 'text-slate-300 hover:bg-slate-800/80 hover:text-white' }}">
                                <div class="flex items-center gap-3 truncate">
                                    <i class="fa-solid {{ $item['icon'] }} w-5 text-center {{ $isActive ? 'text-white' : $item['color'] }} group-hover:scale-110 transition-transform"></i>
                                    <span class="truncate">{{ $item['title'] }}</span>
                                </div>
                                <span class="text-xs font-semibold px-2 py-0.5 rounded-md transition-colors {{ $isActive ? 'bg-indigo-800 text-white' : 'bg-slate-800 text-slate-400 group-hover:bg-slate-700 group-hover:text-slate-200' }}">
                                    {{ $count }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>

            </div>

            <!-- Database Footer Status -->
            <div class="p-4 border-t border-slate-800/80 bg-slate-950/60">
                <div class="flex items-center justify-between text-xs">
                    <div class="flex items-center gap-2">
                        <span class="relative flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                        </span>
                        
                    </div>
                    <span class="text-slate-400 text-[10px] bg-slate-800 px-2 py-0.5 rounded">12 Tabel</span>
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
                 class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm"></div>

            <div x-show="mobileSidebarOpen" 
                 x-transition:enter="transition ease-in-out duration-300 transform"
                 x-transition:enter-start="-translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in-out duration-300 transform"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="-translate-x-full"
                 class="relative flex-1 flex flex-col max-w-xs w-full bg-slate-900 text-slate-200">
                
                <div class="h-16 flex items-center justify-between px-6 border-b border-slate-800 bg-slate-950">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-indigo-600 flex items-center justify-center text-white">
                            <i class="fa-solid fa-graduation-cap"></i>
                        </div>
                        <span class="font-bold text-white">AlumniHub</span>
                    </div>
                    <button @click="mobileSidebarOpen = false" class="text-slate-400 hover:text-white p-2">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto px-4 py-4 space-y-4 custom-scrollbar">
                    <a href="{{ route('dashboard') }}" 
                       class="flex items-center justify-between px-3 py-2.5 rounded-xl font-medium text-sm {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-chart-pie"></i>
                            <span>Dashboard Utama</span>
                        </div>
                    </a>

                    <div class="space-y-1">
                        @foreach($sidebarItems as $item)
                            <a href="{{ route('table.show', $item['key']) }}" 
                               @click="mobileSidebarOpen = false"
                               class="flex items-center justify-between px-3 py-2 rounded-xl text-sm font-medium {{ request()->is('table/' . $item['key']) ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">
                                <div class="flex items-center gap-3 truncate">
                                    <i class="fa-solid {{ $item['icon'] }} w-5 text-center"></i>
                                    <span>{{ $item['title'] }}</span>
                                </div>
                                <span class="text-xs bg-slate-800 px-2 py-0.5 rounded">{{ $counts[$item['key']] ?? 0 }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- MAIN WRAPPER -->
        <div class="flex-1 flex flex-col overflow-hidden bg-slate-100/70">
            
            <!-- TOP NAVBAR -->
            <header class="h-16 bg-white border-b border-slate-200/80 px-4 sm:px-8 flex items-center justify-between z-10 shadow-sm">
                <!-- Mobile Toggle & Breadcrumb -->
                <div class="flex items-center gap-4">
                    <button @click="mobileSidebarOpen = true" class="lg:hidden text-slate-600 hover:text-slate-900 p-2 rounded-lg hover:bg-slate-100">
                        <i class="fa-solid fa-bars-staggered text-lg"></i>
                    </button>

                    <div class="flex items-center gap-2 text-sm text-slate-500">
                        <a href="{{ route('dashboard') }}" class="hover:text-indigo-600 font-medium flex items-center gap-1.5">
                            <i class="fa-solid fa-house text-xs"></i>
                            <span>Home</span>
                        </a>
                        <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                        <span class="font-semibold text-slate-800">@yield('page_title', 'Dashboard')</span>
                    </div>
                </div>

                <!-- Right Header Actions -->
                <div class="flex items-center gap-3 sm:gap-4">
                  

                    <!-- Seed Data Button -->
                    <a href="{{ route('dashboard') }}" title="Refresh / Home" class="h-9 w-9 rounded-xl bg-slate-100 hover:bg-indigo-50 hover:text-indigo-600 text-slate-600 flex items-center justify-center transition-colors">
                        <i class="fa-solid fa-rotate text-sm"></i>
                    </a>

                    <!-- Admin Profile Badge -->
                    <div class="flex items-center gap-3 pl-3 border-l border-slate-200" x-data="{ profileOpen: false }">
                        <div class="relative">
                            <button @click="profileOpen = !profileOpen" @click.outside="profileOpen = false" class="flex items-center gap-3 group">
                                <img src="https://i.pravatar.cc/100?img=60" alt="Avatar" class="w-8 h-8 rounded-full ring-2 ring-indigo-500/20 object-cover">
                                <div class="hidden sm:block text-left">
                                    <p class="text-xs font-bold text-slate-800 leading-tight">{{ Auth::user()->username }}</p>
                                    <p class="text-[10px] text-slate-500 font-medium capitalize">{{ Auth::user()->role }}</p>
                                </div>
                                <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 hidden sm:block group-hover:text-slate-600"></i>
                            </button>

                            <div x-cloak x-show="profileOpen"
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 -translate-y-1"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 class="absolute right-0 mt-3 w-44 bg-white rounded-xl shadow-xl border border-slate-200 py-1.5 z-30">
                                <div class="px-3.5 py-2 border-b border-slate-100">
                                    <p class="text-xs font-bold text-slate-800 truncate">{{ Auth::user()->email }}</p>
                                </div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-3.5 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 flex items-center gap-2">
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
            <main class="flex-1 overflow-y-auto p-4 sm:p-8 custom-scrollbar">
                @if(session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                         class="max-w-7xl mx-auto mb-5 px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-semibold flex items-center gap-2">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif
                @yield('content')
            </main>

            <!-- FOOTER -->
            <footer class="bg-white border-t border-slate-200 px-6 py-3.5 text-xs text-slate-500 flex flex-col sm:flex-row items-center justify-between gap-2">
                <div class="flex items-center gap-2">
                    <span class="font-bold text-slate-700">AlumniHub Database System</span>
                    <span>•</span>
                    <span>Laravel v{{ Illuminate\Foundation\Application::VERSION }}</span>
                    <span>•</span>
                    <span>PHP v{{ PHP_VERSION }}</span>
                </div>
                <div class="text-slate-400">
                    Sistem Database & Portal Informasi Alumni
                </div>
            </footer>
        </div>
    </div>

</body>
</html>
