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

    /**
     *   Crear ventas aleatoriamente, entre las 9:00am y 7:00pm
     *   Usando un vendedor aleatorio y una lista de productos aleatorios
     *
     *   @return void
     */
    private function ventasAleatoriasDelDia($fecha, $ventaTotal, $porcentajeMargen=0) {

        /**
         * Con una lista de objetos, los filtra dejando solo los que sean
         * candidatos validos según la frecuencia y devuelve uno de ellos
         * al azar
         */
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

        /**
         * Obtiene un objeto aleatorio, bajando el valor de frecuencia
         * de ser necesario para forzar que se obtenga un elemento
         */
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

        /**
         * Wrapper para obtener un usuario aleatorio
         */
        $obtenerUsuario = function($random) use ($objetoAleatorioForzado) {
            $usuarios = User::role('vendedor')->get();
            return $objetoAleatorioForzado($usuarios, 
                'usuarios', 
                'name', 
                $random,
                'frecuenciaVentas');
        };

        /**
         * Wrapper para obtener un producto aleatorio
         */
        $obtenerProducto = function($random) use ($objetoAleatorioForzado) {
            $productos = Producto::all();
            return $objetoAleatorioForzado($productos, 
                'productos',
                'nombre', 
                $random,
                'frecuenciaCompras');
        };

        /**
         * Fingir una venta, esta funcion es basicamente la misma que
         * Venta::crear() pero con ligeros ajustes para este entorno
         * de población
         */
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
                        'precio' => $producto->precio,
                        'cantidad' => $cantidad
                    ]);
                }

                $venta->total = $total;
                $venta->save();

                return $venta;
            });
        };

        /**
         * Aquí es donde se obtiene el usuario y la lista de productos
         * para generar lo que es una venta individual.
         */    
        $ventaAleatoria = function($fecha, $compraMinima) 
            use ($obtenerProducto, $obtenerUsuario, $vender) {
            $fecha = (new Carbon("$fecha 09:00"))
                ->add(rand(0, 12*60*60), 'seconds');
            $usuario = $obtenerUsuario(rand(0, 100));

            $total = 0;
            $venta_productos = [];

            while($total<$compraMinima) {
                $producto = $obtenerProducto(rand(0, 100));
                $cantidad = rand(1, 3);
                $total += $producto->precio * $cantidad;

                $venta_productos[] = [ $producto->id, $cantidad ];    
            }

            if(!$total) {
                return [ null, 0 ];
            }

            $venta = $vender($usuario, $venta_productos, $fecha);
            return [ $venta, $total ];
        };

        /*
         * Generar ventas aleatorias hasta llegar a un monto de venta
         * especifico para el dia
         */
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
            echo "Total($fecha): $". number_format($total, 2) ."\n";
            return $ventas;
        });
    }

    /*
     * Genera ventas aleatorias día por día para llenar el mes, asegurandose
     * de que se cumplan los requerimientos de que entre semana se venda
     * cierto importe, fin de semana otro y un día aleatorio al mes otro importe
     */
    function poblaMesAleatoriamente($fecha, $margen) {
        $fecha = new Carbon($fecha);
        $inicio = $fecha->clone()->subtract(1, 'month')->startOfMonth();
        $fin = $fecha;
        DB::table('ventas')->whereBetween('fecha', [$inicio, $fin])->delete();

        $dias = $fin->diff($inicio)->days;
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
