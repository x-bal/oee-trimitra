<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('line_id')->constrained('lines')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('art_code');
            $table->string('product_code');
            $table->string('name');
            $table->string('category')->nullable();
            $table->string('focuscategory')->nullable();
            $table->float('batchsize')->nullable();
            $table->float('stdspeed');
            $table->integer('pcskarton');
            $table->integer('netfill')->nullable();
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
        Schema::dropIfExists('products');
    }
}
