<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMembersTable extends Migration {
	public function up() {
		Schema::create('members', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->string('email')->unique();
			$table->string('email_public');
			$table->string('email_edu');
			$table->string('password');
			$table->enum('member_status', ['Member', 'Alumni', 'Inactive']);
			$table->smallInteger('graduation_year');
			$table->text("facebook");
			$table->text("description");
			$table->timestamps();
		});
	}
	
	public function down() {
		Schema::drop('members');
	}
}
