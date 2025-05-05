<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BlogTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetching blog post UUIDs
        $blogPostIds = DB::table('blog_posts')->pluck('id')->toArray();

        // Fetching tag UUIDs
        $tagIds = DB::table('tags')->pluck('id')->toArray();

        // Creating the association between blog post and tag
        foreach ($blogPostIds as $blogPostId) {
            foreach (array_slice($tagIds, 0, rand(1, count($tagIds))) as $tagId) {
                DB::table('blog_tags')->insert([
                    'blog_post_id' => $blogPostId,
                    'tag_id' => $tagId,
                ]);
            }
        }
    }
}
