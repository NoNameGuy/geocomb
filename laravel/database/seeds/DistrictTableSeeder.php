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
        $data = array(['name' => 'Aveiro'],['name' => 'Beja'],['name' => 'Braga'],['name' => 'Bragança'],['name' => 'Castelo Branco'],['name' => 'Coimbra'],['name' => 'Évora'],['name' => 'Faro'],['name' => 'Guarda'],['name' => 'Leiria'],['name' => 'Lisboa'],['name' => 'Portalegre'],['name' => 'Porto'],['name' => 'Santarém'],['name' => 'Setúbal'],['name' => 'Viana do Castelo' ],['name' => 'Vila Real'],['name' => 'Viseu']);
          
        DB::table('District')->insert($data);
        /*
        $data = array([
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
        ]);

        DB::table('District')->insert($data);*/
    }
}
