<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendors', function (Blueprint $table) {
			$table->bigIncrements('id');           
            $table->string('name')->nullable();           
            $table->string('number')->nullable();
            $table->string('email')->nullable();
			$table->longText('address')->nullable();		
			$table->integer('created_by');
			$table->integer('updated_by');
            $table->timestamps();
			$table->index(['vendor_name']);
        });	
		
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendors');        
    }
}
