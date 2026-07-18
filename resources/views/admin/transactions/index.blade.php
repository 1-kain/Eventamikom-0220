@extends('layouts.admin', ['title' => 'Data Transaksi Global'])

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Data Transaksi Global</h1>
    <p class="text-slate-500 mt-1">Pantau seluruh arus masuk pesanan tiket pelanggan lintas vendor di sini.</p>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50 border-b border-slate-200 text-xs text-slate-500 uppercase tracking-wider font-bold">
                <tr>
                    <th class="px-8 py-4">Order ID & Waktu</th>
                    <th class="px-8 py-4">Kode Tiket</th>
                    <th class="px-8 py-4">Pembeli</th>
                    <th class="px-8 py-4">Event</th>
                    <th class="px-8 py-4">Vendor Penyelenggara</th> <!-- 🌟 KOLOM BARU UNTUK SUPERADMIN -->
                    <th class="px-8 py-4">Status</th>
                    <th class="px-8 py-4 text-right">Total Bayar</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($transactions as $trx)
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-8 py-6">
                        <p class="font-black text-slate-900">{{ $trx->order_id }}</p>
                        <p class="text-xs text-slate-500 font-medium">{{ $trx->created_at->format('d M Y, H:i') }}</p>
                    </td>
                    
                    <td class="px-8 py-6">
                        @if($trx->ticket_code)
                            <span class="px-2.5 py-1.5 bg-slate-100 text-slate-800 rounded-lg font-mono text-xs font-bold border border-slate-200 tracking-wider">
                                {{ $trx->ticket_code }}
                            </span>
                        @else
                            <span class="text-xs text-slate-400 italic">Tidak Ada Kode</span>
                        @endif
                    </td>

                    <td class="px-8 py-6">
                        <p class="font-bold text-slate-800">{{ $trx->customer_name }}</p>
                        <p class="text-xs text-slate-500">{{ $trx->customer_email }}</p>
                        <p class="text-[10px] text-slate-400 font-mono mt-1">{{ $trx->customer_phone }}</p>
                    </td>
                    
                    <td class="px-8 py-6">
                        <p class="font-bold text-indigo-600 truncate max-w-[180px]">{{ $trx->event->title ?? 'Event Telah Dihapus' }}</p>
                    </td>

                    <td class="px-8 py-6">
                        <!-- 🌟 MENAMPILKAN VENDOR YANG PUNYA EVENT -->
                        <span class="px-2.5 py-1 bg-slate-100 text-slate-700 border border-slate-200 text-xs font-bold rounded-md uppercase tracking-wide">
                            {{ $trx->event->user->name ?? 'Unknown Vendor' }}
                        </span>
                    </td>

                    <td class="px-8 py-6">
                        @if(strtolower($trx->status) == 'pending')
                            <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-lg text-xs font-bold uppercase ring-1 ring-amber-200">Pending</span>
                        @elseif(strtolower($trx->status) == 'success' || strtolower($trx->status) == 'settlement')
                            <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-lg text-xs font-bold uppercase ring-1 ring-emerald-200">Berhasil</span>
                        @else
                            <span class="px-3 py-1 bg-rose-100 text-rose-700 rounded-lg text-xs font-bold uppercase ring-1 ring-rose-200">{{ $trx->status }}</span>
                        @endif
                    </td>
                    
                    <td class="px-8 py-6 text-right font-black text-slate-900">
                        Rp {{ number_format($trx->total_price, 0, ',', '.') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <!-- 🌟 UBAH COLSPAN MENJADI 7 KARENA ADA KOLOM VENDOR -->
                    <td colspan="7" class="px-8 py-10 text-center text-slate-500 font-medium">Belum ada transaksi tiket global yang masuk ke sistem.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- 🌟 PENYEMPURNAAN STRUKTUR PAGING -->
    <div class="px-8 py-5 bg-slate-50 border-t border-slate-200 flex justify-between items-center">
        <div class="w-full">
            {{ $transactions->links() }}
        </div>
    </div>
</div>
@endsection