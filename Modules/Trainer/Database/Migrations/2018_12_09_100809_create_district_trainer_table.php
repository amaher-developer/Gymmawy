<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDistrictTrainerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('district_trainer', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('district_id')->index();
            $table->foreign('district_id')->references('id')
                ->on('districts')
                ->onDelete('cascade');

            $table->unsignedInteger('trainer_id')->index();
            $table->foreign('trainer_id')->references('id')
                ->on('trainers')
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
        Schema::dropIfExists('district_trainer');
    }
}
