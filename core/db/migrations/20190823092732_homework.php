<?php

use \App\Service\Migration;
use Illuminate\Database\Schema\Blueprint;

class Homework extends Migration
{
    public function up()
    {
        $this->schema->create('homeworks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('course_id')->unsigned();
            $table->integer('lesson_id')->unsigned()->nullable();
            $table->integer('file_id')->unsigned();
            $table->integer('section')->unsigned();
            $table->boolean('approved')->nullable();
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onUpdate('restrict')
                ->onDelete('cascade');
            $table->foreign('course_id')
                ->references('id')->on('courses')
                ->onUpdate('restrict')
                ->onDelete('cascade');
            $table->foreign('lesson_id')
                ->references('id')->on('lessons')
                ->onUpdate('restrict')
                ->onDelete('set null');
            $table->foreign('file_id')
                ->references('id')->on('files')
                ->onUpdate('restrict')
                ->onDelete('cascade');
        });
    }


    public function down()
    {
        $this->schema->table('homeworks', function (Blueprint $table) {
            $table->dropForeign('homeworks_user_id_foreign');
            $table->dropForeign('homeworks_course_id_foreign');
            $table->dropForeign('homeworks_lesson_id_foreign');
            $table->dropForeign('homeworks_file_id_foreign');

        });
        $this->schema->drop('homeworks');
    }
}
