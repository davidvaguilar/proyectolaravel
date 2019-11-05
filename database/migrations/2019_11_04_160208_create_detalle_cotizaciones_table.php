<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetalleCotizacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_cotizaciones', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idcotizacion')->unsigned();
            $table->foreign('idcotizacion')->references('id')->on('cotizaciones')->onDelete('cascade');
            $table->integer('idproducto')->unsigned();
            $table->foreign('idproducto')->references('id')->on('productos');
            $table->integer('cantidad');
            $table->integer('precio')->unsigned(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalle_cotizaciones');
    }
}
