<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login AmikomEventHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-indigo-900 text-white min-h-screen flex items-center justify-center p-6">
    <div class="max-w-md w-full bg-white text-slate-900 rounded-[2rem] p-8 shadow-2xl">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-indigo-600 rounded-2xl flex items-center justify-center text-white font-bold text-2xl mx-auto mb-4">AH</div>
            <h1 class="text-2xl font-black">Admin Login</h1>
            <p class="text-slate-500">AmikomEventHub Dashboard</p>
        </div>
        
        @if(session('error'))
            <div class="bg-red-100 text-red-600 p-4 rounded-xl mb-6 font-bold text-sm text-center">
                {{ session('error') }}
            </div>
        @endif
        @if($errors->any())
            <div class="bg-red-100 text-red-600 p-4 rounded-xl mb-6 font-bold text-sm text-center">
                Email atau Password yang Anda berikan tidak terdaftar di database kami.
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Email</label>
                <input type="email" name="email" class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium" required>
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Password</label>
                <input type="password" name="password" class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium" required>
            </div>
            <button type="submit" class="w-full py-4 bg-indigo-600 text-white rounded-2xl font-black text-xl shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition">Masuk</button>
        </form>

        <!-- ========================================== -->
        <!-- ELEMEN BARU: PEMBATAS & TOMBOL GOOGLE SSO  -->
        <!-- ========================================== -->
        <div class="mt-6 space-y-4">
            <div class="relative flex items-center py-2">
                <div class="flex-grow border-t border-slate-200"></div>
                <span class="flex-shrink mx-4 text-slate-400 text-sm font-semibold uppercase tracking-wider">atau</span>
                <div class="flex-grow border-t border-slate-200"></div>
            </div>

            <a href="{{ route('auth.google') }}" class="w-full py-4 bg-white border-2 border-slate-100 text-slate-700 rounded-2xl font-bold text-base shadow-sm hover:bg-slate-50 hover:border-slate-200 transition flex items-center justify-center gap-3">
                <svg class="w-6 h-6" viewBox="0 0 24 24">
                    <path fill="#EA4335" d="M12.24 10.285V14.4h6.887c-.648 2.41-2.519 4.114-5.136 4.114A5.76 5.76 0 0 1 8.2 12.76a5.76 5.76 0 0 1 5.791-5.76c1.55 0 2.968.568 4.072 1.503l3.193-3.193A9.92 9.92 0 0 0 13.992 2a9.97 9.97 0 0 0-9.965 9.96C4.027 17.485 8.494 22 13.992 22c5.448 0 9.898-4.415 9.898-9.96a10.07 10.07 0 0 0-.164-1.755H12.24Z"/>
                </svg>
                <span>Masuk dengan Google</span>
            </a>
        </div>
        <!-- ========================================== -->
    </div>
</body>
</html>