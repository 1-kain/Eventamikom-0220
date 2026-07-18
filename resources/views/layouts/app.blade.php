<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AmikomEventHub - Temukan Event Seru!</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-900">

    <!-- Navigation -->
    <nav class="glass sticky top-8 z-40 mx-4 mt-4 px-6 py-4 rounded-2xl border border-white/20 shadow-lg flex justify-between items-center">
        <div class="flex items-center gap-2">
            <a href="{{ route('home') }}" class="flex items-center gap-2 hover:opacity-80 transition group">
                <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white font-bold text-xl group-hover:bg-indigo-700 transition">
                    AH
                </div>
                <span class="text-xl font-bold tracking-tight text-slate-900">AmikomEventHub</span>
            </a>
        </div>
        
        <!-- Wadah Menu: Ditambah items-center agar tombol dan teks sejajar -->
        <div class="hidden md:flex items-center gap-8 font-medium">
            
            <!-- 🌟 MENU DINAMIS: Hanya muncul jika User sudah Login -->
            @auth
                <a href="{{ route('user.tickets') }}" class="hover:text-indigo-600 transition {{ Request::routeIs('user.tickets') ? 'text-indigo-600 font-bold' : 'text-slate-600' }}">
                    Tiket Saya
                </a>
                <a href="{{ route('user.certificates') }}" class="hover:text-indigo-600 transition {{ Request::routeIs('user.certificates') ? 'text-indigo-600 font-bold' : 'text-slate-600' }}">
                    Sertifikat
                </a>
            @endauth
            <!-- 🌟 LOGIKA LOGIN/LOGOUT (Sesuai instruksi: Di kiri Jelajahi) -->
            @guest
                <a href="{{ route('login') }}" class="px-5 py-2 text-sm bg-indigo-100 text-indigo-700 rounded-xl font-bold hover:bg-indigo-200 transition">
                    Login
                </a>
            @endguest

            @auth
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="px-5 py-2 text-sm bg-red-50 text-red-600 border border-red-200 rounded-xl font-bold hover:bg-red-500 hover:text-white transition">
                        Logout
                    </button>
                </form>
            @endauth
            <!-- 🌟 END LOGIKA -->

            <a href="#" class="text-indigo-600">Jelajahi</a>
            <a href="#" class="hover:text-indigo-600 transition">Kategori</a>
            <a href="#" class="hover:text-indigo-600 transition">Tentang Kami</a>
        </div>
    </nav>

    @yield('content')   

    <!-- Footer -->
    <footer class="bg-indigo-900 text-indigo-100 py-20 px-6 mt-20">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-12">
            <div class="space-y-4 col-span-2">
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-indigo-900 font-bold text-xl">
                        AH
                    </div>
                    <span class="text-2xl font-bold text-white">AmikomEventHub</span>
                </div>
                <p class="max-w-xs text-indigo-300">Platform reservasi tiket event online terbaik untuk mahasiswa dan penyelenggara profesional.</p>
            </div>
            <div>
                <h4 class="text-white font-bold mb-6">Navigasi</h4>
                <ul class="space-y-4">
                    <li><a href="#" class="hover:text-white transition">Home</a></li>
                    <li><a href="#" class="hover:text-white transition">Semua Event</a></li>
                    <li><a href="#" class="hover:text-white transition">Cara Bayar</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-bold mb-6">Hubungi Kami</h4>
                <ul class="space-y-4">
                    <li>support@eventtiket.com</li>
                    <li>+62 812 3456 7890</li>
                </ul>
            </div>
        </div>
        <div class="max-w-7xl mx-auto pt-12 mt-12 border-t border-indigo-800 text-center text-indigo-400 text-sm">
            &copy; 2024 AmikomEventHub. Built with Laravel & Tailwind CSS.
        </div>
    </footer>

</body>

</html>