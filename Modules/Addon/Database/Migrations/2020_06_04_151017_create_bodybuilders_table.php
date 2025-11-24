<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBodybuildersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bodybuilders', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('country_id')->index();
            $table->foreign('country_id')->references('id')
                ->on('countries')
                ->onDelete('cascade');

            $table->string('name_ar');
            $table->string('name_en');

            $table->text('description_ar')->nullable();
            $table->text('description_en')->nullable();
            $table->string('image');
            $table->string('cover_image')->nullable();
            $table->date('birthday')->nullable();

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
        Schema::dropIfExists('bodybuilders');
    }
}
