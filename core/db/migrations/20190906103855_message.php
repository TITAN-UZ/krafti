<?php

use \App\Service\Migration;
use Illuminate\Database\Schema\Blueprint;

class Message extends Migration
{
    public function up()
    {
        $this->schema->create('messages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('sender_id')->unsigned()->nullable();
            $table->text('message');

            $table->string('type')->nullable(); // reply, dob, warning, transaction, diploma
            $table->json('data')->nullable();

            $table->boolean('read')->default(false);
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onUpdate('restrict')
                ->onDelete('cascade');
            $table->foreign('sender_id')
                ->references('id')->on('users')
                ->onUpdate('restrict')
                ->onDelete('set null');
        });
    }


    public function down()
    {
        $this->schema->table('messages', function (Blueprint $table) {
            $table->dropForeign('messages_user_id_foreign');
            $table->dropForeign('messages_sender_id_foreign');
        });
        $this->schema->drop('messages');
    }
}
