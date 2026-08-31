<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - AlumniHub</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

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
                            50: '#eef2ff', 100: '#e0e7ff', 200: '#c7d2fe', 300: '#a5b4fc',
                            400: '#818cf8', 500: '#6366f1', 600: '#4f46e5', 700: '#4338ca',
                            800: '#3730a3', 900: '#312e81', 950: '#1e1b4b',
                        }
                    }
                }
            }
        }
    </script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            background-color: #0b1120;
            background-image:
                radial-gradient(circle at 15% 20%, rgba(99, 102, 241, 0.25), transparent 40%),
                radial-gradient(circle at 85% 80%, rgba(34, 211, 238, 0.18), transparent 40%);
        }
        .glass {
            background: rgba(15, 23, 42, 0.55);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }
    </style>
</head>
<body class="h-full font-sans antialiased flex items-center justify-center min-h-screen p-4">

    <div class="w-full max-w-sm" x-data="{ showPassword: false }">

        <!-- Brand -->
        <div class="flex flex-col items-center mb-8">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-indigo-600 via-indigo-500 to-cyan-400 flex items-center justify-center text-white shadow-lg shadow-indigo-500/30 mb-4">
                <i class="fa-solid fa-graduation-cap text-2xl"></i>
            </div>
            <h1 class="font-extrabold text-2xl text-white tracking-tight">AlumniHub</h1>
            <p class="text-xs text-indigo-300/70 font-medium mt-1">Admin Database Management</p>
        </div>

        <!-- Card -->
        <div class="glass rounded-2xl border border-white/10 shadow-2xl p-7 sm:p-8">
            <div class="mb-6">
                <h2 class="text-lg font-bold text-white">Masuk ke Panel Admin</h2>
                <p class="text-xs text-slate-400 mt-1">Silakan masukkan kredensial Anda untuk melanjutkan.</p>
            </div>

            @if ($errors->any())
                <div class="mb-5 px-4 py-3 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-300 text-xs font-medium flex items-start gap-2">
                    <i class="fa-solid fa-circle-exclamation mt-0.5"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-300 mb-1.5">Email</label>
                    <div class="relative">
                        <i class="fa-regular fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500 text-sm"></i>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                               placeholder="admin@alumni.sch.id"
                               class="w-full pl-10 pr-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white/10 transition-all">
                    </div>
                    @error('email')
                        <p class="text-[11px] text-rose-400 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-300 mb-1.5">Password</label>
                    <div class="relative">
                        <i class="fa-solid fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500 text-sm"></i>
                        <input :type="showPassword ? 'text' : 'password'" name="password" id="password" required
                               placeholder="••••••••"
                               class="w-full pl-10 pr-11 py-2.5 bg-white/5 border border-white/10 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white/10 transition-all">
                        <button type="button" @click="showPassword = !showPassword"
                                class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-300 text-sm">
                            <i class="fa-solid" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-[11px] text-rose-400 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-white/20 bg-white/5 text-indigo-600 focus:ring-indigo-500 focus:ring-offset-0">
                        <span class="text-xs text-slate-400 font-medium">Taruh di otak ya</span>
                    </label>
                </div>

                <!-- Submit -->
                <button type="submit"
                        class="w-full py-2.5 mt-2 bg-gradient-to-r from-indigo-600 to-indigo-500 hover:from-indigo-500 hover:to-indigo-400 text-white rounded-xl text-sm font-bold shadow-lg shadow-indigo-600/30 transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    <span>Masuk</span>
                </button>
            </form>
        </div>

        <p class="text-center text-[11px] text-slate-500 mt-6">
            &copy; {{ date('Y') }} AlumniHub Database System
        </p>
    </div>

</body>
</html>
