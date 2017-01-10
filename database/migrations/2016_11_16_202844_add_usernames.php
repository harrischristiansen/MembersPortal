<?php

use App\Models\Member;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddUsernames extends Migration
{
    public function up()
    {
        Schema::table('members', function (Blueprint $table) {
            $table->string('username')->after('name');
        });

        $members = Member::where('username', '')->get();

        foreach ($members as $member) {
            $member->username = app('app\Http\Controllers\MemberController')->generateUsername($member);
            $member->save();
        }

        Schema::table('members', function (Blueprint $table) {
            $table->unique('username');
        });
    }

    public function down()
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn('username');
        });
    }
}
