<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCalorieFoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calorie_foods', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedInteger('category_id')->index();
            $table->foreign('category_id')->references('id')
                ->on('calorie_categories')
                ->onDelete('cascade');
            $table->string('name_ar');
            $table->string('name_en');
            $table->integer('calories');
            $table->integer('unit');
            $table->integer('unit_id')->index();
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
        Schema::dropIfExists('calorie_foods');
    }
}
