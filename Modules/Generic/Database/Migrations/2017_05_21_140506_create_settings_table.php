<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name_en')->nullable();
            $table->string('name_ar')->nullable();
            $table->string('phone')->nullable();
            $table->text('meta_description_en')->nullable();
            $table->text('meta_keywords_en')->nullable();
            $table->text('meta_description_ar')->nullable();
            $table->text('meta_keywords_ar')->nullable();
            $table->string('logo_en')->nullable();
            $table->string('logo_ar')->nullable();
            $table->string('logo_white_en')->nullable();
            $table->string('logo_white_ar')->nullable();
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('google_plus')->nullable();
            $table->string('instagram')->nullable();
            $table->string('youtube')->nullable();
            $table->text('ios_app')->nullable();
            $table->string('android_app')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('support_email')->nullable();
            $table->string('noreply_email')->nullable();
            $table->text('about_en')->nullable();
            $table->text('about_ar')->nullable();
            $table->text('terms_en')->nullable();
            $table->text('terms_ar')->nullable();
            $table->text('address_en')->nullable();
            $table->text('address_ar')->nullable();
            $table->boolean('under_maintenance')->default(false);
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
        Schema::dropIfExists('settings');
    }
}
