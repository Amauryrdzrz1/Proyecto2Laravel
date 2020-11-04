<?php

use Illuminate\Database\Seeder;
use App\Modelos\Producto;

class productosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Modelos\Producto::class, 50)->create();
    }
}
