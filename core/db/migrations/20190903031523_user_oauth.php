<?php

use \App\Service\Migration;
use Illuminate\Database\Schema\Blueprint;

class UserOauth extends Migration
{
    public function up()
    {
        $this->schema->create('user_oauths', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->string('provider', 100);
            $table->integer('identifier')->unsigned();
            $table->string('email')->nullable();
            $table->string('profileURL')->nullable();
            $table->string('photoURL')->nullable();
            $table->string('displayName')->nullable();
            $table->string('firstName')->nullable();
            $table->string('lastName')->nullable();
            $table->string('gender')->nullable();
            $table->integer('phone')->nullable();
            $table->smallInteger('age')->nullable();
            $table->smallInteger('birthDay')->nullable();
            $table->smallInteger('birthMonth')->nullable();
            $table->smallInteger('birthYear')->nullable();
            $table->timestamps();

            $table->primary(['user_id', 'provider']);
            $table->index(['identifier', 'provider']);

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onUpdate('restrict')
                ->onDelete('cascade');
        });
    }


    public function down()
    {
        $this->schema->table('user_oauths', function (Blueprint $table) {
            $table->dropForeign('user_oauths_user_id_foreign');
        });
        $this->schema->drop('user_oauths');
    }
}
