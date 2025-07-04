<?php

namespace Database\Seeders;

use App\Models\ProjectModel;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Str;
use Faker\Factory as Faker;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $projects = [
            [
                'title'       => 'Personal Finance Manager',
                'description' => 'This is a detailed description of Personal Finance Manager.',
                'link'        => 'https://github.com/personal-finance-manager',
                'tech_stack'  => ['Laravel', 'Vue.js', 'TailwindCSS'],
                'is_published' => true,
            ],
            [
                'title'       => 'Task Manager',
                'description' => 'Description for Task Manager.',
                'link'        => 'https://github.com/task-manager',
                'tech_stack'  => ['React', 'Node.js', 'MongoDB'],
                'is_published' => false,
            ],
            [
                'title'       => 'Image Gallery',
                'description' => 'Description for Image Gallery.',
                'link'        => 'https://github.com/image-gallery',
                'tech_stack'  => ['Python', 'Django', 'PostgreSQL'],
                'is_published' => true,
            ],
            [
                'title'       => 'E-commerce Store',
                'description' => 'This is a detailed description of E-commerce Store.',
                'link'        => 'https://github.com/e-commerce-store',
                'tech_stack'  => ['Ruby on Rails', 'MySQL'],
                'is_published' => true,
            ],
            [
                'title'       => 'Weather API',
                'description' => 'This is the description for Weather API.',
                'link'        => 'https://github.com/weather-api',
                'tech_stack'  => ['PHP', 'Laravel', 'MySQL'],
                'is_published' => false,
            ],
        ];

        foreach ($projects as $data) {
            // Generate a realistic project window:
            //   start_date anytime within the past 2 years
            //   end_date 0-365 days after start (or null 20 % of the time to simulate “ongoing”)
            $start = Carbon::today()->subDays($faker->numberBetween(0, 730));
            $end   = $faker->boolean(20)
                ? null
                : (clone $start)->addDays($faker->numberBetween(0, 365));

            ProjectModel::create([
                'title'        => $data['title'],
                'description'  => $data['description'],
                'link'         => $data['link'],
                'tech_stack'   => json_encode($data['tech_stack']),
                'images'       => null,
                'thumbnail'    => null,
                'slug'         => Str::slug($data['title']),
                'is_published' => $data['is_published'],
                'start_date'   => $start->toDateString(),
                'end_date'     => $end?->toDateString(),
            ]);
        }
    }
}
