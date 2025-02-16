<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request){
        $anio = date('Y');

        /*$compras = DB::table('compras as c')
            ->select(DB::raw('MONTHNAME(c.fecha_compra) as mes'),
            DB::raw('YEAR(c.fecha_compra) as anio'),
            DB::raw('SUM(c.total) as total'))
            ->whereYear('c.fecha_compra', $anio)
            ->groupBy( DB::raw('MONTHNAME(c.fecha_compra)'), DB::raw('YEAR(c.fecha_compra)') )
            ->get();*/

        $compras = DB::table('compras as c')
            ->select(DB::raw("CONCAT(DAY(c.fecha_compra),'-',MONTHNAME(c.fecha_compra)) as mes"),
                    DB::raw('YEAR(c.fecha_compra) as anio'),
                    DB::raw('SUM(c.total) as total'))
            ->whereYear('c.fecha_compra', $anio)
            ->groupBy( DB::raw("CONCAT(DAY(c.fecha_compra),'-',MONTHNAME(c.fecha_compra))"), DB::raw('YEAR(c.fecha_compra)') )
            ->get();

        

        /*$ventas = DB::table('ventas as v')
            ->select(DB::raw('MONTHNAME(v.fecha_venta) as mes'),
            DB::raw('YEAR(v.fecha_venta) as anio'),
            DB::raw('SUM(v.total) as total'))
            ->whereYear('v.fecha_venta', $anio)
            ->groupBy( DB::raw('MONTHNAME(v.fecha_venta)'), DB::raw('YEAR(v.fecha_venta)') )
            ->get();*/

        $ventas = DB::table('ventas as v')
            ->select(DB::raw("CONCAT(DAY(v.fecha_venta),'-',MONTHNAME(v.fecha_venta)) as mes"),
                    DB::raw('YEAR(v.fecha_venta) as anio'),
                    DB::raw('SUM(v.total) as total'))
            ->whereYear('v.fecha_venta', $anio)
            ->groupBy( DB::raw("CONCAT(DAY(v.fecha_venta),'-',MONTHNAME(v.fecha_venta))"), DB::raw('YEAR(v.fecha_venta)') )
            ->get();
        return ['compras' => $compras, 'ventas' => $ventas, 'anio' => $anio];             
    }
}
