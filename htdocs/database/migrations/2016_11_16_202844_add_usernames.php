<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Member;
use Illuminate\Support\Facades\DB as DB;

class AddUsernames extends Migration {
    public function up() {
        Schema::table('members', function (Blueprint $table) {
			$table->string('username')->after('name');
        });
        
        $members = Member::where('username',"")->get();
        
        foreach ($members as $member) {
	        $member->username = strtolower(str_replace(" ", "", $member->name));
	        if (app('app\Http\Controllers\MemberController')->usernameAvailable($member->username) == false) {
		        $member->username = $member->username."1";
	        }
	        $member->save();
        }
            
        Schema::table('members', function (Blueprint $table) {
			$table->unique('username');
        });
    }
    
    public function down() {
        Schema::table('members', function (Blueprint $table) {
			$table->dropColumn('username');
        });
    }
}
