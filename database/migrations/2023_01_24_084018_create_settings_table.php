<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('superadmin_id')->unsigned()->nullable();
            $table->foreign('superadmin_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('company_name')->nullable();
            $table->string('project_name')->nullable();
            $table->string('website_name',500)->nullable();
            $table->string('website_title',500)->nullable();
            $table->string('address',500)->nullable();
            $table->string('currency_name',30)->nullable();
            $table->string('currency_symbole',30)->nullable();
            $table->string('bin_number')->nullable();
            $table->string('vat_number')->nullable();
            $table->string('print_headline',300)->nullable();
            $table->string('print_message')->nullable();
            $table->string('printing_logo',300)->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('favicon',300)->default('default.png');
            $table->string('logo',300)->default('default.png');
            $table->string('company_logo')->nullable();
            $table->string('print_logo',300)->default('default.png');
            $table->string('facebook',300)->nullable();
            $table->string('youtube',300)->nullable();
            $table->string('twitter',300)->nullable();
            $table->string('instagram',300)->nullable();
            $table->bigInteger('created_user_id')->default(1);
            $table->bigInteger('updated_user_id')->default(1);
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
};
