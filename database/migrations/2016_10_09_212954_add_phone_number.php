<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPhoneNumber extends Migration {
	public function up() {
		Schema::table('members', function (Blueprint $table) {
			$table->string('phone')->after('email');
			$table->timestamp('authenticated_at')->after('updated_at');
		});
	}
	
	public function down() {
		Schema::table('members', function (Blueprint $table) {
			$table->dropColumn('phone');
			$table->dropColumn('authenticated_at');
		});
	}
}
