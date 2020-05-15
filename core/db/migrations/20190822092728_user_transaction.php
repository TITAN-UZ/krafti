<?php

use Illuminate\Database\Schema\Blueprint;
use Vesp\Services\Migration;

class UserTransaction extends Migration
{

    public function up()
    {
        $this->schema->create('user_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('referral_id')->unsigned()->nullable();
            $table->integer('course_id')->unsigned()->nullable();
            $table->integer('lesson_id')->unsigned()->nullable();
            $table->integer('amount');
            $table->integer('account')->default(0);
            $table->string('action', 100);
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onUpdate('restrict')
                ->onDelete('cascade');
            $table->foreign('course_id')
                ->references('id')->on('courses')
                ->onUpdate('restrict')
                ->onDelete('set null');
            $table->foreign('referral_id')
                ->references('id')->on('users')
                ->onUpdate('restrict')
                ->onDelete('set null');
            $table->foreign('lesson_id')
                ->references('id')->on('lessons')
                ->onUpdate('restrict')
                ->onDelete('set null');
        });
    }


    public function down()
    {
        $this->schema->table('user_transactions', function (Blueprint $table) {
            $table->dropForeign('user_transactions_user_id_foreign');
            $table->dropForeign('user_transactions_course_id_foreign');
            $table->dropForeign('user_transactions_referral_id_foreign');
            $table->dropForeign('user_transactions_lesson_id_foreign');
        });
        $this->schema->drop('user_transactions');
    }
}
