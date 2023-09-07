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
        Schema::create('districts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('division_id')->unsigned()->nullable();
            $table->foreign('division_id')->references('id')->on('divisions')->onDelete('cascade');
            $table->string('district');
            $table->string('bn_district')->nullable();
            $table->unique(['division_id','district']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('districts');
    }
};
