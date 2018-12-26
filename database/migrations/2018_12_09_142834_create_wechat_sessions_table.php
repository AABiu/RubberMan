<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateWechatSessionsTable.
 */
class CreateWechatSessionsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('wechat_sessions', function(Blueprint $table) {
            $table->increments('id');
            $table->string('user_id')->nullable()->comment('用户id');
            $table->string('open_id')->nullable()->comment('微信open id');
            $table->string('session_key')->nullable()->comment('微信session key');
            $table->string('union_id')->nullable()->comment('微信union id');
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
		Schema::drop('wechat_sessions');
	}
}
