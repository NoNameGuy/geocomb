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
    	$data = array(["id" => 1, "name" => "Goncalo", "email" => "goncalo@gmail.com", "password" => Hash::make(123123123)],
    		["id" => 2, "name" => "Paulo", "email" => "paulo@gmail.com", "password" => Hash::make(123123123)]);
        DB::table('users')->insert($data);
    }
}
