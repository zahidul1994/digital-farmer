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
        Schema::create('areas', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('thana_id')->unsigned()->nullable();
            $table->foreign('thana_id')->references('id')->on('thanas')->onDelete('cascade');
            $table->string('area');
            $table->string('bn_area')->nullable();
            $table->unique(['thana_id','area']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('areas');
    }
};
