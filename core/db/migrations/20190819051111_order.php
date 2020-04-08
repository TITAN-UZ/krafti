<?php

use \App\Service\Migration;
use Illuminate\Database\Schema\Blueprint;

class Order extends Migration
{
    public function up()
    {
        $this->schema->create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('course_id')->unsigned()->nullable();
            $table->string('service');
            $table->integer('cost')->unsigned();
            $table->integer('discount')->unsigned()->nullable()->default(0);
            $table->smallInteger('status')->unsigned();
            $table->smallInteger('period')->unsigned();
            $table->boolean('manual')->nullable()->default(false);
            $table->dateTime('paid_at')->nullable();
            $table->dateTime('paid_till')->nullable();
            $table->timestamps();

            $table->index(['course_id', 'user_id', 'status']);
            $table->index('created_at');

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onUpdate('restrict')
                ->onDelete('set null');
            $table->foreign('course_id')
                ->references('id')->on('courses')
                ->onUpdate('restrict')
                ->onDelete('set null');
        });
    }


    public function down()
    {
        $this->schema->table('orders', function (Blueprint $table) {
            $table->dropForeign('orders_user_id_foreign');
            $table->dropForeign('orders_course_id_foreign');
        });
        $this->schema->drop('orders');
    }
}
