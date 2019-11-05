<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Cotizacion;
use App\DetalleCotizacion;

class CotizacionController extends Controller
{
    public function index( Request $request ){
        if( !$request->ajax() ) return redirect('/');
        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if( $buscar == '' ){
            $cotizaciones = Cotizacion::join('proveedores', 'cotizaciones.idproveedor','=', 'proveedores.id')
                    ->join('users', 'cotizaciones.idusuario', '=', 'users.id')
                    ->select('cotizaciones.id','cotizaciones.tipo_identificacion','cotizaciones.num_cotizacion',
                            'cotizaciones.fecha_cotizacion', 'cotizaciones.total', 'cotizaciones.estado', 
                            'proveedores.nombre', 'users.usuario')
                    ->orderBy('cotizaciones.id', 'desc')->paginate(3);
        } else {
            $cotizaciones = Cotizacion::join('proveedores', 'cotizaciones.idproveedor','=', 'proveedores.id')
                    ->join('users', 'cotizaciones.idusuario', '=', 'users.id')
                    ->select('cotizaciones.id','cotizaciones.tipo_identificacion','cotizaciones.num_cotizacion',
                            'cotizaciones.fecha_cotizacion', 'cotizaciones.total', 'cotizaciones.estado', 
                            'proveedores.nombre', 'users.usuario')
                    ->where('cotizaciones.'.$criterio, 'like', '%'.$buscar.'%')
                    ->orderBy('cotizaciones.id', 'desc')->paginate(3);
        }
        
        return[
            'pagination' => [
                'total' => $cotizaciones->total(),
                'current_page' => $cotizaciones->currentPage(),
                'per_page' => $cotizaciones->perPage(),
                'last_page' => $cotizaciones->lastPage(),
                'from' => $cotizaciones->firstItem(),
                'to' => $cotizaciones->lastItem(),
            ],
            'cotizaciones' => $cotizaciones
        ];
    }

    public function obtenerCabecera(Request $request){
        if( !$request->ajax() ) return redirect('/');
        $id = $request->id;
        $cotizacion = Cotizacion::join('proveedores','cotizaciones.idproveedor','=','proveedores.id')
                ->join('users','cotizaciones.idusuario','=','users.id')
                ->select('cotizaciones.id','cotizaciones.tipo_identificacion',
                        'cotizaciones.num_cotizacion','cotizaciones.fecha_cotizacion','cotizaciones.total',
                        'cotizaciones.estado','proveedores.nombre','users.usuario')
                ->where('cotizaciones.id','=',$id)
                ->orderBy('cotizaciones.id', 'desc')->take(1)->get();         
        return ['cotizacion' => $cotizacion];
    }

    public function obtenerDetalles(Request $request){
        if (!$request->ajax()) return redirect('/');
        $id = $request->id;
        $detalles = DetalleCotizacion::join('productos','detalle_cotizaciones.idproducto','=','productos.id')
                ->select('detalle_cotizaciones.cantidad','detalle_cotizaciones.precio','productos.nombre as producto')
                ->where('detalle_cotizaciones.idcotizacion','=',$id)
                ->orderBy('detalle_cotizaciones.id', 'desc')->get();         
        return ['detalles' => $detalles];
    }

    //FALTA PARA IMPRIMIR

    public function store(Request $request)
    {
        if( !$request->ajax()) return redirect('/');
        try{
            DB::beginTransaction();
            $mytime = Carbon::now('America/Santiago');

            $cotizacion = new Cotizacion();
            $cotizacion->idproveedor = $request->idproveedor;
            $cotizacion->idusuario = \Auth::user()->id;
            $cotizacion->tipo_identificacion = $request->tipo_identificacion;
            $cotizacion->num_cotizacion = $request->num_cotizacion;
            $cotizacion->fecha_cotizacion = $mytime->toDateTimeString();
            $cotizacion->total = $request->total;
            $cotizacion->estado = 'Registrado';
            $cotizacion->save();

            $detalles = $request->data;
            foreach( $detalles  as $a => $det ){
                $detalle = new DetalleCotizacion();
                $detalle->idcotizacion = $cotizacion->id;
                $detalle->idproducto = $det['idproducto'];
                $detalle->cantidad = $det['cantidad'];
                $detalle->precio = $det['precio'];     
                $detalle->save();
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    public function desactivar( Request $request ){
        if( !$request->ajax() ) return redirect('/');
        $cotizacion = Cotizacion::findOrFail($request->id);
        $cotizacion->estado = 'Anulado';
        $cotizacion->save();
    }
}
