<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TableCategoryGymTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_gym', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('category_id')->index();
            $table->foreign('category_id')->references('id')
                ->on('categories')
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
        Schema::dropIfExists('category_gym');
    }
}
