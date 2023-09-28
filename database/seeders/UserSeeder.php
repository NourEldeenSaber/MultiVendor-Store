<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Ayman Mamdouh',
            'email'=> 'ayman12@sys.com',
            'password'=>Hash::make('password'),
            'phone_number'=>'01007812642'
        ]);

        DB::table('users')->insert([
            'name' => 'Ayman Mamdouh_2',
            'email'=> 'ayman123@sys.com',
            'password'=>Hash::make('password'),
            'phone_number'=>'0507479756'
        ]);
    }
}
