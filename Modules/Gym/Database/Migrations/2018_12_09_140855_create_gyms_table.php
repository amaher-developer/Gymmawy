<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGymsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gyms', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('gym_brand_id')->index();
            $table->foreign('gym_brand_id')->references('id')
                ->on('gym_brands')
                ->onDelete('cascade');
            $table->unsignedInteger('district_id')->index();
            $table->foreign('district_id')->references('id')
                ->on('districts')
                ->onDelete('cascade');
            $table->string('cover_image')->nullable();
            $table->string('image')->nullable();
            $table->string('address')->nullable();
            $table->jsonb('phones')->nullable();
            $table->integer('views')->default(0);
            $table->float('lat')->nullable();
            $table->float('lng')->nullable();
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
        Schema::dropIfExists('gyms');
    }
}
