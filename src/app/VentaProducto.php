<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VentaProducto extends Model
{
    protected $table = "venta_productos";

    public function venta() {
        return $this->belongsTo('App\Venta', 'venta_producto');
    }
}
