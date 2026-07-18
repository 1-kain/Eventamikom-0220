<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 🌟 TUKANG BANGUNAN PINTAR: Cek dulu sebelum mengeksekusi
        
        // 1. Cek apakah kolom user_id sudah ada atau belum
        if (!Schema::hasColumn('transactions', 'user_id')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained()->nullOnDelete();
            });
        }

        // 2. Cek apakah kolom ticket_code sudah ada atau belum
        if (!Schema::hasColumn('transactions', 'ticket_code')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->string('ticket_code')->nullable()->after('order_id')->unique();
            });
        }
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (Schema::hasColumn('transactions', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
            
            if (Schema::hasColumn('transactions', 'ticket_code')) {
                $table->dropColumn('ticket_code');
            }
        });
    }
};