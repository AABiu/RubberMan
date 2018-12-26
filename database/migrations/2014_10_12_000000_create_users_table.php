<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('account_id')->nullable()->comment('账户id');
            $table->string('name')->nullable()->comment('用户名');
            $table->string('mobile')->nullable()->comment('手机号');
            $table->string('email')->nullable()->comment('邮箱');
            $table->string('nickname')->nullable()->comment('微信昵称');
            $table->string('avatar')->nullable()->comment('微信头像');
            $table->string('open_id')->nullable()->comment('微信open id');
            $table->timestamp('email_verified_at')->nullable()->comment('邮箱认证时间');
            $table->string('password')->nullable()->comment('登录密码');
            $table->tinyInteger('status')->default(1)->comment('用户状态:(1:正常，2：禁用)');
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
}
