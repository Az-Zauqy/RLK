<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merch_product', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->index();
            $table->integer('kategori_id')->index();
            $table->integer('karya_id')->index();
            $table->string('title');
            $table->string('slug');
            $table->text('description');
            $table->integer('price');
            $table->integer('diskon')->default(0);
            $table->integer('stock')->default(1);
            $table->string('sku')->nullable();
            $table->integer('weight');
            $table->integer('asuransi')->default(0);
            $table->integer('long')->default(10);
            $table->integer('width')->default(10);
            $table->integer('height')->default(10);
            $table->integer('status')->default(1); //1 aktif, 0, non aktif
            $table->string('views')->default(0);
            $table->string('kondisi')->nullable(); //1 baru, 0, bekas
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
        Schema::dropIfExists('merch_product');
    }
}
