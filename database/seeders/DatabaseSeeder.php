<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Admin Utama
        \App\Models\User::create([
        'name' => 'Admin Amikom',
        'email' => 'admin@amikom.ac.id',
        'password' => bcrypt('password'),
        'role' => 'admin',
        ]);

        // 2. Insert Kategori Event
        $category = \App\Models\Category::create([
        'name' => 'Seminar IT',
        'slug' => 'seminar-it',
        ]);

        $category2 = \App\Models\Category::firstOrCreate([
        'name' => 'Entertaiment',
        'slug' => 'entertaiment',
        ]);

        //Tambahan 3 Category
        $category3 = \App\Models\Category::firstOrCreate([
        'name' => 'Workshop',
        'slug' => 'workshop',
        ]);

        $category4 = \App\Models\Category::create([
            'name' => 'Laravel Advanced Workshop', 
            'slug' => 'laravel-advanced-workshop',
        ]);
        $category5 = \App\Models\Category::create([
            'name' => 'E-Sport', 
            'slug' => 'e-sport',
        ]);


        // 3. Insert Sampel Events
        \App\Models\Event::create([
        'category_id' => $category2->id,
        'title' => 'Jazz Night 2025',
        'description' => 'Nikmati malam yang indah dengan alunan musik jazz

        yang merdu.',

        'date' => '2026-05-10 19:00:00',
        'location' => 'Amikom Baru',
        'price' => 50000,
        'stock' => 100,
        'poster_path' => 'posters/event-1.png',
        ]);

        \App\Models\Event::create([
        'category_id' => $category->id,
        'title' => 'Hackaton - Unleash Your Inner Developer',
        'description' => 'Ayo asah skill coding kamu dan ciptakan solusi

        inovatif untuk tantangan masa depan!',
        'date' => '2026-05-05 10:00:00',
        'location' => 'Inkubator Amikom',
        'price' => 50000,
        'stock' => 100,
        'poster_path' => 'posters/event-2.png',
        ]);

        \App\Models\Event::create([
        'category_id' => $category->id,
        'title' => 'AI & FUTURE TECH SUMMIT 2026',
        'description' => 'Jelajahi tren terkini dalam kecerdasan buatan dan

        teknologi masa depan bersama para ahli di bidangnya.',

        'date' => '2026-05-01 13:00:00',
        'location' => 'Cinema Unit 6',
        'price' => 50000,
        'stock' => 100,
        'poster_path' => 'posters/event-3.png',
        ]);

        //Tambahan 6 Event
        \App\Models\Event::create([
            'category_id' => $category2->id,
            'title' => 'Jazz Night 2025',
            'description' => 'Nikmati malam yang indah dengan alunan musik jazz yang merdu.',
            'date' => '2026-05-10 19:00:00',
            'location' => 'Amikom Baru',
            'price' => 50000,
            'stock' => 100,
            'poster_path' => 'posters/event-1.png',
        ]);

        \App\Models\Event::create([
            'category_id' => $category->id,
            'title' => 'Hackaton - Unleash Your Inner Developer',
            'description' => 'Ayo asah skill coding kamu dan ciptakan solusi inovatif untuk tantangan masa depan!',
            'date' => '2026-05-05 10:00:00',
            'location' => 'Inkubator Amikom',
            'price' => 50000,
            'stock' => 100,
            'poster_path' => 'posters/event-2.png',
        ]);

        \App\Models\Event::create([
            'category_id' => $category->id,
            'title' => 'AI & FUTURE TECH SUMMIT 2026',
            'description' => 'Jelajahi tren terkini dalam kecerdasan buatan dan teknologi masa depan bersama para ahli di bidangnya.',
            'date' => '2026-05-01 13:00:00',
            'location' => 'Cinema Unit 6',
            'price' => 50000,
            'stock' => 100,
            'poster_path' => 'posters/event-3.png',
        ]);

        \App\Models\Event::create([
            'category_id' => $category3->id,
            'title' => 'UI/UX Masterclass: Design Thinking',
            'description' => 'Pelajari metodologi design thinking untuk menciptakan produk digital yang user-centric.',
            'date' => '2026-06-15 09:00:00',
            'location' => 'Lab Komputer 4',
            'price' => 75000,
            'stock' => 40,
            'poster_path' => 'posters/event-4.png',
        ]);

        \App\Models\Event::create([
            'category_id' => $category5->id,
            'title' => 'E-Sport U-Champ 2026',
            'description' => 'Turnamen Mobile Legends antar mahasiswa Amikom. Tunjukkan kemampuan tim kamu!',
            'date' => '2026-07-20 10:00:00',
            'location' => 'Basement Gedung 4',
            'price' => 25000,
            'stock' => 200,
            'poster_path' => 'posters/event-5.png',
        ]);

        \App\Models\Event::create([
            'category_id' => $category4->id,
            'title' => 'Laravel Advanced Workshop',
            'description' => 'Mendalami fitur-fitur lanjutan Laravel untuk membangun aplikasi skala enterprise.',
            'date' => '2026-08-05 08:30:00',
            'location' => 'Ruang Seminar Unit 5',
            'price' => 100000,
            'stock' => 30,
            'poster_path' => 'posters/event-6.png',
        ]);
    }
}
