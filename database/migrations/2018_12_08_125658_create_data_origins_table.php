<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateDataOriginsTable.
 */
class CreateDataOriginsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('data_origins', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('one')->nullable()->comment('千位');
            $table->unsignedInteger('two')->nullable()->comment('百位');
            $table->unsignedInteger('three')->nullable()->comment('十位');
            $table->unsignedInteger('four')->nullable()->comment('个位');
            $table->string('str')->nullable()->comment('直码');
            $table->timestamps();
            $table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('data_origins');
	}
}
