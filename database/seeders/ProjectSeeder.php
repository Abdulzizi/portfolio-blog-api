<?php

namespace Database\Seeders;

use App\Models\ProjectModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Str;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Creating 5 dummy projects
        ProjectModel::create([
            'title' => 'Project One',
            'description' => 'This is a detailed description of Project One.',
            'link' => 'https://github.com/project-one',
            'tech_stack' => json_encode(['Laravel', 'Vue.js', 'TailwindCSS']),
            'images' => null, // No image for now
            'thumbnail' => null, // No thumbnail for now
            'slug' => Str::slug('Project One'),
            'is_published' => true,
        ]);

        ProjectModel::create([
            'title' => 'Project Two',
            'description' => 'Description for Project Two.',
            'link' => 'https://github.com/project-two',
            'tech_stack' => json_encode(['React', 'Node.js', 'MongoDB']),
            'images' => null,
            'thumbnail' => null,
            'slug' => Str::slug('Project Two'),
            'is_published' => false,
        ]);

        ProjectModel::create([
            'title' => 'Project Three',
            'description' => 'Description for Project Three.',
            'link' => 'https://github.com/project-three',
            'tech_stack' => json_encode(['Python', 'Django', 'PostgreSQL']),
            'images' => null,
            'thumbnail' => null,
            'slug' => Str::slug('Project Three'),
            'is_published' => true,
        ]);

        ProjectModel::create([
            'title' => 'Project Four',
            'description' => 'This is a detailed description of Project Four.',
            'link' => 'https://github.com/project-four',
            'tech_stack' => json_encode(['Ruby on Rails', 'MySQL']),
            'images' => null,
            'thumbnail' => null,
            'slug' => Str::slug('Project Four'),
            'is_published' => true,
        ]);

        ProjectModel::create([
            'title' => 'Project Five',
            'description' => 'This is the description for Project Five.',
            'link' => 'https://github.com/project-five',
            'tech_stack' => json_encode(['PHP', 'Laravel', 'MySQL']),
            'images' => null,
            'thumbnail' => null,
            'slug' => Str::slug('Project Five'),
            'is_published' => false,
        ]);
    }
}
