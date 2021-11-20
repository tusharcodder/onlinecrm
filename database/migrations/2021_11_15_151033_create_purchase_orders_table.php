<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('bill_no')->nullable();
            $table->string('isbn13',50)->nullable();
            $table->longText('book_title')->nullable();
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->integer('quantity')->nullable();
            $table->decimal('mrp',10,2)->nullable();
            $table->decimal('discount',10,2)->nullable();
            $table->decimal('cost_price',10,2)->nullable();
            $table->string('purchase_by')->nullable();
            $table->date('purchase_date')->nullable();
            $table->integer('created_by');
			$table->integer('updated_by');
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
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
        Schema::dropIfExists('purchase_orders');
    }
}
