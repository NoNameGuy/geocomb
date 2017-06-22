<?php

use Illuminate\Database\Seeder;

class FuelTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('fuel')->insert([ //,
          'Gasoleo',
          'Gasoleo Simples',
          'Gasoleo Especial',
          'Gasoleo Colorido',
          'Gasolina 95',
          'Gasolina Simples 95',
          'Gasolina Especial 95',
          'Gasolina 98',
          'Gasolina Simples 98',
          'Gasolina Especial 98',
          'Gas Natural Comprimido Kg',
          'Gas Natural Comprimido m3',
          'Gas Natural Liquido',
          'GPL',
        ]);
    }
}
