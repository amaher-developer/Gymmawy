<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGymDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gym_discounts', function (Blueprint $table) {
            $table->increments('id');
            $table->text('description');
            $table->string('image')->nullable();
            $table->unsignedInteger('gym_id')->index()->nullable();
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
        Schema::dropIfExists('gym_discounts');
    }
}
