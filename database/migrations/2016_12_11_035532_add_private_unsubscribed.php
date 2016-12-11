<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPrivateUnsubscribed extends Migration {
	public function up() {
		Schema::table('members', function (Blueprint $table) {
			$table->boolean('privateProfile')->after('username');
			$table->boolean('unsubscribed')->after('email');
		});
	}
	
	public function down() {
		Schema::table('members', function (Blueprint $table) {
			$table->dropColumn('privateProfile');
			$table->dropColumn('unsubscribed');
		});
	}
}
