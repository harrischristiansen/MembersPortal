<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationsTable extends Migration {
    public function up() {
		Schema::create('applications', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('member_id')->unsigned();
			$table->foreign('member_id')->references('id')->on('members');
			$table->integer('event_id')->unsigned();
			$table->foreign('event_id')->references('id')->on('events');
			$table->text("tshirt");
			$table->text("interests");
			$table->text("dietary");
			$table->timestamps();
		});
	}

	public function down() {
		Schema::drop('applications');
	}
}
