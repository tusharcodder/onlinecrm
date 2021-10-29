<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->date('sale_date')->nullable();
            $table->string('invoice_no')->nullable();
            $table->string('po_no')->nullable();
            $table->string('brand')->nullable();
			$table->string('category')->nullable();
            $table->string('vendor_type')->nullable();
            $table->string('vendor_name')->nullable();
            $table->string('aggregator_vendor_name')->nullable();
            $table->string('hsn_code')->nullable();
            $table->string('sku_code')->nullable();
            $table->string('product_code')->nullable();
			$table->string('colour')->nullable();
            $table->string('size')->nullable(); 
			$table->string('quantity')->nullable();
			$table->string('vendor_discount',100)->nullable();
			$table->string('mrp', 100)->nullable();
			$table->string('before_tax_amount', 100)->nullable();
			$table->string('state')->nullable();
			$table->string('cgst', 100)->nullable();
			$table->string('sgst', 100)->nullable();
			$table->string('igst', 100)->nullable();
			$table->string('sale_price', 100)->nullable();
			$table->string('total_sale_amount', 100)->nullable();
			$table->string('cost_price', 100)->nullable();
			$table->string('total_cost_amount', 100)->nullable();
			$table->string('receivable_amount', 100)->nullable();
			$table->integer('created_by');
			$table->integer('updated_by');
            $table->timestamps();
			$table->index(['invoice_no']);
			$table->index(['po_no']);
			$table->index(['brand']);
			$table->index(['category']);
			$table->index(['colour']);
			$table->index(['size']);
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
        Schema::dropIfExists('sales');
    }
}
