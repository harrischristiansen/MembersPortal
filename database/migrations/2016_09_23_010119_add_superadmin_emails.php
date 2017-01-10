<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddSuperadminEmails extends Migration
{
    public function up()
    {
        Schema::table('members', function (Blueprint $table) {
            $table->boolean('superAdmin')->after('admin');
            $table->date('setupEmailSent')->after('password');
        });
    }

    public function down()
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn('superAdmin');
            $table->dropColumn('setupEmailSent');
        });
    }
}
