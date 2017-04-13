<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(DistrictTableSeeder::class);
        $this->call(FuelPriceTableSeeder::class);
        $this->call(LocalizationTableSeeder::class);
        $this->call(ScheduleTableSeeder::class);
        $this->call(ServicesTableSeeder::class);
        $this->call(StationTableSeeder::class);
    }
}
