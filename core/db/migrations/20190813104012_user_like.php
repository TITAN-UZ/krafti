<?php

use App\Service\Migration;
use Illuminate\Database\Schema\Blueprint;

class UserLike extends Migration
{
    public function up()
    {
        $this->schema->create('user_likes', function (Blueprint $table) {
            $table->integer('lesson_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->smallInteger('value');
            $table->timestamps();

            $table->primary(['lesson_id', 'user_id']);
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onUpdate('restrict')
                ->onDelete('cascade');
            $table->foreign('lesson_id')
                ->references('id')->on('lessons')
                ->onUpdate('restrict')
                ->onDelete('cascade');
        });
    }


    public function down()
    {
        $this->schema->table('user_likes', function (Blueprint $table) {
            $table->dropForeign('user_likes_lesson_id_foreign');
            $table->dropForeign('user_likes_user_id_foreign');
        });
        $this->schema->drop('user_likes');
    }
}
