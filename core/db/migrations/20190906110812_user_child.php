<?php

use \App\Service\Migration;
use Illuminate\Database\Schema\Blueprint;

class UserChild extends Migration
{
    public function up()
    {
        $this->schema->create('user_children', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('name');
            $table->date('dob');
            $table->boolean('gender')->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onUpdate('restrict')
                ->onDelete('cascade');
        });
    }


    public function down()
    {
        $this->schema->table('user_children', function (Blueprint $table) {
            $table->dropForeign('user_children_user_id_foreign');
        });
        $this->schema->drop('user_children');
    }
}
