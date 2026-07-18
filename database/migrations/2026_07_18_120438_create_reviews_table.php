<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Siapa pembelinya
            $table->foreignId('organizer_id')->constrained('users')->cascadeOnDelete(); // Siapa EO-nya
            $table->foreignId('event_id')->constrained()->cascadeOnDelete(); // Dari event mana
            $table->integer('rating'); // Bintang 1-5
            $table->text('comment'); // Isi ulasan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};