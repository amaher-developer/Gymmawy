<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TableGymServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gym_service', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('service_id')->index();
            $table->foreign('service_id')->references('id')
                ->on('services')
                ->onDelete('cascade');

            $table->unsignedInteger('gym_id')->index();
            $table->foreign('gym_id')->references('id')
                ->on('gyms')
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
        Schema::dropIfExists('gym_service');
    }
}
