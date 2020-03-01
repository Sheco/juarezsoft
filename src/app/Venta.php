<?php

namespace App;

use Carbon\Carbon;
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
        return DB::transaction(function() use ($user, $productos) {
            $venta = new Venta;
            $venta->user_id = $user->id;
            $venta->fecha_hora = Carbon::now();
            $venta->fecha = $venta->fecha_hora->format('Y-m-d');
            $venta->save();

            foreach($productos as $producto) {
                list($id, $cantidad) = $producto;
                $producto = Producto::find($id);
                $producto->stock -= $cantidad;
                $producto->save();

                $venta->productos()->attach($producto, [
                    'cantidad' => $cantidad
                ]);
            }
            return $venta;
        });
    }

    public function total() {
        return $this->productos->reduce(function($total, $producto) { 
            $cantidad = $producto->pivot->cantidad;
            return $total+($producto->precio*$cantidad); 
        }, 0); 
    }

    public static function ventasAleatoriasDelDia($fecha, $ventaTotal) {
        // crear una venta aleatoria.
        // Generar una hora aleatoria entre 9:00 y 7:00pm
        // Obtener un usuario aleatorio
        // Hacer in ciclo en donde se obtenga un producto aleatorio
        // y una cantidad aleatoria entre 1 y 3, hasta que la venta 
        // tenga un total de al menos $1000
        $productos = Producto::all();
        $usuarios = User::all();
            
        $ventaAleatoria = function($fecha, $compraMinima) 
            use ($productos, $usuarios) {
            $fecha = (new Carbon($fecha))
                ->add(rand(0, 12*60*60), 'seconds');
            $usuario = $usuarios->shuffle()->slice(0, 1)->first();

            $total = 0;
            $venta_productos = [];

            echo "Inicia compra..\n";
            while($total<$compraMinima) {
                $producto = $productos->shuffle()->slice(0, 1)->first();
                $cantidad = rand(1, 3);
                $total += $producto->precio * $cantidad;
                echo "Vendiendo $cantidad {$producto->nombre}, por ". ($cantidad*$producto->precio)."\n";
                $venta_productos[] = [ $producto->id, $cantidad ];    
            }
            echo "Total de la operacion: $". number_format($total) ."\n";
            $venta = Venta::crear($usuario, $venta_productos);
            $venta->fecha_hora = $fecha;
            $venta->fecha = $fecha->format('Y-m-d');
            $venta->save();
            return [ $venta, $total ];
        };

        // generar ventas hasta llegar al objetivo
        return DB::transaction(function() 
            use ($fecha, $ventaTotal, $ventaAleatoria) {
            $total = 0;
            $compraMinima = 1000;
            $ventas = [];

            while($total<$ventaTotal) {
                list($venta, $totalOperacion) = $ventaAleatoria($fecha, $compraMinima);
                $total += $totalOperacion;
                $ventas[] = $venta;
            }
            echo "Total del dia: $". number_format($total, 2) ."\n";
            return $ventas;
        });
    }
}
