<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Cotizacion extends Model
{
    protected $table = 'cotizaciones';

    protected $fillable = ['idproveedor', 'idusuario', 'tipo_identificacion',
                         'num_cotizacion', 'fecha_cotizacion', 'total', 'estado'];

    public function usuario(){
        return $this->belongsTo('App\User');
    }

    public function proveedor(){
        return $this->belongsTo('App\Proveedor');
    }

    public function getFechaCotizacionAttribute($value)
    {
        $fecha = Carbon::parse($value);
        return $fecha->format('d-m-Y H:i:s');
    }

    public function getCreatedAtAttribute($value)
    {
        $meses = array( "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", 
                        "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre" );
        $fecha = Carbon::parse($value);
        return $fecha->format('d-m-Y H:i:s');
    }

}
