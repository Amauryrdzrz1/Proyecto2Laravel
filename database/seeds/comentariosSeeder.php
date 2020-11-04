<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class comentariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Modelos\Comentario::class, 100)->create();
    }
}
