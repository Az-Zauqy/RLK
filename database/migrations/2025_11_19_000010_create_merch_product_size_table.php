<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchProductSizeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            Schema::create('merch_product_variant_sizes', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('merch_product_variant_id');
                $table->string('size')->default('Default');
                $table->integer('stock')->default(0);
                $table->integer('diskon')->default(0);
                $table->integer('price')->default(0);
                $table->integer('weight')->nullable();
                $table->integer('long')->default(10);
                $table->integer('width')->default(10);
                $table->integer('height')->default(10);
                $table->string('sku')->nullable();
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
        Schema::dropIfExists('merch_product_variant_sizes');
    }
}
