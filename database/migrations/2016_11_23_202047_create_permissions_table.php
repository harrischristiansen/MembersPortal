<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionsTable extends Migration {
	public function up() {
		Schema::create('permissions', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->string('description');
			$table->timestamps();
		});

		Schema::create('member_permission', function(Blueprint $table) {
			$table->integer('member_id')->unsigned()->index();
			$table->foreign('member_id')->references('id')->on('members');
			$table->integer('permission_id')->unsigned()->index();
			$table->foreign('permission_id')->references('id')->on('permissions');
			$table->primary(['member_id','permission_id']);
			$table->integer('recorded_by')->unsigned()->nullable()->index();
			$table->foreign('recorded_by')->references('id')->on('members');
			$table->timestamps();
		});
	}
	
	public function down() {
		Schema::drop('permissions');
		Schema::drop('member_permission');
	}
}
