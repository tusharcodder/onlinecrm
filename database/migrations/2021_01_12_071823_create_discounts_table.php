<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
			$table->bigIncrements('id');
            $table->string('vendor_type')->nullable();
            $table->string('vendor_name')->nullable();
            $table->string('aggregator_vendor_name')->nullable();
            $table->string('product_code')->nullable();
            $table->string('discount', 100)->nullable();
            $table->date('valid_from_date')->nullable();
			$table->date('valid_to_date')->nullable();
			$table->integer('created_by');
			$table->integer('updated_by');
            $table->timestamps();
			
			$table->index(['vendor_name','product_code']);
        });	
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('discounts');
    }
}
