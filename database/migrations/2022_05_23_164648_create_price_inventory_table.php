<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePriceInventoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_inventory', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->string('sku')->nullable();			
			$table->integer('price')->nullable();
			$table->integer('quantity')->nullable();
            $table->integer('lead_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('price_inventory');
    }
}
