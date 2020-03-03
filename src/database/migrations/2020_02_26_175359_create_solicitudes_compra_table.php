<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSolicitudesCompraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicitudes_compra', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('proveedor_id');
            $table->unsignedBigInteger('producto_id');
            $table->unsignedInteger("cantidad");
            $table->enum("status", ['nueva', 'pagada', 'entregada', 'cancelada']);
            $table->dateTime('fecha_pago')->nullable();
            $table->dateTime('fecha_entregada')->nullable();
            $table->timestamps();

            $table->index('proveedor_id');
            $table->index('producto_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('proveedor_solicitudes_compra');
    }
}
