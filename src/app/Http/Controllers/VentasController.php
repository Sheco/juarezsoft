<?php

namespace App\Http\Controllers;

use App\Venta;
use App\Producto;
use App\Factura;
use Carbon\Carbon;

use Illuminate\Http\Request;

class VentasController extends Controller
{
    function diarias(Request $request) {
        $now = Carbon::now();

        $fecha_inicio = $request->input('fecha_inicio', $now->clone()->subtract(1, 'month')->format('Y-m-d'));
        $fecha_final = $request->input('fecha_final', $now->format('Y-m-d'));
        $datos = Venta::reporteDiario($fecha_inicio, $fecha_final);

        $fechas = $datos->map(function($x) { return $x->fecha; });
        $totales = $datos->map(function($x) { return $x->total; });

        return view('ventas.diarias', compact(
            'fecha_inicio', 'fecha_final', 'fechas', 'totales'
        ));
    }

    function productos(Request $request) {
        $now = Carbon::now();
        $fecha_inicio = $request->input('fecha_inicio', $now->clone()->startOfMonth()->format('Y-m-d'));
        $fecha_final = $request->input('fecha_final', $now->clone()->endOfMonth()->format('Y-m-d'));
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
        $fecha_inicio = $request->input('fecha_inicio', date('Y-m-d'));
        $fecha_final = $request->input('fecha_final', date('Y-m-d'));

        $datos = Venta::reporteVendedores($fecha_inicio, $fecha_final);
        return view('ventas.vendedores', compact(
            'fecha_inicio', 'fecha_final', 'datos'
        ));
    }

    function ver(Venta $venta) {
        return view('ventas.ver', compact('venta'));
    }

    function buscar(Request $request) {
        $id = $request->get('id', '');
        $fecha = $request->get('fecha', '');
        $total = $request->get('total', '');
        return view('ventas.buscar', compact('id', 'fecha', 'total'));
    }

    function buscarReal(Request $request) {
        $request->validate([
            'id' => ['required', function($attribute, $value, $fail) use($request) {
                $venta = Venta::where('id', $value)
                    ->where('fecha', $request->get('fecha'))
                    ->where('total', $request->get('total'))
                    ->first();

                if(!$venta) return $fail('No se encontró la venta');

                if($venta->estaFacturada()) 
                    return $fail('La venta ya esta facturada');
            }],
            'fecha'=>'required',
            'total'=>'required'
        ]);

        $venta = Venta::find($request->get('id'));
        return redirect()->route('ventas.ver', $venta);
    }

    function facturar(Venta $venta, Request $request) {
        $request->validate([
            'nombre'=>['required', function($attribute, $value, $fail) 
                use ($venta) {
                    if($venta->estaFacturada()) 
                        return $fail('Esta venta ya esta facturada');
                }],
            'rfc'=>'required',
            'direccion'=>'required',
            'codigopostal'=>'required',
            'telefono'=>'required',
            'email'=>'required']);

        $venta->facturar($request->all());
        return redirect()->route('ventas.ver', $venta);
    }

    function factura(Venta $venta) {
        $factura = $venta->factura;
        return view("ventas.factura", compact('venta', 'factura'));
    }
}
