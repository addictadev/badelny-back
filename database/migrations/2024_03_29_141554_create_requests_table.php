<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('from')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('to')->nullable()->constrained('users')->onDelete('cascade');
            $table->integer('exchange_type')->nullable();
            $table->json('buyer_product_id')->nullable()->constrained('products')->onDelete('cascade');
            $table->foreignId('seller_product_id')->nullable()->constrained('products')->onDelete('cascade');
            $table->integer('points')->nullable();
            $table->tinyInteger('status')->default(0);
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
        Schema::dropIfExists('requests');
    }
};
