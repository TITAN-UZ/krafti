<?php

use \App\Service\Migration;
use Illuminate\Database\Schema\Blueprint;

class UserFavorite extends Migration
{
    public function up()
    {
        $this->schema->create('user_favorites', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('course_id')->unsigned();

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onUpdate('restrict')
                ->onDelete('cascade');
            $table->foreign('course_id')
                ->references('id')->on('courses')
                ->onUpdate('restrict')
                ->onDelete('cascade');
        });

    }


    public function down()
    {
        $this->schema->drop('user_favorites');
    }
}
