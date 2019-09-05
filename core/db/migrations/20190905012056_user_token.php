<?php

use \App\Service\Migration;
use Illuminate\Database\Schema\Blueprint;

class UserToken extends Migration
{
    public function up()
    {
        $this->schema->create('user_tokens', function (Blueprint $table) {
            $table->string('token');
            $table->integer('user_id')->unsigned();
            $table->boolean('active')->default(1);
            $table->string('ip')->nullable();
            $table->timestamp('valid_till')->index();
            $table->timestamps();

            $table->primary(['user_id', 'created_at']);
            $table->index(['token', 'user_id', 'active']);

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onUpdate('restrict')
                ->onDelete('cascade');
        });
    }


    public function down()
    {
        $this->schema->table('user_tokens', function (Blueprint $table) {
            $table->dropForeign('user_tokens_user_id_foreign');
        });
        $this->schema->drop('user_tokens');
    }
}
