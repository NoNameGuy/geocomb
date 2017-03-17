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
            'name' => 'Leiria',
            'station' => 1
        ],[
            'name' => 'Coimbra',
            'station' => 2
        ],[
            'name' => 'Lisboa',
            'station' => 3
        ],[
            'name' => 'Porto',
            'station' => 4
        ]
        );
    }
}
