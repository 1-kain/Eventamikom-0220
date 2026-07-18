<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // 1. Tambah kolom untuk menyimpan ID unik dari Google
            $table->string('google_id')->nullable()->after('id');
            
            // 2. Ubah password jadi nullable karena login via Google gak butuh isi password di DB kita
            $table->string('password')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('google_id');
            $table->string('password')->nullable(false)->change();
        });
    }
};