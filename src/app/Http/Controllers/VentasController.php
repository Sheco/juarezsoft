<?php

namespace App\Http\Controllers;

use App\Venta;

use Illuminate\Http\Request;

class VentasController extends Controller
{
    function diarias(Request $request) {
        $fecha_inicio = $request->input('fecha_inicio');
        $fecha_final = $request->input('fecha_final');
        $datos = Venta::reporteDiario($fecha_inicio, $fecha_final);
        return view('ventas.diarias', compact(
            'fecha_inicio', 'fecha_final', 'datos'
        ));
    }

    function productos(Request $request) {
        $fecha_inicio = $request->input('fecha_inicio');
        $fecha_final = $request->input('fecha_final');
        $orden = $request->input('orden');

        $datos = Venta::reporteProductos($fecha_inicio, $fecha_final, $orden);
        return view('ventas.productos', 
            compact('fecha_inicio', 'fecha_final', 'orden', 'datos')
        );
    }
}
