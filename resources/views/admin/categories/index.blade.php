@extends('layouts.admin')

@section('content')
<div class="p-8 w-full">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Kelola Kategori</h1>
            <p class="text-slate-500 mt-1">Daftar kategori event yang tersedia di platform.</p>
        </div>
        <button class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl shadow-md hover:bg-indigo-700 transition font-bold flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Kategori
        </button>
    </div>

    <div class="bg-white rounded-2xl border shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b text-sm text-slate-500 uppercase tracking-wider">
                    <th class="px-8 py-4 font-bold">No</th>
                    <th class="px-8 py-4 font-bold">Nama Kategori</th>
                    <th class="px-8 py-4 font-bold">Status</th>
                    <th class="px-8 py-4 font-bold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-8 py-6 font-medium text-slate-600">1</td>
                    <td class="px-8 py-6 font-bold text-slate-900">Seminar</td>
                    <td class="px-8 py-6">
                        <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-lg text-xs font-bold uppercase">Aktif</span>
                    </td>
                    <td class="px-8 py-6 flex justify-end gap-2">
                        <button class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl hover:bg-indigo-600 hover:text-white transition">
                            Edit
                        </button>
                        <button class="p-2.5 bg-rose-50 text-rose-600 rounded-xl hover:bg-rose-600 hover:text-white transition">
                            Hapus
                        </button>
                    </td>
                </tr>
                
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-8 py-6 font-medium text-slate-600">2</td>
                    <td class="px-8 py-6 font-bold text-slate-900">Konser</td>
                    <td class="px-8 py-6">
                        <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-lg text-xs font-bold uppercase">Aktif</span>
                    </td>
                    <td class="px-8 py-6 flex justify-end gap-2">
                        <button class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl hover:bg-indigo-600 hover:text-white transition">Edit</button>
                        <button class="p-2.5 bg-rose-50 text-rose-600 rounded-xl hover:bg-rose-600 hover:text-white transition">Hapus</button>
                    </td>
                </tr>

                <tr class="hover:bg-slate-50 transition">
                    <td class="px-8 py-6 font-medium text-slate-600">3</td>
                    <td class="px-8 py-6 font-bold text-slate-900">Workshop</td>
                    <td class="px-8 py-6">
                        <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-lg text-xs font-bold uppercase">Nonaktif</span>
                    </td>
                    <td class="px-8 py-6 flex justify-end gap-2">
                        <button class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl hover:bg-indigo-600 hover:text-white transition">Edit</button>
                        <button class="p-2.5 bg-rose-50 text-rose-600 rounded-xl hover:bg-rose-600 hover:text-white transition">Hapus</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection