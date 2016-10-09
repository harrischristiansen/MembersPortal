<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPhoneNumber extends Migration {
	public function up() {
		Schema::table('members', function (Blueprint $table) {
			$table->string('phone')->after('email');
		});
	}
	
	public function down() {
		Schema::table('members', function (Blueprint $table) {
			$table->dropColumn('phone');
		});
	}
}
