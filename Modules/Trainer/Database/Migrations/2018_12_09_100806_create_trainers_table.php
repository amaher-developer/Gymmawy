<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrainersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trainers', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('user_id')->index();
            $table->foreign('user_id')->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->unsignedInteger('city_id')->index();
            $table->foreign('city_id')->references('id')
                ->on('cities')
                ->onDelete('cascade');
            $table->string('name_en');
            $table->string('name_ar');
            $table->date('birthday')->nullable();
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('instagram')->nullable();
            $table->string('linkedin')->nullable();
            $table->text('about_en')->nullable();
            $table->text('about_ar')->nullable();
            $table->integer('experience')->nullable();
            $table->integer('gender')->nullable();
            $table->integer('views')->default(0);
            $table->string('image')->nullable();
            $table->string('gym_name')->nullable();
            $table->string('reference_url')->nullable();
            $table->boolean('published')->default(false)->index();
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
        Schema::dropIfExists('trainers');
    }
}
