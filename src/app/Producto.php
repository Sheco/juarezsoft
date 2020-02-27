<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = "productos";
    protected $fillable = [ "nombre", "codigo", "precio", "departamento_id" ];

    public function departamento() {
        return $this->belongsTo('App\Departamento');
    }
}
