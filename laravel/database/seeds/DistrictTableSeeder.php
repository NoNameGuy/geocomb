<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DistrictTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
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
        ];
        
        DB::table('District')->insert($data);
    }
}
