<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateHackathonsTable extends Migration
{
    public function up()
    {
        Schema::create('hackathons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('website');
            $table->string('date');
            $table->string('location');
            $table->string('apply');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('hackathons');
    }
}
