<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration {
	public function up() {
		Schema::create('projects', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->integer('event_id')->unsigned()->nullable()->index();
			$table->foreign('event_id')->references('id')->on('events');
			$table->text("description");
			$table->text("github");
			$table->text("devpost");
			$table->timestamps();
			$table->softDeletes();
		});

		Schema::create('member_project', function(Blueprint $table) {
			$table->integer('member_id')->unsigned()->index();
			$table->foreign('member_id')->references('id')->on('members');
			$table->integer('project_id')->unsigned()->index();
			$table->foreign('project_id')->references('id')->on('projects');
			$table->primary(['member_id','project_id']);
			$table->timestamps();
		});
	}
	
	public function down() {
		Schema::drop('projects');
		Schema::drop('member_project');
	}
}