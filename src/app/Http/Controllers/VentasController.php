<?php

namespace App\Http\Controllers;

use App\Venta;
use App\Producto;

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
        $producto_id = $request->input('producto_id');

        $productos = Producto::with([
            'departamento'=>function($query) { 
                $query->orderBy('nombre'); 
            }])->get();

        $datos = Venta::reporteProductos($fecha_inicio, $fecha_final, 
            $orden, $producto_id);

        return view('ventas.productos', compact(
            'fecha_inicio', 'fecha_final', 'orden',             
            'datos', 'productos', 'producto_id'
        ));
    }

    function vendedores(Request $request) {
        $fecha_inicio = $request->input('fecha_inicio');

        $datos = Venta::reporteVendedores($fecha_inicio);
        return view('ventas.vendedores', compact(
            'fecha_inicio', 'datos'
        ));
    }
}
