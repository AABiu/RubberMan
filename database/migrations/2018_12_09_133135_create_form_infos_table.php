<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateFormInfosTable.
 */
class CreateFormInfosTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('form_infos', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable()->comment('用户id');
            $table->string('form_id')->nullable()->comment('微信form id');
            $table->string('account_id')->nullable()->comment('账户 id');
            $table->string('open_id')->nullable()->comment('微信open id');
            $table->tinyInteger('status')->default(1)->comment('状态:(1:可用，2：不可用)');
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
		Schema::drop('form_infos');
	}
}
