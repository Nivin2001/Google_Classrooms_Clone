<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Classroom;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
      //  \App\Models\User::factory(10)->create();
        // \App\Models\Topic::factory(4)->create([
        //     'classroom_id' => 1,
        // ]);

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        // $this->call([
        // //     UserSeeder::class
        //      ClassroomSeeder::class
        // ]);
        \App\Models\Admin::factory(4)->create();
        
    }
}
