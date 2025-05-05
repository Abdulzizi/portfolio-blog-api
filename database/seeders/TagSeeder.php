<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB as FacadesDB;
use Str;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            ['name' => 'Technology', 'slug' => 'technology', 'description' => 'All about technology'],
            ['name' => 'Health', 'slug' => 'health', 'description' => 'Tips for a healthy life'],
            ['name' => 'Lifestyle', 'slug' => 'lifestyle', 'description' => 'Lifestyle and daily living'],
            ['name' => 'Business', 'slug' => 'business', 'description' => 'Business-related content'],
        ];

        foreach ($tags as $tag) {
            FacadesDB::table('tags')->insert([
                'id' => Str::uuid(),
                'name' => $tag['name'],
                'slug' => $tag['slug'],
                'description' => $tag['description'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
