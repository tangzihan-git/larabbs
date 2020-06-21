<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration 
{
	public function up()
	{
		Schema::create('articles', function(Blueprint $table) {
            $table->increments('id');
            $table->string('title')->index();
            $table->text('desc')->nullable();
            $table->text('content');
            $table->integer('user_id')->unsigned()->default(1);
            $table->timestamps();
        });
	}

	public function down()
	{
		Schema::drop('articles');
	}
}
