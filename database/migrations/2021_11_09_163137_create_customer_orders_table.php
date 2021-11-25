<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->string('order_id')->nullable();
            $table->string('order_item_id')->nullable();
			$table->string('purchase_date')->nullable();
            $table->string('payments_date')->nullable();
            $table->string('reporting_date')->nullable();
            $table->string('promise_date')->nullable();
            $table->string('days_past_promise')->nullable();
			$table->string('buyer_email')->nullable();
			$table->string('buyer_name')->nullable();
			$table->string('buyer_phone_number')->nullable();
			$table->string('sku')->nullable();
			$table->longText('product_name')->nullable();
			$table->string('quantity_purchased')->nullable();
			$table->string('quantity_shipped')->nullable();
			$table->string('quantity_to_ship')->nullable();
			$table->string('quantity_to_be_shipped')->default(0)->nullable();
			$table->string('ship_service_level')->nullable();
			$table->string('recipient_name')->nullable();
			$table->longText('ship_address_1')->nullable();
			$table->longText('ship_address_2')->nullable();
			$table->longText('ship_address_3')->nullable();
			$table->string('ship_city')->nullable();
			$table->string('ship_state')->nullable();
			$table->string('ship_postal_code')->nullable();
			$table->string('ship_country')->nullable();
			$table->string('is_business_order')->nullable();
			$table->string('purchase_order_number')->nullable();
			$table->string('price_designation')->nullable();			
			$table->string('price')->nullable();
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
        Schema::dropIfExists('customer_orders');
    }
}
