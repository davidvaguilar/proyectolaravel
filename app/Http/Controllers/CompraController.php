<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Compra;
use App\DetalleCompra;

class CompraController extends Controller
{
    public function index( Request $request ){
        //if( !$request->ajax() ) return redirect('/');
        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if( $buscar == '' ){
            $compras = Compra::join('proveedores', 'compras.idproveedor','=', 'proveedores.id')
                    ->join('users', 'compras.idusuario', '=', 'users.id')
                    ->select('compras.id','compras.tipo_identificacion','compras.num_compra',
                            'compras.fecha_compra','compras.impuesto', 'compras.total', 'compras.estado', 
                            'proveedores.nombre', 'users.usuario')
                    ->orderBy('compras.id', 'desc')->paginate(3);
        } else {
            $compras = Compra::join('proveedores', 'compras.idproveedor','=', 'proveedores.id')
                    ->join('users', 'compras.idusuario', '=', 'users.id')
                    ->select('compras.id','compras.tipo_identificacion','compras.num_compra',
                            'compras.fecha_compra','compras.impuesto', 'compras.total', 'compras.estado', 
                            'proveedores.nombre', 'users.usuario')
                    ->where('compras.'.$criterio, 'like', '%'.$buscar.'%')
                    ->orderBy('compras.id', 'desc')->paginate(3);
        }
        
        return[
            'pagination' => [
                'total' => $compras->total(),
                'current_page' => $compras->currentPage(),
                'per_page' => $compras->perPage(),
                'last_page' => $compras->lastPage(),
                'from' => $compras->firstItem(),
                'to' => $compras->lastItem(),
            ],
            'compras' => $compras
        ];
    }

    public function store(Request $request)
    {
        if( !$request->ajax()) return redirect('/');
        try{
            DB::beginTransaction();
            $mytime = Carbon::now('America/Santiago');

            $compra = new Compra();
            $compra->idproveedor = $request->idproveedor;
            $compra->idusuario = Auth::user()->id;
            $compra->tipo_identificacion = $request->tipo_identificacion;
            $compra->num_compra = $request->num_compra;
            $compra->fecha_compra = $mytime->toDateString();
            $compra->impuesto = $request->impuesto;
            $compra->total = $request->total;
            $compra->estado = 'Registrado';
            $compra->save();

            $detalles->$request->data;

            foreach( $detalles  as $a => $det ){
                $detalle = new DetalleCompra();
                $detalle->idcompra = $compra->id;
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

    public function desactivar(Request $request){
        if( !$request->ajax()) return redirect('/');
        $compra = Compra::findOrFail($request->id);
        $compra->estado = 'Anulado';
        $compra->save();
    }

   

}
