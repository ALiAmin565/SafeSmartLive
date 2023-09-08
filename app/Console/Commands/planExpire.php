<?php

namespace App\Console\Commands;

use App\Models\video;
use Illuminate\Console\Command;
use App\Models\User;
use Carbon\Carbon;

class planExpire extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'planExpire every all mounth';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
         $date=Carbon::now()->format('Y-m-d');
         
         
         
         $users=User::where('end_plan','<=',$date)->get();
         
         
          

foreach ($users as $user) {
    $user->update([
        'start_plan'=>null,
        'end_plan' => null,
        'plan_id'=>1,
        'Status_Plan'=>null,
    ]);
}
    }
}
