<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchCategoryProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merch_category_product', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('merch_product_id');
            $table->unsignedBigInteger('merch_category_id');

            $table->foreign('merch_product_id')
                ->references('id')->on('merch_products')
                ->onDelete('cascade');

            $table->foreign('merch_category_id')
                ->references('id')->on('merch_categories')
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
        Schema::dropIfExists('merch_category_product');
    }
}
