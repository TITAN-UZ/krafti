<?php

use App\Service\Migration;
use Illuminate\Database\Schema\Blueprint;

class UserFavorite extends Migration
{
    public function up()
    {
        $this->schema->create('user_favorites', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('course_id')->unsigned();
            $table->timestamps();

            $table->primary(['course_id', 'user_id']);
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
        $this->schema->table('user_favorites', function (Blueprint $table) {
            $table->dropForeign('user_favorites_course_id_foreign');
            $table->dropForeign('user_favorites_user_id_foreign');
        });
        $this->schema->drop('user_favorites');
    }
}
