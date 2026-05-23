<?php

namespace Database\Seeders;

use App\Models\Partner;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class PartnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        for ($i = 0; $i < 5; $i++) {
            Partner::create([
                'name' => $faker->company, 
                'logo_url' => 'https://placehold.co/200x200', 
            ]);
        }
    }
}