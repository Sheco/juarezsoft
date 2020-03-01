<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SolicitudCompra extends Model
{
    protected $table = "solicitudes_compra";
    protected $fillable = [ 'proveedor_id', 'producto_id', 'cantidad', 'status' ];


    public function producto() {
        return $this->belongsTo('App\Producto');
    }

    public function proveedor() {
        return $this->belongsTo('App\Proveedor');
    }

    static function crear(Proveedor $proveedor, Producto $producto, $cantidad) {
        return self::create([
            'proveedor_id'=>$proveedor->id,
            'producto_id'=>$producto->id,
            'cantidad'=>$cantidad,
            'status'=>'nueva',
        ]);
    }

    public function pagar() {
        $this->fecha_pago = Carbon::now();
        $this->save();
    }

    public function entregada() {
        DB::transaction(function() {
            $this->fecha_entregada = Carbon::now();
            $this->save();

            $this->producto->stock += $this->cantidad;
            $this->producto->save();
        });
    }
}
