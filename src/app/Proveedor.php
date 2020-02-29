<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table = "proveedores";

    protected function productos() {
        return $this->belongsToMany('App\Productos', 'proveedor_productos')
                    ->withPivot([
                        'precio'
                    ]);
    }
}
