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
        Schema::create('video_uploads', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->enum('upload_type',['Video','Text','File'])->default('Video');
            $table->string('text_title')->nullable();
            $table->string('video_link',500)->nullable();
            $table->string('text_link',500)->nullable();
            $table->string('file_link',500)->nullable();
            $table->unsignedBigInteger('created_by_user_id')->default(1);
            $table->unsignedBigInteger('updated_by_user_id')->default(1);
            $table->tinyInteger('status')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_uploads');
    }
};
