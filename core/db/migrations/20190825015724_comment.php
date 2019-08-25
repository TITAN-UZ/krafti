<?php

use \App\Service\Migration;
use Illuminate\Database\Schema\Blueprint;

class Comment extends Migration
{

    public function up()
    {
        $this->schema->create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('lesson_id')->unsigned();
            $table->integer('parent_id')->unsigned()->default(0);
            $table->text('text');
            $table->boolean('deleted')->default(0);
            $table->boolean('review')->default(0);
            $table->timestamps();

            $table->index(['deleted', 'review']);
            $table->index(['deleted', 'lesson_id']);

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onUpdate('restrict')
                ->onDelete('cascade');
            $table->foreign('lesson_id')
                ->references('id')->on('lessons')
                ->onUpdate('restrict')
                ->onDelete('cascade');
            /*$table->foreign('parent_id')
                ->references('id')->on('comments')
                ->onUpdate('restrict')
                ->onDelete('set_null');*/
        });
    }

    public function down()
    {
        $this->schema->table('comments', function (Blueprint $table) {
            $table->dropForeign('comments_user_id_foreign');
            $table->dropForeign('comments_lesson_id_foreign');
            //$table->dropForeign('comments_parent_id_foreign');
        });
        $this->schema->drop('comments');
    }
}
