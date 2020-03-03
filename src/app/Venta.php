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

    public static function reporteProductos($fecha_inicio, 
        $fecha_final,
        $orden) {
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

        foreach(explode(',', $orden) as $o) {
            $query->orderBy($o, 'desc');
        }
        
        return $query->get();
    }

    public static function ventasAleatoriasDelDia($fecha, $ventaTotal, $porcentajeMargen=0) {
        // crear una venta aleatoria.
        // Generar una hora aleatoria entre 9:00 y 7:00pm
        // Obtener un usuario aleatorio
        // Hacer in ciclo en donde se obtenga un producto aleatorio
        // y una cantidad aleatoria entre 1 y 3, hasta que la venta 
        // tenga un total de al menos $1000
        echo "Iniciando...";

        $objetoAleatorio = function(
            $lista,
            $catalogo, 
            $nombre, 
            $random,
            $frecuencia) {

            return $lista->filter(function($item) use ($random, $frecuencia) {
                return $item->$frecuencia >= $random;
            })->shuffle()->first();
        };

        $obtenerUsuario = function($random) use ($objetoAleatorio) {
            $usuarios = User::role('vendedor')->get();
            return $objetoAleatorio($usuarios, 
                'usuarios', 
                'name', 
                $random,
                'frecuenciaVentas');
        };

        $obtenerProducto = function($random) use ($objetoAleatorio) {
            $productos = Producto::all();
            return $objetoAleatorio($productos, 
                'productos',
                'nombre', 
                $random,
                'frecuenciaCompras');
        };

        
        $vender = function(User $user, $productos, $fecha) {
            return DB::transaction(function() use ($user, $productos, $fecha) {
                $venta = new Venta;
                $venta->user_id = $user->id;
                $venta->fecha_hora = $fecha;
                $venta->fecha = $fecha->format('Y-m-d');
                $venta->save();

                $total = 0;
                foreach($productos as $producto) {
                    list($id, $cantidad) = $producto;

                    $producto = Producto::find($id);
                    $total += $producto->precio*$cantidad;

                    $venta->productos()->attach($producto, [
                        'cantidad' => $cantidad
                    ]);
                }

                $venta->total = $total;
                $venta->save();

                return $venta;
            });
        };
            
        $ventaAleatoria = function($fecha, $compraMinima) 
            use ($obtenerProducto, $obtenerUsuario, $vender) {
            $fecha = (new Carbon($fecha))
                ->add(rand(0, 12*60*60), 'seconds');
            $usuario = $obtenerUsuario(rand(0, 100));

            $total = 0;
            $venta_productos = [];

            $rareza = rand(0, 100);
            while($total<$compraMinima) {
                $producto = $obtenerProducto(intval($rareza*0.8));
                if(!$producto) {
                    $rareza = rand(0, $rareza);
                    if($rareza<10)
                        break;
                    continue;
                }
                $cantidad = min(rand(1, 3), $producto->stock);
                $total += $producto->precio * $cantidad;

                echo "Vendiendo $cantidad {$producto->nombre}: $". 
                    ($cantidad*$producto->precio) ."\n";

                $venta_productos[] = [ $producto->id, $cantidad ];    
            }

            echo "Total de la operacion: $". number_format($total) ."\n";
            if(!$total) {
                return [ null, 0 ];
            }

            $venta = $vender($usuario, $venta_productos, $fecha);
            return [ $venta, $total ];
        };

        // generar ventas hasta llegar al objetivo
        return DB::transaction(function() 
            use ($fecha, $ventaTotal, $ventaAleatoria, $porcentajeMargen) {
            $total = 0;
            $compraMinima = 1000;
            $ventas = [];
            $margen = $ventaTotal*$porcentajeMargen/100;
            $ventaTotal += rand(0, $margen*2)-$margen;

            while($total<$ventaTotal) {
                list($venta, $totalOperacion) = $ventaAleatoria($fecha, $compraMinima);
                if(!$venta) {
                    break;
                }
                $total += $totalOperacion;
                $ventas[] = $venta;
            }
            echo "Total del dia: $". number_format($total, 2) ."\n";
            return $ventas;
        });
    }

    static function poblaMesAleatoriamente($fecha) {
        $fecha = new Carbon($fecha);
        $inicio = $fecha->clone()->startOfMonth();
        $fin = $fecha->clone()->endOfMonth();
        DB::table('ventas')->whereBetween('fecha', [$inicio, $fin])->delete();

        $dias = $fin->diff($inicio)->days+1;
        $especial = $inicio->clone()->add(rand(0, $dias), 'days');

        $periodo = CarbonPeriod::create($inicio, $dias);
        foreach($periodo as $dia) {
            $finDeSemana = $dia->isoWeekday()>5;
            $ingreso = $finDeSemana? 
                2500000:
                ($dia->format('Y-m-d')==$especial->format('Y-m-d')? 
                    1750000:
                    750000);
            Venta::ventasAleatoriasDelDia($dia->format('Y-m-d'), $ingreso, 20);
        }
    }
}
