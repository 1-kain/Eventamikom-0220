@extends('layouts.admin')

@section('content')
<div class="p-8 w-full">
    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-100 border border-emerald-200 text-emerald-800 rounded-xl font-bold flex items-center gap-3 shadow-sm animate-fade-in">
            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Kelola Kategori</h1>
            <p class="text-slate-500 mt-1">Daftar kategori event yang tersedia di platform secara real-time.</p>
        </div>
        
        <button onclick="document.getElementById('modal-tambah').showModal()" class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl shadow-md hover:bg-indigo-700 transition font-bold flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Kategori
        </button>
    </div>

    <div class="mb-6">
        <form action="{{ route('admin.categories.index') }}" method="GET" class="flex gap-3 max-w-md">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </span>
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama kategori..." class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm">
            </div>
            <button type="submit" class="px-4 py-2.5 bg-slate-800 text-white text-sm font-bold rounded-xl hover:bg-slate-900 transition">
                Cari
            </button>
            @if($search)
                <a href="{{ route('admin.categories.index') }}" class="px-4 py-2.5 bg-slate-200 text-slate-700 text-sm font-bold rounded-xl hover:bg-slate-300 transition flex items-center">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <div class="bg-white rounded-2xl border shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b text-sm text-slate-500 uppercase tracking-wider">
                    <th class="px-8 py-4 font-bold w-20">No</th>
                    <th class="px-8 py-4 font-bold">Nama Kategori</th>
                    <th class="px-8 py-4 font-bold">Slug URL</th>
                    <th class="px-8 py-4 font-bold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($categories as $index => $category)
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-8 py-6 font-medium text-slate-600">{{ $index + 1 }}</td>
                    <td class="px-8 py-6 font-bold text-slate-900">{{ $category->name }}</td>
                    <td class="px-8 py-6 text-slate-500 font-mono text-xs">{{ $category->slug }}</td>
                    <td class="px-8 py-6 flex justify-end gap-2">
                        <button onclick="document.getElementById('modal-edit-{{ $category->id }}').showModal()" class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl hover:bg-indigo-600 hover:text-white transition text-sm font-bold">
                            Edit
                        </button>
                        
                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2.5 bg-rose-50 text-rose-600 rounded-xl hover:bg-rose-600 hover:text-white transition text-sm font-bold">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>

                <dialog id="modal-edit-{{ $category->id }}" class="backdrop:bg-slate-900/50 p-6 rounded-2xl border shadow-xl max-w-md w-full bg-white">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-slate-900">Ubah Nama Kategori</h3>
                        <button onclick="document.getElementById('modal-edit-{{ $category->id }}').close()" class="text-slate-400 hover:text-slate-600">
                            ✕
                        </button>
                    </div>
                    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Nama Kategori</label>
                                <input type="text" name="name" value="{{ $category->name }}" required class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none">
                            </div>
                            <div class="flex justify-end gap-2 pt-2">
                                <button type="button" onclick="document.getElementById('modal-edit-{{ $category->id }}').close()" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl font-bold text-sm hover:bg-slate-200 transition">Batal</button>
                                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-xl font-bold text-sm hover:bg-indigo-700 transition">Simpan Perubahan</button>
                            </div>
                        </div>
                    </form>
                </dialog>
                @empty
                <tr>
                    <td colspan="4" class="px-8 py-12 text-center text-slate-400 font-medium">
                        Tidak ada data kategori yang ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<dialog id="modal-tambah" class="backdrop:bg-slate-900/50 p-6 rounded-2xl border shadow-xl max-w-md w-full bg-white">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-xl font-bold text-slate-900">Tambah Kategori Baru</h3>
        <button onclick="document.getElementById('modal-tambah').close()" class="text-slate-400 hover:text-slate-600">
            ✕
        </button>
    </div>
    <form action="{{ route('admin.categories.store') }}" method="POST">
        @csrf
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Nama Kategori</label>
                <input type="text" name="name" placeholder="Contoh: Workshop, Webinar, dll." required class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="document.getElementById('modal-tambah').close()" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl font-bold text-sm hover:bg-slate-200 transition">Batal</button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-xl font-bold text-sm hover:bg-indigo-700 transition">Tambah Data</button>
            </div>
        </div>
    </form>
</dialog>
@endsection