<?php

namespace App;

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
            $venta->save();

            foreach($productos as $producto) {
                list($id, $cantidad) = $producto;
                $venta->productos()->attach(Producto::find($id), [
                    'cantidad' => $cantidad
                ]);
            }
            return $venta;
        });
    }
}
