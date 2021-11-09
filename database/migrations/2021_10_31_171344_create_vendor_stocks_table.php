<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_stocks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('isbnno')->nullable();
            $table->string('name')->nullable();
			$table->string('author')->nullable();
            $table->string('publisher')->nullable();
            $table->date('stock_date')->nullable();
            $table->string('price')->nullable();
			$table->string('discount')->nullable();
			$table->string('quantity')->nullable();
			$table->integer('created_by');
			$table->integer('updated_by');
            $table->timestamps();
			
			$table->foreignId('vendor_id')
                ->references('id')
                ->on('vendors')
                ->onDelete('cascade');
				
			$table->foreignId('binding_id')
                ->references('id')
                ->on('bindings')
                ->onDelete('cascade');
				
			$table->foreignId('currency_id')
                ->references('id')
                ->on('currenciess')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendor_stocks');
    }
}
