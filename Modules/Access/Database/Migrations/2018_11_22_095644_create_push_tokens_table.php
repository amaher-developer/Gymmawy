<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePushTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('push_tokens', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->index();
            $table->foreign('user_id')->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->integer('device_type');
            $table->string('token');
//            $table->string('one_signal_token');
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
        Schema::dropIfExists('push_tokens');
    }
}
