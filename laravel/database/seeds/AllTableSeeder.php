<?php

use Illuminate\Database\Seeder;

class AllTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $content = file_get_contents('public/files/Postos-Alves-Bandeira.geojson');
      $content .= file_get_contents('public/files/Postos-BP.geojson');
      $content .= file_get_contents('public/files/Postos-Cepsa.geojson');
      $content .= file_get_contents('public/files/Postos-E.Leclerc.geojson');
      $content .= file_get_contents('public/files/Postos-Ecobrent.geojson');
      $content .= file_get_contents('public/files/Postos-Galp.geojson');
      $content .= file_get_contents('public/files/Postos-Intermarche.geojson');
      $content .= file_get_contents('public/files/Postos-Jumbo.geojson');
      $content .= file_get_contents('public/files/Postos-Outras-Marcas.geojson');
      $content .= file_get_contents('public/files/Postos-OZ-Energia.geojson');
      $content .= file_get_contents('public/files/Postos-Pingo-Doce.geojson');
      $content .= file_get_contents('public/files/Postos-Prio.geojson');
      $content .= file_get_contents('public/files/Postos-Rede-Energia.geojson');
      $content .= file_get_contents('public/files/Postos-Repsol.geojson');
      $content .= file_get_contents('public/files/Postos-Total.geojson');
      $array = json_decode($content, true);
      for($i = 0; $i < 15; $i++){
        echo $array;
      }
      //for ($i = 0; $i < DB::table('location')->count(); $i++) {


       /*$brand = $this->randomBrand();
       $location = $i+1;//$this->generateRandomNumber(1,10);
       $district = $this->associateDistrict($i+1);
       $fuel_price = $this->generateRandomNumber(1,10);
       $services = $this->generateRandomNumber(1,10);
       $schedule = $this->generateRandomNumber(1,10);


         DB::table('station')->insert([
           'name' => "a$i",
           'brand' => $brand,
           'location' => $location,
           'district' => $district,
           'fuel_price' => $fuel_price,
           'services' => $services,
           'last_update' => date("Y-m-d H:i:s", strtotime("12/04/2017 19:00")),
           'schedule' => $schedule
         ]);*/

      //}
    }
}
