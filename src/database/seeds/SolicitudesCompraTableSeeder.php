<?php

use Illuminate\Database\Seeder;
use App\Producto;
use App\Proveedor;
use App\SolicitudCompra;
use Carbon\Carbon;


class SolicitudesCompraTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $productos = Producto::all();
        echo "Creando solicitudes de compra...\n";
        foreach($productos as $producto) {
            // buscaremos un proveedor aleatorio y le compraremos 100 de este
            // producto, la fecha de solicitud, la pondremos de hace 2 semanas
            // aproximadamente

            $proveedor = $producto->proveedores->shuffle()->first();
            $cantidad = 1000;
            echo "Comprando $cantidad $producto->nombre a $proveedor->nombre\n";

            $fecha_solicitud = Carbon::now()
                ->subtract(14+rand(0, 7), 'days');
            $fecha_pago = $fecha_solicitud->copy()
                ->add(2+rand(1, 4), 'days');
            $fecha_entrega = $fecha_pago->copy()
                ->add(2+rand(1, 4), 'days');

            $solicitud = SolicitudCompra::crear(
                $proveedor,
                $producto,
                $cantidad
            );

            // Ajustar las fechas, manualmente
            $solicitud->created_at = $fecha_solicitud;
            $solicitud->fecha_pago = $fecha_pago;
            $solicitud->save();

            $solicitud->entregada();
            $solicitud->fecha_entregada = $fecha_entrega;
            $solicitud->save();
        }
    }
}
