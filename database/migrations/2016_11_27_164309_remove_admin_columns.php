<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class RemoveAdminColumns extends Migration
{
    public function up()
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn('admin');
            $table->dropColumn('superAdmin');
        });
    }

    public function down()
    {
        Schema::table('members', function (Blueprint $table) {
            $table->boolean('admin')->after('id');
            $table->boolean('superAdmin')->after('admin');
        });
    }
}
