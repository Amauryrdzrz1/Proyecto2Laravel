<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(comentariosSeeder::class);
        $this->call(productosSeeder::class);
        $this->call(usersSeeder::class);
    }
}
