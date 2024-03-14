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
        Schema::create('products', function (Blueprint $table) {
            $table->id('id');
            $table->string('name')->nullable();
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('cascade');
            $table->foreignId('sub_category_id')->nullable()->constrained('categories')->onDelete('cascade');
            $table->string('weight')->nullable();
            $table->integer('condition')->nullable();
            $table->string('color')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('is_approve')->default(0);
            $table->integer('exchange_options')->nullable();
            $table->string('price')->nullable();
            $table->string('points')->nullable();
            $table->mediumText('description')->nullable();
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
        Schema::drop('products');
    }
};
