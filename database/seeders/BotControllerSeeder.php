<?php

namespace Database\Seeders;

use App\Models\BotStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BotControllerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       BotStatus::create([
            'is_active' => 0,
       ]);
    }
}
