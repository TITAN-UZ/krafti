<?php

use App\Service\Migration;
use Illuminate\Database\Schema\Blueprint;

class Subscriber extends Migration
{
    public function up()
    {
        $this->schema->create('subscribers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email')->unique();
            $table->integer('user_id')->unsigned()->nullable();

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onUpdate('restrict')
                ->onDelete('set null');
        });
    }


    public function down()
    {
        $this->schema->drop('subscribers');
    }
}
