<?php

namespace Database\Seeders;

use App\Models\plan;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $plans = [
            ['name' => 'free',
            'price'=>'0',
            'percentage1'=>'0'
        ],

            // Add more plan data as needed
        ];
        plan::insert($plans);

    }
}
