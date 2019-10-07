<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'nombre' => 'David Villegas Aguilar',
            'tipo_documento' => 'CEDULA',
            'num_documento' => '15979446',
            'direccion' => 'Heroes',
            'telefono' => '976400180',
            'email' => 'david.villegas.aguilar@gmail.com',
            'usuario' => 'david',
            'password' => bcrypt('123'),
            'idrol' => '1',
            'imagen' => 'avatar1.jpg'
        ]);

        User::create([
            'nombre' => 'Gabriel',
            'tipo_documento' => 'CEDULA',
            'num_documento' => '22222222',
            'direccion' => 'Iquique',
            'telefono' => '955555555',
            'email' => 'gabriel@correo.cl',
            'usuario' => 'gabriel',
            'password' => bcrypt('123'),
            'idrol' => '1',
            'imagen' => 'avatar2.jpg'
        ]);
    }
}
