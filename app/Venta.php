<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Venta extends Model
{
    protected $table = 'ventas';
    protected $fillable =[
        'idcliente', 
        'idusuario',
        'tipo_identificacion',
        'num_venta',
        'fecha_venta',
        'impuesto',
        'total',
        'estado'
    ];

    public function getFechaVentaAttribute($value)
    {
        $fecha = Carbon::parse($value);
        return $fecha->format('d-m-Y H:i:s');
    }

    public function getCreatedAtAttribute($value)
    {
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $fecha = Carbon::parse($value);
        return $fecha->format('d-m-Y H:i:s');
        //return $fecha->format('d').' de '.$meses[($fecha->format('n')) - 1].' de '.$fecha->format('Y').' '.$fecha->format('H:i:s');
    }
}
