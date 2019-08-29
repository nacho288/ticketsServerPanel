<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ProductosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('App/Producto');

/*         $table->string('nombre');
        $table->string('codigo')->unique();
        $table->bigInteger('minimo')->nullable();;
        $table->bigInteger('maximo')->nullable();; */

        for ($i=0; $i <  51; $i++) {
            $minimo = ($faker->numberBetween(1, 10) ) * 10;

            DB::table('productos')->insert([
                'nombre' => $faker->name,
                'codigo' => $faker->unique()->numberBetween(1, 100),
                'minimo' => $minimo,
                'maximo' => $minimo * 2
            ]);
        }

    }
}
