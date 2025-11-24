<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryTrainerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_trainer', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('category_id')->index();
            $table->foreign('category_id')->references('id')
                ->on('categories')
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
        Schema::dropIfExists('category_trainer');
    }
}
