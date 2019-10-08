<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Compra extends Model
{
    protected $table = 'compras';

    protected $fillable= ['idproveedor', 'idusuario', 'tipo_identificacion', 'num_compra', 'fecha_compra', 'impuesto', 'total', 'estado'];

    public function usuario(){
        return $this->belongsTo('App\User');
    }

    public function proveedor(){
        return $this->belongsTo('App\Proveedor');
    }

    public function getFechaCompraAttribute($value)
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
