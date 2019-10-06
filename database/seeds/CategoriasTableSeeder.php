<?php

use Illuminate\Database\Seeder;
use App\Categoria;

class CategoriasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Categoria::create([
            'nombre' => 'harina',
            'descripcion' => 'variedad de harina',
        ]);

        Categoria::create([
            'nombre' => 'detergente',
            'descripcion' => 'variedad de detergente',
        ]);
        
        Categoria::create([
            'nombre' => 'cerveza',
            'descripcion' => 'variedad de cerveza',
        ]);

    }
}
