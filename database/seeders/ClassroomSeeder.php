<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassroomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('classrooms')
        ->insert([
            'id'=>'1',
            'name' => 'Laravel Training',
            'code' => 'NDKANJM52#',
            'section' => 'seeders',
            'subject' => 'initializing data',
            
        ]);
    }
}
