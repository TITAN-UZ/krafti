<?php

use App\Service\Migration;
use Illuminate\Database\Schema\Blueprint;

class Course extends Migration
{
    public function up()
    {
        $this->schema->create('courses', function (Blueprint $table) {
            $table->increments('id');

            $table->string('title');
            $table->string('tagline')->nullable();
            $table->text('description')->nullable();
            $table->string('category');
            $table->json('price')->nullable();
            $table->json('properties')->nullable();
            $table->string('age'); // 4-8

            $table->integer('cover_id')->unsigned()->nullable();
            $table->integer('video_id')->unsigned()->nullable();
            $table->integer('diploma_id')->unsigned()->nullable();

            $table->integer('views_count')->unsigned()->nullable()->default(0);
            $table->integer('reviews_count')->unsigned()->nullable()->default(0);
            $table->integer('lessons_count')->unsigned()->nullable()->default(0);
            $table->integer('videos_count')->unsigned()->nullable()->default(0);
            $table->integer('likes_sum')->nullable()->default(0);

            $table->boolean('active')->default(0);
            $table->timestamps();

            $table->foreign('cover_id')
                ->references('id')->on('files')
                ->onUpdate('restrict')
                ->onDelete('set null');
            $table->foreign('video_id')
                ->references('id')->on('videos')
                ->onUpdate('restrict')
                ->onDelete('set null');
            $table->foreign('diploma_id')
                ->references('id')->on('files')
                ->onUpdate('restrict')
                ->onDelete('set null');
        });
    }


    public function down()
    {
        $this->schema->table('courses', function (Blueprint $table) {
            $table->dropForeign('courses_cover_id_foreign');
            $table->dropForeign('courses_video_id_foreign');
            $table->dropForeign('courses_diploma_id_foreign');
        });
        $this->schema->drop('courses');
    }
}
