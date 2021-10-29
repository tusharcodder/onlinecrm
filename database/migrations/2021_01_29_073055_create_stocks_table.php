<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stocks', function (Blueprint $table) {
			$table->bigIncrements('id');
            $table->string('manufacturer_name')->nullable();
			$table->string('country', 100)->nullable();
            $table->date('manufacture_date')->nullable();
			$table->string('cost', 100)->nullable();
			$table->date('stock_date')->nullable();
            $table->string('brand')->nullable();
            $table->string('category')->nullable();
			$table->string('gender', 100)->nullable();
            $table->string('colour')->nullable();
            $table->string('size')->nullable();
			$table->string('lotno')->nullable();
            $table->string('sku_code')->nullable();
            $table->string('product_code')->nullable();
            $table->string('hsn_code')->nullable();
			$table->string('online_mrp', 100)->nullable();
			$table->string('offline_mrp', 100)->nullable();
			$table->string('quantity')->nullable();
			$table->longText('description')->nullable();
			$table->longText('image_url')->nullable();
			$table->integer('created_by');
			$table->integer('updated_by');
            $table->timestamps();
			$table->index(['brand']);
			$table->index(['category']);
			$table->index(['gender']);
			$table->index(['colour']);
			$table->index(['size']);
			$table->index(['lotno']);
			$table->index(['sku_code']);
			$table->index(['product_code']);
			$table->index(['hsn_code']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stocks');
    }
}
