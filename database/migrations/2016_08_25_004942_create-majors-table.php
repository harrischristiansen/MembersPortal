<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMajorsTable extends Migration
{
    public function up()
    {
        Schema::create('majors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::table('members', function ($table) {
            $table->foreign('major_id')->references('id')->on('majors');
        });
    }

    public function down()
    {
        Schema::drop('majors');
    }
}
