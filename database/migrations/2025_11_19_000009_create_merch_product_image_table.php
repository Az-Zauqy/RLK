<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchProductImageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merch_product_variant_images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('merch_product_variant_id');
            $table->string('image_path');
            $table->string('label')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('merch_product_variant_id')
                ->references('id')->on('merch_product_variants')
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
        Schema::dropIfExists('merch_product_variant_images');
    }
}
