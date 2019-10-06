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
            'nombre' => 'david',
            'tipo_documento' => 'CEDULA',
            'num_documento' => '12345',
            'direccion' => 'av carlota',
            'telefono' => '1234556',
            'email' => 'david.villegas.aguilar@gmail.com',
            'usuario' => 'david',
            'password' => bcrypt('123'),
            'idrol' => '1'
        ]);
    }
}
