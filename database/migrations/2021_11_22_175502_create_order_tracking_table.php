<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderTrackingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_tracking', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->string('order_id')->nullable();
			$table->string('order_item_id')->nullable();
			$table->string('price')->default(0)->nullable();
			$table->string('sku')->nullable();
			$table->string('isbnno')->nullable();
			$table->string('shipper')->nullable();	
			$table->string('tracking_id')->nullable();			
			$table->string('box_id')->nullable();
			$table->string('shipper_id')->nullable();
			$table->string('shipment_date')->nullable();
			$table->string('quantity_shipped')->nullable();
			$table->string('ncp')->nullable();
			$table->integer('created_by')->nullable();
			$table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('order_tracking');
    }
}
