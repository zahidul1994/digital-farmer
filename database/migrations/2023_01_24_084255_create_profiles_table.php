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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->enum('gender',['Male','Female','Other'])->default('Male');
            $table->string('company_name')->nullable();
            $table->string('comment',500)->nullable();
            $table->string('position')->nullable();
            $table->string('company_logo')->nullable();
            $table->integer('rating')->nullable();
            $table->string('nid_number')->nullable();
            $table->string('country')->default('Bangladesh');
            $table->string('division')->nullable();
            $table->string('district')->nullable();
            $table->string('thana')->nullable();
            $table->string('area')->nullable();
            $table->string('company_address',400)->nullable();
            $table->string('owner_name')->nullable();
            $table->string('web_address',400)->nullable();
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
        Schema::dropIfExists('profiles');
    }
};
