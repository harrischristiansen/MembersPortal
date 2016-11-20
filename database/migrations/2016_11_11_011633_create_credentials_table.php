<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCredentialsTable extends Migration {
	public function up() {
		Schema::create('credentials', function (Blueprint $table) {
			$table->increments('id');
			$table->string('site');
			$table->string('username');
			$table->string('password');
			$table->text("description");
			$table->integer('member_id')->unsigned();
			$table->foreign('member_id')->references('id')->on('members');
			$table->timestamps();
			$table->softDeletes();
		});
	}
	
	public function down() {
		Schema::drop('credentials');
	}
}
