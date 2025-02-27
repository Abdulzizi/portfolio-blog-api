<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('posts')->insert([
            [
                'id' => Str::uuid(),
                'user_id' => '9ad1d6ab-e234-433c-871b-73a8b7ff3a61',
                'title' => 'Getting Started with Laravel',
                'content' => 'Laravel is a powerful MVC framework for PHP...',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'user_id' => '375dfe0f-6ccf-4b78-b38f-ed17eb50d0c3',
                'title' => 'Vue.js for Beginners',
                'content' => 'Vue.js is a progressive JavaScript framework...',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
