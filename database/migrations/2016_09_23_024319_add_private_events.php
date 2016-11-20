<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPrivateEvents extends Migration {
	public function up() {
		Schema::table('events', function (Blueprint $table) {
			$table->boolean('privateEvent')->after('id');
		});
		Schema::table('event_member', function(Blueprint $table) {
			$table->integer('recorded_by')->unsigned()->nullable()->index()->after('member_id');
			$table->foreign('recorded_by')->references('id')->on('members');
		});
	}
	public function down() {
		Schema::table('events', function (Blueprint $table) {
			$table->dropColumn('privateEvent');
		});
		Schema::table('event_member', function (Blueprint $table) {
			$table->dropColumn('recorded_by');
		});
	}
}
