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
            'num_documento'=> '44444444',
        ]);

    }
}
