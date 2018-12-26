<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateActivitiesTable.
 */
class CreateActivitiesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('activities', function(Blueprint $table) {
            $table->increments('id');
            $table->string('user_id')->nullable()->comment('用户id');
            $table->string('qs')->nullable()->comment('期数');
            $table->json('query')->nullable()->comment('查询条件');
            $table->text('result')->nullable()->comment('查询结果');
            $table->tinyInteger('status')->default(0)->comment('状态：0：未确定，1：准确，2：不准确');
            $table->unsignedInteger('like_num')->default(0)->comment('点赞数');
            $table->softDeletes();
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
		Schema::drop('activities');
	}
}
