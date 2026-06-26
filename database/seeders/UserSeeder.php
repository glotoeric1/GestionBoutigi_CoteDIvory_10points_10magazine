<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => "Eric K GLOTO",
            'email' => "skillcodiing@gmail.net",
            'password' => Hash::make('2211a'),
            'contact' => '73231645',
            'statut' => '1',
            'roles' => "Super Admin",
            'secret' => "2211a"
        ]);
    }
}