<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon; // 🌟 Wajib import Carbon untuk validasi waktu 2026 secara real-time

class CheckoutController extends Controller
{
    public function create(Event $event)
    {
        // 1. SENSOR OTOMATIS: Jika belum login, catat URL checkout ini dan lempar ke Google
        if (!auth()->check()) {
            session(['url.intended' => url()->current()]); 
            return redirect()->route('auth.google');
        }

        // 🌟 GERBANG DEPAN: Tolak akses jika event sudah selesai atau stok ludes
        if ($event->stock <= 0 || Carbon::parse($event->date)->isPast()) {
            return redirect()->route('event.show', $event->id)
                ->with('error', 'Mohon maaf, penjualan tiket untuk acara ini sudah ditutup.');
        }

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

        // 🌟 GERBANG BELAKANG: Proteksi mutlak dari serangan injeksi URL/Postman
        if ($event->stock <= 0 || Carbon::parse($event->date)->isPast()) {
            return redirect()->route('event.show', $event->id)
                ->with('error', 'Mohon maaf, transaksi tidak dapat diproses karena penjualan tiket sudah ditutup.');
        }

        $orderId = 'TRX-' . time() . '-' . Str::random(5);
        $totalPrice = $event->price + 5000; 

        DB::transaction(function () use ($event, $orderId, $request, $totalPrice, &$transaction) {
            
            // Generate Kode Tiket Unik
            $ticketCode = 'TKT-' . strtoupper(Str::random(6));
            while (Transaction::where('ticket_code', $ticketCode)->exists()) {
                $ticketCode = 'TKT-' . strtoupper(Str::random(6));
            }

            $transaction = Transaction::create([
                'user_id'        => auth()->id(), 
                'event_id'       => $event->id,
                'order_id'       => $orderId,
                'ticket_code'    => $ticketCode, 
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
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

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
        $categories = \App\Models\Category::all();
        $transaction = \App\Models\Transaction::with('event')->where('order_id', $order_id)->firstOrFail();
        return view('checkout.payment', compact('transaction', 'categories'));
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
        $categories = \App\Models\Category::all();
        $transaction = \App\Models\Transaction::where('order_id', $order_id)->firstOrFail();

        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = false;

        try {
            $midtransStatus = \Midtrans\Transaction::status($order_id);
            $trx_status = is_array($midtransStatus) ? ($midtransStatus['transaction_status'] ?? '') : ($midtransStatus->transaction_status ?? '');

            if (in_array($trx_status, ['capture', 'settlement'])) {
                $transaction->update(['status' => 'success']);
                return view('checkout.success', compact('transaction', 'categories'));
            } else {
                return redirect()->route('checkout.payment', $order_id)->with('error', 'Pembayaran Anda belum selesai atau masih ditangguhkan.');
            }
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', 'Transaksi tidak ditemukan atau gagal diproses oleh sistem pembayaran.');
        }
    }

    public function callback(Request $request)
    {
        \Illuminate\Support\Facades\Log::info('Simulasi Webhook Masuk!', $request->all());

        $order_id = $request->order_id;
        $transaction_status = $request->transaction_status;
        
        $transaction = \App\Models\Transaction::where('order_id', $order_id)->first();
        
        if ($transaction) {
            if ($transaction_status == 'capture' || $transaction_status == 'settlement') {
                $transaction->update(['status' => 'success']); 
                return response()->json(['message' => 'Status berhasil diperbarui secara lokal!']);
            }
        }

        return response()->json(['message' => 'Data diterima tapi tidak diproses'], 400);
    }
}