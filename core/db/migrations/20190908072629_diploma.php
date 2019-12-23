<?php

use \App\Service\Migration;
use Illuminate\Database\Schema\Blueprint;

class Diploma extends Migration
{
    public function up()
    {
        $this->schema->create('diplomas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('course_id')->unsigned();
            $table->integer('child_id')->unsigned()->nullable();
            $table->integer('file_id')->unsigned()->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'course_id', 'child_id']);

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onUpdate('restrict')
                ->onDelete('cascade');
            $table->foreign('course_id')
                ->references('id')->on('users')
                ->onUpdate('restrict')
                ->onDelete('cascade');
            $table->foreign('child_id')
                ->references('id')->on('user_children')
                ->onUpdate('restrict')
                ->onDelete('cascade');
            $table->foreign('file_id')
                ->references('id')->on('files')
                ->onUpdate('restrict')
                ->onDelete('set null');
        });
    }


    public function down()
    {
        $this->schema->table('diplomas', function (Blueprint $table) {
            $table->dropForeign('diplomas_user_id_foreign');
            $table->dropForeign('diplomas_child_id_foreign');
            $table->dropForeign('diplomas_course_id_foreign');
            $table->dropForeign('diplomas_file_id_foreign');
        });
        $this->schema->drop('diplomas');
    }
}
