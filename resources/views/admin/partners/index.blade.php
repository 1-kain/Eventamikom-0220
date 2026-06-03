@extends('layouts.admin')

@section('content')
<div class="p-8 w-full">
    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-100 border border-emerald-200 text-emerald-800 rounded-xl font-bold flex items-center gap-3 shadow-sm">
            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Kelola Partner</h1>
            <p class="text-slate-500 mt-1">Daftar partner pendukung platform digital AmikomEventHub.</p>
        </div>
        
        <button onclick="document.getElementById('modal-tambah-partner').showModal()" class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl shadow-md hover:bg-indigo-700 transition font-bold flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Partner
        </button>
    </div>

    <div class="mb-6">
        <form action="{{ route('admin.partners.index') }}" method="GET" class="flex gap-3 max-w-md">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </span>
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama partner..." class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm">
            </div>
            <button type="submit" class="px-4 py-2.5 bg-slate-800 text-white text-sm font-bold rounded-xl hover:bg-slate-900 transition">
                Cari
            </button>
            @if($search)
                <a href="{{ route('admin.partners.index') }}" class="px-4 py-2.5 bg-slate-200 text-slate-700 text-sm font-bold rounded-xl hover:bg-slate-300 transition flex items-center">
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
                    <th class="px-8 py-4 font-bold w-32">Logo</th>
                    <th class="px-8 py-4 font-bold">Nama Partner</th>
                    <th class="px-8 py-4 font-bold">Alamat URL Logo</th>
                    <th class="px-8 py-4 font-bold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($partners as $index => $partner)
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-8 py-6 font-medium text-slate-600">{{ $index + 1 }}</td>
                    <td class="px-8 py-6">
                        <img src="{{ $partner->logo_url }}" alt="{{ $partner->name }}" class="w-16 h-16 object-contain rounded-lg border bg-slate-50 p-1">
                    </td>
                    <td class="px-8 py-6 font-bold text-slate-900">{{ $partner->name }}</td>
                    <td class="px-8 py-6 text-slate-500 font-mono text-xs max-w-xs truncate">
                        <a href="{{ $partner->logo_url }}" target="_blank" rel="noopener noreferrer" class="text-indigo-600 hover:text-indigo-800 hover:underline inline-flex items-center gap-1">
                            {{ $partner->logo_url }}
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                        </a>
                    </td>                    
                    <td class="px-8 py-6 flex justify-end gap-2">
                        <button onclick="document.getElementById('modal-edit-partner-{{ $partner->id }}').showModal()" class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl hover:bg-indigo-600 hover:text-white transition text-sm font-bold">
                            Edit
                        </button>
                        
                        <form action="{{ route('admin.partners.destroy', $partner->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus partner ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2.5 bg-rose-50 text-rose-600 rounded-xl hover:bg-rose-600 hover:text-white transition text-sm font-bold">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>

                <dialog id="modal-edit-partner-{{ $partner->id }}" class="backdrop:bg-slate-900/50 p-6 rounded-2xl border shadow-xl max-w-md w-full bg-white">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-slate-900">Ubah Data Partner</h3>
                        <button onclick="document.getElementById('modal-edit-partner-{{ $partner->id }}').close()" class="text-slate-400 hover:text-slate-600">
                            ✕
                        </button>
                    </div>
                    <form action="{{ route('admin.partners.update', $partner->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Nama Partner</label>
                                <input type="text" name="name" value="{{ $partner->name }}" required class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">URL Link Logo</label>
                                <input type="url" name="logo_url" value="{{ $partner->logo_url }}" required class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none text-sm font-mono text-slate-600">
                            </div>
                            <div class="flex justify-end gap-2 pt-2">
                                <button type="button" onclick="document.getElementById('modal-edit-partner-{{ $partner->id }}').close()" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl font-bold text-sm hover:bg-slate-200 transition">Batal</button>
                                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-xl font-bold text-sm hover:bg-indigo-700 transition">Simpan Perubahan</button>
                            </div>
                        </div>
                    </form>
                </dialog>
                @empty
                <tr>
                    <td colspan="5" class="px-8 py-12 text-center text-slate-400 font-medium">
                        Belum ada mitra/partner terdaftar atau tidak ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<dialog id="modal-tambah-partner" class="backdrop:bg-slate-900/50 p-6 rounded-2xl border shadow-xl max-w-md w-full bg-white">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-xl font-bold text-slate-900">Daftarkan Partner Baru</h3>
        <button onclick="document.getElementById('modal-tambah-partner').close()" class="text-slate-400 hover:text-slate-600">
            ✕
        </button>
    </div>
    <form action="{{ route('admin.partners.store') }}" method="POST">
        @csrf
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Nama Partner</label>
                <input type="text" name="name" placeholder="Contoh: Universitas Amikom Yogyakarta" required class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none text-sm">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">URL Link Logo</label>
                <input type="url" name="logo_url" value="https://placehold.co/200x200" placeholder="Contoh: https://linklogo.com/logo.png" required class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none text-sm font-mono text-slate-600">
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="document.getElementById('modal-tambah-partner').close()" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl font-bold text-sm hover:bg-slate-200 transition">Batal</button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-xl font-bold text-sm hover:bg-indigo-700 transition">Simpan Mitra</button>
            </div>
        </div>
    </form>
</dialog>
@endsection