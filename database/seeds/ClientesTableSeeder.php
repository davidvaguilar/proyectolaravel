<?php

use Illuminate\Database\Seeder;
use App\Cliente;

class ClientesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Cliente::create([
            'nombre' => 'pedro',
            'tipo_documento' => 'CEDULA',
            'num_documento' => '123455',
            'direccion' => 'av tucapel',
            'telefono' => '123456',
            'email' => 'pedro@correo.cl'
        ]);
    }
}
