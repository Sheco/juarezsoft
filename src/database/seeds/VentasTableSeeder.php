<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Producto;
use App\Venta;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class VentasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->poblaMesAleatoriamente(Carbon::now()->format('Y-m-d'), 5);
    }

    private function ventasAleatoriasDelDia($fecha, $ventaTotal, $porcentajeMargen=0) {
        // crear una venta aleatoria.
        // Generar una hora aleatoria entre 9:00 y 7:00pm
        // Obtener un usuario aleatorio
        // Hacer in ciclo en donde se obtenga un producto aleatorio
        // y una cantidad aleatoria entre 1 y 3, hasta que la venta 
        // tenga un total de al menos $1000

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

        $objetoAleatorioForzado = function(
            $lista,
            $catalogo,
            $nombre,
            $random,
            $frecuencia) use ($objetoAleatorio) {
            while(!$obj = $objetoAleatorio($lista, $catalogo,
                $nombre, $random, $frecuencia)) {
                if($random < 10) 
                    return null;
                $random = $random * 0.8;
            }
            return $obj;
        };

        $obtenerUsuario = function($random) use ($objetoAleatorioForzado) {
            $usuarios = User::role('vendedor')->get();
            return $objetoAleatorioForzado($usuarios, 
                'usuarios', 
                'name', 
                $random,
                'frecuenciaVentas');
        };

        $obtenerProducto = function($random) use ($objetoAleatorioForzado) {
            $productos = Producto::all();
            return $objetoAleatorioForzado($productos, 
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

            while($total<$compraMinima) {
                $producto = $obtenerProducto(rand(0, 100));
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
            $compraMinima = 2000;
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

    function poblaMesAleatoriamente($fecha, $margen) {
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
            $this->ventasAleatoriasDelDia($dia->format('Y-m-d'), $ingreso, $margen);
        }
    }
}
