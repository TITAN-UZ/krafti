<?php

use Illuminate\Database\Schema\Blueprint;
use Vesp\Services\Migration;

class Video extends Migration
{
    public function up()
    {
        $this->schema->create('videos', function (Blueprint $table) {
            $table->increments('id');

            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('remote_key')->unsigned()->unique()->nullable();
            $table->json('preview')->nullable();
            $table->smallInteger('width')->unsigned()->default(0);
            $table->smallInteger('height')->unsigned()->default(0);
            $table->smallInteger('duration')->unsigned()->default(0);
            $table->integer('views_count')->unsigned()->default(0);
            //$table->json('privacy')->unsigned()->default(0);
            $table->timestamps();
        });
    }


    public function down()
    {
        $this->schema->drop('videos');
    }
}
