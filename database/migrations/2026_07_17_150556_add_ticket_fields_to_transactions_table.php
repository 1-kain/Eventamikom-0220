<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Kita tambahkan kode tiket unik dan status scan di sini
            $table->string('ticket_code')->nullable()->unique()->after('order_id');
            $table->enum('is_scanned', ['No', 'YES'])->default('No')->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['ticket_code', 'is_scanned']);
        });
    }
};