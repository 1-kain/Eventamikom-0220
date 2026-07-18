@extends('layouts.admin', ['title' => 'Kelola Vendor'])

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Kelola Vendor (Organizer)</h1>
        <p class="text-slate-500 mt-1 font-medium">Manajemen akun partner dan penyelenggara event di platform.</p>
    </div>
    <a href="{{ route('admin.organizers.create') }}" class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl text-xs font-bold hover:bg-indigo-700 transition shadow-sm flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Tambah Vendor Baru
    </a>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50 text-slate-500 uppercase text-xs font-bold tracking-wider border-b border-slate-200">
                <tr>
                    <th class="px-8 py-4">Nama Perusahaan / Vendor</th>
                    <th class="px-8 py-4">Email Kontak</th>
                    <th class="px-8 py-4">Tanggal Bergabung</th>
                    <th class="px-8 py-4 text-center">Aksi Hak Akses</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($organizers as $vendor)
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-8 py-6 font-bold text-slate-800 uppercase text-sm">
                        {{ $vendor->name }}
                    </td>
                    <td class="px-8 py-6 text-sm text-slate-600">
                        {{ $vendor->email }}
                    </td>
                    <td class="px-8 py-6 text-sm text-slate-400 font-medium">
                        {{ $vendor->created_at->format('d M Y') }}
                    </td>
                    <td class="px-8 py-6 text-center space-x-2 whitespace-nowrap">
                        <a href="{{ route('admin.organizers.edit', $vendor->id) }}" class="px-3 py-1.5 bg-amber-50 text-amber-600 rounded-lg text-xs font-bold hover:bg-amber-100 transition inline-block">
                            Edit
                        </a>
                        <form action="{{ route('admin.organizers.destroy', $vendor->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus vendor ini beserta seluruh datanya?')">
                            @csrf 
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1.5 bg-rose-50 text-rose-600 rounded-lg text-xs font-bold hover:bg-rose-100 transition">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-8 py-10 text-center text-slate-400 font-medium">Belum ada vendor yang terdaftar di sistem.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection