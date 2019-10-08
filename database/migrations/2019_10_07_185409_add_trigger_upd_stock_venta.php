<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTriggerUpdStockVenta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared(
            'CREATE TRIGGER tr_updStockVenta AFTER INSERT ON detalle_ventas FOR EACH ROW
            BEGIN
                UPDATE productos SET stock = stock - NEW.cantidad 
                WHERE id = NEW.idproducto;
            END'
        );
    }

    /*UPDATE `productos` SET `stock` = `stock` - NEW.cantidad 
                WHERE `productos.id` = NEW.idproducto;*/

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER tr_updStockVenta');
    }
}
