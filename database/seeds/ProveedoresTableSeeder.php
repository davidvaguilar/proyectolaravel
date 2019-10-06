<?php

use Illuminate\Database\Seeder;
use App\Proveedor;

class ProveedoresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Proveedor::create([
            'nombre' => 'juan',
            'tipo_documento' => 'CEDULA',
            'num_documento'=> '1111-1',
        ]);

        Proveedor::create([
            'nombre' => 'pedro',
            'tipo_documento' => 'CEDULA',
            'num_documento'=> '2222-2',
        ]);

        Proveedor::create([
            'nombre' => 'luis',
            'tipo_documento' => 'CEDULA',
            'num_documento'=> '3333-3',
        ]);

    }
}
