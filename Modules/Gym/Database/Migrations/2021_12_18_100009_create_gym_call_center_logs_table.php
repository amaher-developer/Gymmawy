<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGymCallCenterLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gym_call_center_logs', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('gym_id')->index();
            $table->foreign('gym_id')->references('id')
                ->on('gyms')
                ->onDelete('cascade');
            $table->text('comment')->nullable();
            $table->integer('rate')->nullable()->default(0);
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
        Schema::dropIfExists('gym_call_center_logs');
    }
}
