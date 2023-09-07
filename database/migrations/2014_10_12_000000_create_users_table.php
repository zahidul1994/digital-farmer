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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
             $table->enum('user_type',['Superadmin','Admin','Staff','Mso','Lco','SubLco'])->default('Admin');
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->unsignedBigInteger('staff_id')->nullable();
            $table->unsignedBigInteger('mso_id')->nullable();
            $table->unsignedBigInteger('lco_id')->nullable();
            $table->unsignedBigInteger('sub_lco_id')->nullable();
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->string('username')->unique();
            $table->string('password');
            $table->integer('otp')->nullable();
            $table->float('balance',16,2)->default(0);
            $table->string('image')->default('not-found.webp');
            $table->date('account_expire_date')->nullable();
            $table->timestamp('last_login')->nullable();
            $table->bigInteger('created_user_id')->default(1);
            $table->bigInteger('updated_user_id')->default(1);
            $table->timestamp('email_verified_at')->nullable();
            $table->ipAddress('ip_address')->default('101.2.160.0');
            $table->tinyInteger('staff_quantity')->default(1);
            $table->tinyInteger('status')->default(0);
            $table->softDeletes();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
