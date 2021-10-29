<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuyersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buyers', function (Blueprint $table) {
            //
			$table->bigIncrements('id');
            $table->string('name')->unique();
            $table->string('country')->nullable();
            $table->longText('address')->nullable();
			$table->integer('created_by');
			$table->integer('updated_by');
            $table->timestamps();
        });
		
		Schema::create('contact_person_has_buyers', function (Blueprint $table) {
			 
			$table->bigIncrements('id');
            $table->unsignedBigInteger('buyer_id');
			$table->string('contact_person_name');
			$table->string('contact_person_email')->nullable();
			$table->string('contact_person_number')->nullable();
	
            $table->index(['contact_person_name']);

            $table->foreign('buyer_id')
                ->references('id')
                ->on('buyers')
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
        /* Schema::table('buyers', function (Blueprint $table) {
            //
        }); */
		Schema::dropIfExists('buyers_has_contact_person');
		Schema::dropIfExists('buyers');
    }
}
