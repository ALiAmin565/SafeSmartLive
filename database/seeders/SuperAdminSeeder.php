<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = [
            [
            'name' => 'SuperAdmin',
            'email'=>'yahya@upvela.com',
            'password'=>Hash::make('yahya16598'),
            'verified'=>1,
            'phone'=>'01147963593',
            'state'=>'super_admin',
            'affiliate_code'=>'MEOGKH1N',
            'affiliate_link'=>'https://upvela.page.link/5oDiynTYcHAPAHQE7',
            'created_at'=>'2023-06-21 15:53:53'


        ],

            // Add more plan data as needed
        ];
        User::insert($user);

    }
}
