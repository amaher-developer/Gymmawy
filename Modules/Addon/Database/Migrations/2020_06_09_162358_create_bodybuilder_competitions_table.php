<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBodybuilderCompetitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bodybuilder_competitions', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedInteger('bodybuilder_id')->index();
            $table->foreign('bodybuilder_id')->references('id')
                ->on('bodybuilders')
                ->onDelete('cascade');

            $table->string('name_ar');
            $table->string('name_en');
            $table->string('year', 8);

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
        Schema::dropIfExists('bodybuilder_competitions');
    }
}
