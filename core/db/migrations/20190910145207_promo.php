<?php

use \App\Service\Migration;
use Illuminate\Database\Schema\Blueprint;

class Promo extends Migration
{
    public function up()
    {
        $this->schema->create('promos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->unique();
            $table->integer('discount')->unsigned();
            $table->boolean('percent')->default(0);
            $table->string('title')->nullable();
            //$table->text('description')->nullable();
            $table->dateTime('date_start')->nullable();
            $table->dateTime('date_end')->nullable();
            $table->integer('limit')->unsigned()->nullable();
            $table->integer('used')->unsigned()->nullable()->default(0);
            $table->timestamps();
        });

        $this->schema->table('orders', function (Blueprint $table) {
            $table->integer('promo_id')->unsigned()->nullable()
                ->after('course_id');

            $table->foreign('promo_id')
                ->references('id')->on('promos')
                ->onUpdate('restrict')
                ->onDelete('cascade');
        });
    }


    public function down()
    {
        $this->schema->table('orders', function (Blueprint $table) {
            $table->dropForeign('orders_promo_id_foreign');
            $table->dropColumn('promo_id');
        });
        $this->schema->drop('promos');
    }
}
