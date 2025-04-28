<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user')->insert([
            'id' => Str::uuid(),
            'username' => 'Abdul Jawad Azizi',
            'email' => 'jawadazizi052@gmail.com',
            'password' => Hash::make('devGanteng'),
            'updated_security' => date('Y-m-d H:i:s'),
        ]);
    }
}
