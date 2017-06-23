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
        $this->call(AllTableSeeder::class);
        /*$this->call(DistrictTableSeeder::class);
        $this->call(FuelPriceTableSeeder::class);
        $this->call(LocationTableSeeder::class);
        $this->call(ScheduleTableSeeder::class);
        $this->call(ServicesTableSeeder::class);
        $this->call(StationTableSeeder::class);
        $this->call(UserTableSeeder::class);*/
    }
}
