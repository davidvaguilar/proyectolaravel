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
            'nombre' => 'pepe',
            'tipo_documento' => 'CEDULA',
            'num_documento' => '11111111',
            'direccion' => 'tucapel',
            'telefono' => '572222222',
            'email' => 'pepe@correo.cl'
        ]);
    }
}
