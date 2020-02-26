<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("nombre");
            $table->string("codigo");
            $table->decimal("precio", 9, 2);
            $table->integer("stock")->default(0);
            $table->unsignedBigInteger("departamento_id");
            $table->timestamps();
           
            $table->foreign("departamento_id")
                ->references("id")->on("departamentos");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('productos');
    }
}
