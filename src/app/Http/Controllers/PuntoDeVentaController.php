<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Producto;
use App\Venta;

class PuntoDeVentaController extends Controller
{
    function index(Request $request) {
        $sesion = $this->sesion($request);
        return view('puntodeventa.index', compact('sesion'));
    }

    function sesion(Request $request) {
        $sesion = collect($request->session()->get('puntodeventa', []))
              ->map(function($row) {
                  return [ Producto::find($row[0]), $row[1]];
              });
        $total = $sesion->reduce(function($carry, $row) {
            list($producto, $cantidad) = $row;
            return $carry+$cantidad*$producto->precio;
        }, 0);
        return view('puntodeventa.sesion', compact('sesion', 'total'));
    }

    function agregar(Request $request) {
        $sesion = collect($request->session()->get("puntodeventa", []));
        $cantidad = 1;
        $codigo = $request->get('codigo');

        if(strpos($codigo, "*") !== false)
            list($cantidad, $codigo) = explode("*", $codigo);

        $producto = Producto::where('codigo', $codigo)->first();

        if(!$producto) return ["status"=>false];
        
        $sesion->add([$producto->id, $cantidad]);

        $request->session()->put("puntodeventa", $sesion);
        return ["status"=>true];
    }

    function limpiar(Request $request) {
        $request->session()->put("puntodeventa", []);
        return redirect()->route('puntodeventa.index');
    }

    function guardar(Request $request) {
        $sesion = collect($request->session()->get('puntodeventa', []));
        if($sesion->count() == 0 ) {
            return redirect()->route('puntodeventa.index')
                ->withErrors('No hay productos en esta sesión');
        }
        $venta = Venta::crear(Auth::user(), $sesion->toArray());
        $request->session()->put("puntodeventa", []);
        return redirect()->route("puntodeventa.recibo", $venta);
    }

    function recibo(Venta $venta) {
        return view('puntodeventa.recibo', compact('venta'));
    }
}
