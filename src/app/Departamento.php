<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    protected $table = "departamentos";
    protected $fillable = [ 'nombre' ];

    public function productos() {
        return $this->hasMany('App\Producto');
    }
}
