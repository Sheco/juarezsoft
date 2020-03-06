<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Producto extends Model
{
    protected $table = "productos";
    protected $fillable = [ "nombre", "codigo", "precio", "departamento_id", 'frecuenciaCompras', 'stock' ];

    public function departamento() {
        return $this->belongsTo('App\Departamento');
    }

    public function proveedores() {
        return $this->belongsToMany('App\Proveedor', 'proveedor_productos')
                    ->withPivot([
                        'precio'
                    ]);
    }

    public function estadisticas(Carbon $fecha_inicio, Carbon $fecha_final) {
        // obtener las ventas diarias
        $data = DB::table('venta_productos')
            ->join('productos', 'productos.id', '=', 'venta_productos.producto_id')
            ->join('ventas', 'ventas.id', '=', 'venta_productos.venta_id')
            ->select(
                'ventas.fecha',
                DB::raw('sum(cantidad) as vendidos'),
                )
            ->where('productos.id', $this->id)
            ->whereBetween('fecha', [
                $fecha_inicio->format('Y-m-d'), 
                $fecha_final->format('Y-m-d') 
            ])
            ->orderByRaw('ventas.fecha')
            ->groupBy('ventas.fecha')
            ->get()
            ->mapWithKeys(function($row) {
                return [ $row->fecha => $row ];
            });

        /*
         *  es posible que no haya datos de algun día, asignarle 
         *  cero ventas y la misma cantidad de stock del dia anterior
         */
        $periodo = $fecha_inicio->toPeriod($fecha_final);
        foreach($periodo as $fecha) {
            $fecha = $fecha->format('Y-m-d');

            if(!isset($data[$fecha])){
                $data[$fecha] = json_decode(json_encode([
                    "fecha" => $fecha,
                    "vendidos" => "0",
                ])); 
            }
        }

        /* reordenar por fecha */
        return collect($data)->sortBy('fecha');
    }
}
