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
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
           $table->bigInteger('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->float('debit',16,4)->default('0');
            $table->float('credit',16,4)->default('0');
            $table->enum('type', ['refer', 'commission','join','payment','withdraw','renew','receive','sms','other'])->nullable();
            $table->string('invoice')->nullable();
            $table->string('note',500)->nullable();
            $table->string('payment_method')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('transaction_number')->nullable();
            $table->unsignedBigInteger('receiver_id');
            $table->unsignedBigInteger('created_user_id')->default(1);
            $table->unsignedBigInteger('updated_user_id')->default(1);
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('receiver_seen')->default(0);
             $table->softDeletes('deleted_at', 0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};
