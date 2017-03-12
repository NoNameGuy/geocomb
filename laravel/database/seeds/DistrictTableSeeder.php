<?php

use Illuminate\Database\Seeder;

class DistrictsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('District')->insert([
            'name' => str_random(10),
            'station' => 1
        ]);
    }
}
