<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGymBrandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gym_brands', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->index();
            $table->foreign('user_id')->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->string('name_en');
            $table->string('name_ar');
            $table->string('main_phone')->nullable();
            $table->string('logo')->nullable();
            $table->jsonb('socials')->nullable();
            $table->text('description_ar')->nullable();
            $table->text('description_en')->nullable();
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
        Schema::dropIfExists('gym_brands');
    }
}
