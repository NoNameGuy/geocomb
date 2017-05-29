<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$data = array(["id" => 1, "name" => "Gestor", "email" => "gestor@gmail.com", "password" => Hash::make(123123123), "is_activated" => 1]);
        DB::table('users')->insert($data);
    }
}
