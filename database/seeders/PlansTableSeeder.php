<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlansTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('plans')->insert([
            [
                'name' => 'Basic',
                'price' => 30000,
                'disk_size' => 5,
                'description' => '5GB 제공 / 월 30,000원'
            ],
            [
                'name' => 'Pro',
                'price' => 50000,
                'disk_size' => 10,
                'description' => '10GB 제공 / 월 50,000원'
            ]
        ]);
    }
}
