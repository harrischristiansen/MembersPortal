<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPrivateEvents extends Migration {
	public function up() {
		Schema::table('events', function (Blueprint $table) {
			$table->boolean('privateEvent')->after('id');
		});
	}
	public function down() {
		Schema::table('events', function (Blueprint $table) {
			$table->dropColumn('privateEvent');
		});
	}
}
