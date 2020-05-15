<?php

use Illuminate\Database\Schema\Blueprint;
use Vesp\Services\Migration;

class Subscriber extends Migration
{
    public function up()
    {
        $this->schema->create('subscribers', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->integer('user_id')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onUpdate('restrict')
                ->onDelete('set null');
        });
    }


    public function down()
    {
        $this->schema->table('subscribers', function (Blueprint $table) {
            $table->dropForeign('subscribers_user_id_foreign');
        });
        $this->schema->drop('subscribers');
    }
}
