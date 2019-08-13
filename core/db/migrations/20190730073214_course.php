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
            $table->integer('price');
            $table->string('category');
            $table->json('properties');
            $table->string('age'); // 4-8

            $table->integer('cover_id')->unsigned()->nullable();
            $table->integer('video_id')->unsigned()->nullable();
            $table->integer('bonus_id')->unsigned()->nullable();

            $table->integer('views_count')->unsigned()->nullable()->default(0);
            $table->integer('reviews_count')->unsigned()->nullable()->default(0);
            $table->integer('likes_count')->unsigned()->nullable()->default(0);
            $table->integer('lessons_count')->unsigned()->nullable()->default(0);

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
            $table->foreign('bonus_id')
                ->references('id')->on('videos')
                ->onUpdate('restrict')
                ->onDelete('set null');
        });
    }


    public function down()
    {
        $this->schema->table('courses', function (Blueprint $table) {
            $table->dropForeign('courses_cover_id_foreign');
            $table->dropForeign('courses_video_id_foreign');
            $table->dropForeign('courses_bonus_id_foreign');
        });
        $this->schema->drop('courses');
    }
}
