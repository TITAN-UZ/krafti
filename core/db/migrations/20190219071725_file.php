<?php

use App\Service\Migration;
use Illuminate\Database\Schema\Blueprint;

class File extends Migration
{

    public function up()
    {
        $this->schema->create('files', function (Blueprint $table) {
            $table->increments('id');
            $table->string('file');
            $table->string('path');
            $table->string('title')->nullable();
            $table->json('preview')->nullable();
            $table->string('type')->nullable();
            $table->smallInteger('width')->unsigned()->nullable();
            $table->smallInteger('height')->unsigned()->nullable();
            $table->text('description')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }


    public function down()
    {
        $this->schema->drop('files');
    }

}
