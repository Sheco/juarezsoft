<?php

namespace App;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Venta extends Model
{
    protected $table = 'ventas';

    public function productos() {
        return $this->belongsToMany('App\Producto', 'venta_productos')
            ->withPivot([
                'cantidad'
            ]);
    }

    /* 
     * $productos es un arreglo de arreglos con dos elementos,
     * el id del producto y la cantidad de productos a vender
     * Por ejemplo, vender diez productos con id 1
     *    $productos = [
     *      [ 1, 10 ]
     *    ]
     * */
    public static function crear(User $user, array $productos = []) {
        // TODO: agregar permisos/privilegios
        //  El usuario $user puede crear ventas?
        return DB::transaction(function() use ($user, $productos) {
            $venta = new Venta;
            $venta->user_id = $user->id;
            $venta->fecha_hora = Carbon::now();
            $venta->fecha = $venta->fecha_hora->format('Y-m-d');
            $venta->save();

            $total = 0;
            foreach($productos as $producto) {
                list($id, $cantidad) = $producto;
                $producto = Producto::find($id);
                $producto->stock -= $cantidad;
                $producto->save();

                $total += $producto->precio*$cantidad;

                $venta->productos()->attach($producto, [
                    'cantidad' => $cantidad
                ]);
            }
            
            $venta->total = $total;
            $venta->save();

            return $venta;
        });
    }

    public function total() {
        return $this->productos->reduce(function($total, $producto) { 
            $cantidad = $producto->pivot->cantidad;
            return $total+($producto->precio*$cantidad); 
        }, 0); 
    }

    /* reportes */

    public static function reporteDiario($fecha_inicio,
        $fecha_final) {

        if(!$fecha_inicio || !$fecha_final)
            return collect([]);

        return DB::table('ventas')
            ->select('fecha', 
                DB::raw('sum(total) as total'))
            ->whereBetween('fecha', [ $fecha_inicio, $fecha_final ])
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get();
    }

    public static function reporteProductos($fecha_inicio, $fecha_final,
        $orden, $producto_id=null) {
        if(!$fecha_inicio || !$fecha_final) 
            return collect([]);

        if(!$orden) $orden = "total";

        $query = DB::table('venta_productos')
            ->join('productos', 'productos.id', '=', 'venta_productos.producto_id')
            ->join('ventas', 'ventas.id', '=', 'venta_productos.venta_id')
            ->select('ventas.fecha', 
                DB::raw('sum(cantidad) as cantidad'),
                'productos.nombre', 
                DB::raw('sum(productos.precio*cantidad) as total'))
            ->groupBy('fecha', 'productos.id')
            ->whereBetween('fecha', [ $fecha_inicio, $fecha_final ]);

        if($producto_id)
            $query->where('venta_productos.producto_id', $producto_id);

        foreach(explode(',', $orden) as $o) {
            $query->orderBy($o, 'desc');
        }
        
        return $query->get();
    }

    public static function reporteVendedores($fecha) {
        if(!$fecha) return collect([]);

        $fecha = new Carbon($fecha);
        $inicio = $fecha->clone()->startOfMonth()->format('Y-m-d');
        $fin = $fecha->clone()->endOfMonth()->format('Y-m-d');

        return DB::table('ventas')
            ->join('users', 'users.id', '=', 'ventas.user_id')
            ->select('users.name as nombre', 
                DB::raw('count(*) as cantidad'),
                DB::raw('sum(total) as total')
            )
            ->groupBy('users.name')
            ->orderByRaw('sum(total) desc')
            ->whereBetween('fecha', [ $inicio, $fin])
            ->get();
    }

}
