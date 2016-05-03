<?php
	
/*
	@ Harris Christiansen (Harris@HarrisChristiansen.com)
	2016-05-02
	Project: Members Tracking Portal
*/

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocationsTable extends Migration {
    public function up() {
        Schema::create('locations', function (Blueprint $table) {
            $table->increments('id');
            $table->text('name');
            $table->text('city');
            $table->float('loc_lat');
            $table->float('loc_lng');
            $table->timestamps();
        });
        Schema::create('location-member', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('location_id')->unsigned();
			$table->foreign('location_id')->references('id')->on('locations');
			$table->integer('member_id')->unsigned();
			$table->foreign('member_id')->references('id')->on('members');
			$table->date('date_start');
			$table->date('date_end');
            $table->timestamps();
        });
    }
    public function down() {
        Schema::drop('location-member');
        Schema::drop('locations');
    }
}
