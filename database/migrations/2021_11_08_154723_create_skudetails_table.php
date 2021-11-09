<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSkudetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('skudetails', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('market_id')->nullable();
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->string('isbn13',50)->nullable();
            $table->string('isbn10',50)->nullable();
            $table->string('sku_code',50)->nullable();
            $table->string('mrp',50)->nullable();
            $table->string('disc',50)->nullable();
            $table->string('wght',50)->nullable();
            $table->string('pkg_wght',50)->nullable();
            $table->integer('created_by')->nullable();
			$table->integer('updated_by')->nullable();
            $table->foreign('market_id')->references('id')->on('market_places')->onDelete('cascade');
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
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
        Schema::dropIfExists('skudetails');
    }
}
