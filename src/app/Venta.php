<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Venta extends Model
{
    protected $table = 'ventas';
    protected $dates = [
        'fecha_hora', 
    ];

    protected $casts = [
        'fecha' => 'datetime:Y-m-d'
    ];

    public function productos() {
        return $this->belongsToMany('App\Producto', 'venta_productos')
            ->withPivot([
                'cantidad',
                'precio',
            ]);
    }

    public function user() {
        return $this->belongsTo('App\User');
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
                    'precio'=> $producto->precio,
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
                DB::raw('sum(venta_productos.precio*cantidad) as total'))
            ->groupBy('fecha', 'productos.id')
            ->whereBetween('fecha', [ $fecha_inicio, $fecha_final ]);

        if($producto_id)
            $query->where('venta_productos.producto_id', $producto_id);

        foreach(explode(',', $orden) as $o) {
            $query->orderBy($o, 'desc');
        }
        
        return $query->get();
    }

    public static function reporteInventario() {
        $fecha_final = Carbon::now();
        $fecha_inicio = $fecha_final->clone()->subtract(7, 'days');

        return DB::table('venta_productos')
            ->join('productos', 'productos.id', '=', 'venta_productos.producto_id')
            ->join('ventas', 'ventas.id', '=', 'venta_productos.venta_id')
            ->select(
                'productos.id',
                'productos.nombre', 
                'productos.stock',
                DB::raw('sum(cantidad) as vendidos'),
                )
            ->groupBy('productos.id')
            ->havingRaw("productos.stock-(sum(cantidad)*2) < 0")
            ->whereBetween('fecha', [ $fecha_inicio, $fecha_final ])
            ->orderByRaw('productos.stock-sum(cantidad)')
            ->get();
    }

    public static function reporteVendedores($fecha_inicio, $fecha_final) {
        if(!$fecha_inicio || !$fecha_final) return collect([]);

        return DB::table('ventas')
            ->join('users', 'users.id', '=', 'ventas.user_id')
            ->select('users.name as nombre', 
                DB::raw('count(*) as cantidad'),
                DB::raw('sum(total) as total')
            )
            ->groupBy('users.name')
            ->orderByRaw('sum(total) desc')
            ->whereBetween('fecha', [ $fecha_inicio, $fecha_final])
            ->get();
    }

    function facturar($datos) {
        $datos["venta_id"] = $this->id;
        $datos["status"] = "normal";
        $factura = Factura::create($datos);
        $factura->save();
        return $factura;
    }

    public function facturas() {
        return $this->hasMany('App\Factura');
    }

    function getFacturaAttribute() {
        return $this->facturas->where('status', 'normal')->first();
    }

    function estaFacturada() {
        return Factura::where('venta_id', $this->id)
            ->where('status', 'normal')
            ->count()>0;
    }
}
