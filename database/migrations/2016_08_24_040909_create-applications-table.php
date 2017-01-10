<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateApplicationsTable extends Migration
{
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('member_id')->unsigned()->index();
            $table->foreign('member_id')->references('id')->on('members');
            $table->integer('event_id')->unsigned()->index();
            $table->foreign('event_id')->references('id')->on('events');
            $table->enum('tshirt', ['Small', 'Medium', 'Large', 'XL']);
            $table->text('interests')->nullable();
            $table->text('dietary')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('applications');
    }
}
