<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePasswordResetsCodesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('password_resets_codes', function (Blueprint $table) {
            $table->id('id');
            $table->string('mobile')->index();
            $table->string('code')->index();
            $table->string('email')->index();
            $table->integer('expired')->index()->default(0);
            $table->datetime('expired_at')->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('password_resets_codes');
    }
}
