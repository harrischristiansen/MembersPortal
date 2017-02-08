<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddRememberToken extends Migration
{
    public function up()
    {
        Schema::table('members', function (Blueprint $table) {
            $table->rememberToken();
        });
    }

    public function down()
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn('remember_token');
        });
    }
}
