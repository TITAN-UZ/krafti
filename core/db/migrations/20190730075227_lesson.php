<?php

use Illuminate\Database\Schema\Blueprint;
use Vesp\Services\Migration;

class Lesson extends Migration
{
    public function up()
    {
        $this->schema->create('lessons', function (Blueprint $table) {
            $table->increments('id');

            $table->string('title');
            $table->text('description')->nullable();

            $table->integer('course_id')->unsigned();
            $table->integer('cover_id')->unsigned()->nullable();
            $table->integer('video_id')->unsigned()->nullable();
            $table->integer('bonus_id')->unsigned()->nullable();
            $table->integer('file_id')->unsigned()->nullable(); // Archive with files for reading
            $table->integer('author_id')->unsigned()->nullable();

            $table->json('products')->nullable();

            $table->integer('views_count')->unsigned()->default(0);
            $table->integer('likes_count')->unsigned()->default(0);
            $table->integer('dislikes_count')->unsigned()->default(0);
            $table->smallInteger('rank')->default(0)->index();
            $table->smallInteger('section')->index();
            $table->boolean('active')->default(0)->index();
            $table->boolean('extra')->default(0)->index();
            $table->boolean('free')->default(0)->index();
            $table->timestamps();

            $table->foreign('course_id')
                ->references('id')->on('courses')
                ->onUpdate('restrict')
                ->onDelete('cascade');
            $table->foreign('cover_id')
                ->references('id')->on('files')
                ->onUpdate('restrict')
                ->onDelete('set null');
            $table->foreign('video_id')
                ->references('id')->on('videos')
                ->onUpdate('restrict')
                ->onDelete('set null');
            $table->foreign('bonus_id')
                ->references('id')->on('videos')
                ->onUpdate('restrict')
                ->onDelete('set null');
            $table->foreign('file_id')
                ->references('id')->on('files')
                ->onUpdate('restrict')
                ->onDelete('set null');
            $table->foreign('author_id')
                ->references('id')->on('users')
                ->onUpdate('restrict')
                ->onDelete('set null');
        });
    }


    public function down()
    {
        $this->schema->table('lessons', function (Blueprint $table) {
            $table->dropForeign('lessons_course_id_foreign');
            $table->dropForeign('lessons_cover_id_foreign');
            $table->dropForeign('lessons_video_id_foreign');
            $table->dropForeign('lessons_bonus_id_foreign');
            $table->dropForeign('lessons_file_id_foreign');
            $table->dropForeign('lessons_author_id_foreign');
        });
        $this->schema->drop('lessons');
    }
}
