<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TableGymFavorites extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gym_favorites', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('gym_id')->index();
            $table->foreign('gym_id')->references('id')
                ->on('gyms')
                ->onDelete('cascade');

            $table->unsignedInteger('user_id')->index();
            $table->foreign('user_id')->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->softDeletes();
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
        Schema::dropIfExists('gym_favorites');
    }
}
