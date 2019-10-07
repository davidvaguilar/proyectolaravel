<?php

use Illuminate\Database\Seeder;
use App\Producto;

class ProductosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Producto::create([
            'idcategoria' => 1,
            'codigo' => '8410100025155',
            'nombre' => 'Cacao Nesquik 400 GRS',
            'precio_venta' => 10000,
            'stock' => 3,
            'imagen' => '8410100025155.jpg'
        ]);

        /*Producto::create([
            'idcategoria' => 2,
            'codigo' => '4567',
            'nombre' => 'ace',
            'precio_venta' => 2000,
            'stock' => 16
        ]);
        
        Producto::create([
            'idcategoria' => 3,
            'codigo' => '56789',
            'nombre' => 'cristal',
            'precio_venta' => 5000,
            'stock' => 30
        ]);*/
    }
}
