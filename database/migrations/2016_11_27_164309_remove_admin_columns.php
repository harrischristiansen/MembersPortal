<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveAdminColumns extends Migration {
    public function up() {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn('admin');
            $table->dropColumn('superAdmin');
        });
    }
    
    public function down() {
        Schema::table('members', function (Blueprint $table) {
			$table->boolean('admin')->after('id');
			$table->boolean('superAdmin')->after('admin');
        });
    }
}
