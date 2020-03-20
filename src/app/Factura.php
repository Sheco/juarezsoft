<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    protected $table = 'facturas';
    protected $fillable = ['rfc', 'nombre', 'direccion', 'codigopostal',
        'telefono', 'email', 'venta_id', 'status'];
}
