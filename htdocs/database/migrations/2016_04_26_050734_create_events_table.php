<?php
	
/*
	@ Harris Christiansen (Harris@HarrisChristiansen.com)
	2016-04-25
	Project: Members Tracking Portal
*/

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration {
    public function up() {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
			$table->string('name');
			$table->string('location');
			$table->timestamp('event_time');
			$table->text('facebook');
			$table->boolean('requiresApplication');
			$table->boolean('requiresRegistration');
            $table->timestamps();
			$table->softDeletes();
        });

		Schema::create('event_member', function(Blueprint $table) {
			$table->integer('event_id')->unsigned()->index();
			$table->foreign('event_id')->references('id')->on('events');
			$table->integer('member_id')->unsigned()->index();
			$table->foreign('member_id')->references('id')->on('members');
			$table->primary(['event_id','member_id']);
			$table->timestamps();
		});
    }

    public function down() {
        Schema::drop('event_member');
        Schema::drop('events');
    }
}
