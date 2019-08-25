<?php

use \App\Service\Migration;
use Illuminate\Database\Schema\Blueprint;

class UserProgress extends Migration
{
    public function up()
    {
        $this->schema->create('user_progresses', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('course_id')->unsigned();
            $table->integer('section')->default(0);
            $table->integer('rank')->default(0);
            $table->timestamps();

            $table->primary(['user_id', 'course_id']);

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onUpdate('restrict')
                ->onDelete('cascade');
            $table->foreign('course_id')
                ->references('id')->on('users')
                ->onUpdate('restrict')
                ->onDelete('cascade');
        });
    }


    public function down()
    {
        $this->schema->table('user_progresses', function (Blueprint $table) {
            $table->dropForeign('user_progresses_user_id_foreign');
            $table->dropForeign('user_progresses_course_id_foreign');

        });
        $this->schema->drop('user_progresses');
    }
}
