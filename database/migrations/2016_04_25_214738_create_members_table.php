<?php
	
/*
	@ Harris Christiansen (Harris@HarrisChristiansen.com)
	2016-04-25
	Project: Members Tracking Portal
*/

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMembersTable extends Migration {
	public function up() {
		Schema::create('members', function (Blueprint $table) {
			$table->increments('id');
			$table->boolean('admin');
			$table->string('name');
			$table->string('email')->unique();
			$table->string('email_public');
			$table->string('email_edu');
			$table->string('password');
			$table->enum('member_status', ['Member', 'Alumni', 'Inactive']);
			$table->enum('gender', ['', 'Male', 'Female', 'Other', 'No']);
			$table->smallInteger('graduation_year');
			$table->integer('major_id')->unsigned()->nullable();
			$table->text("picture");
			$table->text("description");
			$table->text("facebook");
			$table->text("github");
			$table->text("linkedin");
			$table->text("devpost");
			$table->text("website");
			$table->text("resume");
			$table->timestamps();
		});
	}
	
	public function down() {
		Schema::drop('members');
	}
}
