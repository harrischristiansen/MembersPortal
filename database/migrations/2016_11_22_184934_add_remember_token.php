<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRememberToken extends Migration {
    public function up() {
        Schema::table('members', function (Blueprint $table) {
            $table->rememberToken();
        });
    }
    public function down() {
        Schema::table('members', function (Blueprint $table) {
			$table->dropColumn('remember_token');
        });
    }
}
