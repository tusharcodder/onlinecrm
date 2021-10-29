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
            $table->string('type');
            $table->string('vendor_name')->nullable();
            $table->string('contact_person_name')->nullable();
            $table->string('contact_person_number')->nullable();
            $table->string('contact_person_email')->nullable();
			$table->string('commission_type', 50)->nullable();
			$table->string('commission', 50)->nullable();
			$table->integer('created_by');
			$table->integer('updated_by');
            $table->timestamps();
			$table->index(['type','vendor_name']);
        });
		
		Schema::create('aggregator_has_vendors', function (Blueprint $table) {
			 
			$table->bigIncrements('id');
            $table->unsignedBigInteger('vendor_id');
			$table->string('aggregator_vendor_name')->nullable();
			$table->string('aggregator_vendor_commission', 50)->nullable();
	
            $table->index(['aggregator_vendor_name']);

            $table->foreign('vendor_id')
                ->references('id')
                ->on('vendors')
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
        Schema::dropIfExists('vendors');
        Schema::dropIfExists('aggregator_has_vendors');
    }
}
