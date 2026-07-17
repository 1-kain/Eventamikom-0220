<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    public function create(Event $event)
    {
        $categories = \App\Models\Category::all(); 
        return view('checkout.create', compact('event', 'categories'));
    }

    public function store(Request $request, Event $event)
    {
        $request->validate([
            'customer_name'  => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
        ]);

        if ($event->stock <= 0) {
            return back()->with('error', 'Mohon maaf, tiket untuk acara ini sudah habis.');
        }

        $orderId = 'TRX-' . time() . '-' . Str::random(5);
        $totalPrice = $event->price + 5000; 

        DB::transaction(function () use ($event, $orderId, $request, $totalPrice, &$transaction) {
            
            // 🌟 LANGKAH BARU: Generate Kode Tiket Unik (Contoh: TKT-A89X7Z)
            $ticketCode = 'TKT-' . strtoupper(Str::random(6));
            
            // Proteksi berlapis: Pastikan kode tidak kembar di database
            while (Transaction::where('ticket_code', $ticketCode)->exists()) {
                $ticketCode = 'TKT-' . strtoupper(Str::random(6));
            }

            $transaction = Transaction::create([
                'event_id'       => $event->id,
                'order_id'       => $orderId,
                'ticket_code'    => $ticketCode, // 🌟 Masuk ke kolom baru
                'customer_name'  => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'total_price'    => $totalPrice,
                'status'         => 'Pending', 
            ]);

            $event->decrement('stock');
        });

        // Konfigurasi Midtrans
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = false; // Mode Sandbox!
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        // Susun Paket Array Data Transaksi
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $totalPrice,
            ],
            'customer_details' => [
                'first_name' => $request->customer_name,
                'email' => $request->customer_email,
                'phone' => $request->customer_phone,
            ],
            'notification_url' => rtrim(env('APP_URL'), '/') . '/midtrans/callback',
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            $transaction->update(['snap_token' => $snapToken]);

            return redirect()->route('checkout.payment', $transaction->order_id);

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses pembayaran jaringan: ' . $e->getMessage());
        }
    }

    public function payment($order_id)
    {
        // Mengambil daftar kategori untuk keperluan menu footer
        $categories = \App\Models\Category::all();

        $transaction = \App\Models\Transaction::with('event')->where('order_id', $order_id)->firstOrFail();
        return view('checkout.payment', compact('transaction','categories'));
    }

    public function cancel($order_id)
    {
        $transaction = \App\Models\Transaction::with('event')->where('order_id', $order_id)->where('status', 'pending')->first();

        if (! $transaction) {
            return response()->json(['message' => 'Transaksi tidak ditemukan atau tidak bisa dibatalkan.'], 404);
        }

        DB::transaction(function () use ($transaction) {
            $transaction->update(['status' => 'cancelled']);

            if ($transaction->event) {
                $transaction->event->increment('stock');
            }
        });

        return response()->json(['message' => 'Transaksi dibatalkan dan stok tiket dikembalikan.']);
    }

    
    public function success($order_id)
{
    // Mengambil daftar kategori untuk keperluan menu footer
    $categories = \App\Models\Category::all();

    $transaction = \App\Models\Transaction::where('order_id', $order_id)->firstOrFail();

    // Validasi status pembayaran asli dari Midtrans (Mencegah manipulasi URL)
    \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
    \Midtrans\Config::$isProduction = false;

    try {
        $midtransStatus = \Midtrans\Transaction::status($order_id);
        
        // Ambil status dengan mengecek apakah dia array atau object
        $trx_status = is_array($midtransStatus) ? ($midtransStatus['transaction_status'] ?? '') : ($midtransStatus->transaction_status ?? '');

        // Jika Midtrans mengonfirmasi pembayaran lunas
        if (in_array($trx_status, ['capture', 'settlement'])) {
            $transaction->update(['status' => 'success']);
            
            // Tampilkan halaman sukses HANYA jika benar-benar lunas
            return view('checkout.success', compact('transaction','categories'));
        } else {
            // JIKA STATUSNYA MASIH PENDING / BELUM LUNAS:
            // Kembalikan user ke halaman transaksi/pembayaran semula dengan pesan peringatan
            return redirect()->route('checkout.payment', $order_id)->with('error', 'Pembayaran Anda belum selesai atau masih ditangguhkan.');
        }

    } catch (\Exception $e) {
        // Jika error (transaksi tidak ada di Midtrans, koneksi terputus), kembalikan ke beranda
        return redirect()->route('home')->with('error', 'Transaksi tidak ditemukan atau gagal diproses oleh sistem pembayaran.');
    }
}

public function callback(Request $request)
{
    // Perekam Log untuk pembuktian
    \Illuminate\Support\Facades\Log::info('Simulasi Webhook Masuk!', $request->all());

    $order_id = $request->order_id;
    $status_code = $request->status_code;
    $gross_amount = $request->gross_amount;
    $transaction_status = $request->transaction_status;
    
    // KOMENTARI SEPANJANG BARIS VALIDASI INI UNTUK SIMULASI LOKAL
    // $serverKey = env('MIDTRANS_SERVER_KEY');
    // $hashed = hash("sha512", $order_id . $status_code . $gross_amount . $serverKey);
    // if ($hashed == $request->signature_key) {
        
        $transaction = \App\Models\Transaction::where('order_id', $order_id)->first();
        
        if ($transaction) {
            // Jika statusnya settlement atau capture, ubah ke success
            if ($transaction_status == 'capture' || $transaction_status == 'settlement') {
                
                // Sesuaikan kata 'success' di bawah ini dengan enum/string di databasemu (misal: 'Berhasil' atau 'success')
                $transaction->update(['status' => 'success']); 
                
                return response()->json(['message' => 'Status berhasil diperbarui secara lokal!']);
            }
        }
    // } // JANGAN LUPA KOMENTARI TUTUP KURUNG NYA JUGA

    return response()->json(['message' => 'Data diterima tapi tidak diproses'], 400);
}
}