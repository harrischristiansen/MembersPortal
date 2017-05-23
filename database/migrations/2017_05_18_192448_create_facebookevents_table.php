<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacebookeventsTable extends Migration
{
    public function up()
    {
        Schema::table('events', function ($table) {
           $table->boolean('fromFacebook')->default(false);
        });
    }

    public function down()
    {
        Schema::drop('event_member');
        Schema::drop('events');
    }
}
