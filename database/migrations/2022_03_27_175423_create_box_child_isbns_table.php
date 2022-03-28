<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoxChildIsbnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('box_child_isbns', function (Blueprint $table) {
            $table->bigIncrements('id');
			 $table->unsignedBigInteger('box_isbn_id')->nullable();
			$table->string('book_isbn13')->nullable();			
			$table->integer('created_by')->nullable();
			$table->integer('updated_by')->nullable();
$table->foreign('box_isbn_id')->references('id')->on('box_parent_isbns')->onDelete('cascade');
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
        Schema::dropIfExists('box_child_isbns');
    }
}
