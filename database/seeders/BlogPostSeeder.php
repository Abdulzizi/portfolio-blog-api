<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Str;

class BlogPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $posts = [
            ['title' => 'The Future of Tech', 'content' => 'Content about tech...', 'slug' => 'the-future-of-tech'],
            ['title' => 'How to Stay Healthy', 'content' => 'Content about health...', 'slug' => 'how-to-stay-healthy'],
            ['title' => 'Work-Life Balance Tips', 'content' => 'Content about lifestyle...', 'slug' => 'work-life-balance'],
        ];

        foreach ($posts as $post) {
            DB::table('blog_posts')->insert([
                'id' => Str::uuid(),
                'title' => $post['title'],
                'content' => $post['content'],
                'slug' => $post['slug'],
                'is_published' => rand(0, 1) === 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
