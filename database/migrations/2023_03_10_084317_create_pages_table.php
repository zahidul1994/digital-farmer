<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('superadmin_id')->unsigned()->nullable();
            $table->foreign('superadmin_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('page_name')->unique();
            $table->string('slug')->unique();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('json_screma',5000)->nullable();
            $table->string('keyword',500)->nullable();
            $table->mediumText('header_description')->nullable();
            $table->mediumText('footer_description')->nullable();
            $table->string('image',500)->default('default.png');
            $table->Integer('view')->default(0);
            $table->unsignedBigInteger('created_user_id')->default(1);
            $table->unsignedBigInteger('updated_user_id')->default(1);
            $table->tinyInteger('status')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
