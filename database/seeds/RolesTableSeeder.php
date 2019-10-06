<?php

use Illuminate\Database\Seeder;
use App\Rol;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Rol::create([
            'nombre' => 'Administrador',
            'descripcion' => 'Administrador'
        ]);

        Rol::create([
            'nombre' => 'Vendedor',
            'descripcion' => 'Vendedor'
        ]);

        Rol::create([
            'nombre' => 'Almacenero',
            'descripcion' => 'Comprador'
        ]);
    }
}
