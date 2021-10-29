<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerformancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('performances', function (Blueprint $table) {
			$table->bigIncrements('id');
            $table->string('product_code')->nullable();
            $table->string('category')->nullable();
			$table->string('sale_through', 100)->nullable();
			$table->integer('created_by');
			$table->integer('updated_by');
            $table->timestamps();
			
			$table->index(['product_code','category']);
        });	
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('performances');
    }
}
