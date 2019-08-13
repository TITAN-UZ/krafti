<?php

use App\Service\Migration;
use Illuminate\Database\Schema\Blueprint;

class Log extends Migration
{
    public function up()
    {
        $this->schema->create('logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('channel');
            $table->integer('level');
            $table->string('message')->nullable();
            $table->timestamp('time')->index();
            $table->integer('object_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('action')->nullable();
            $table->json('data')->nullable();
        });
    }


    public function down()
    {
        $this->schema->drop('logs');
    }
}
