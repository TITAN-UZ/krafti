<?php

use App\Service\Migration;
use Illuminate\Database\Schema\Blueprint;

class User extends Migration
{

    public function up()
    {
        $this->schema->create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email', 100)->nullable()->index();
            $table->string('password')->nullable(false);
            $table->string('tmp_password')->nullable();
            $table->string('fullname', 100)->nullable();
            $table->date('dob')->nullable();
            $table->string('phone', 14)->nullable();
            $table->string('instagram', 100)->nullable();
            $table->string('company')->nullable();
            $table->text('description')->nullable();
            $table->boolean('active')->default(true)->index();
            $table->boolean('confirmed')->default(false)->index();
            $table->integer('photo_id')->unsigned()->nullable();
            $table->integer('background_id')->unsigned()->nullable();
            $table->integer('role_id')->unsigned()->nullable();
            $table->integer('referrer_id')->unsigned()->nullable();
            $table->integer('account')->unsigned()->default(0);
            $table->string('promo', 100)->nullable()->unique();
            $table->json('children')->nullable();
            $table->timestamp('logged_at')->nullable();
            $table->timestamp('reset_at')->nullable();
            $table->timestamps();

            $table->foreign('photo_id')
                ->references('id')->on('files')
                ->onUpdate('restrict')
                ->onDelete('cascade');
            $table->foreign('background_id')
                ->references('id')->on('files')
                ->onUpdate('restrict')
                ->onDelete('cascade');
            $table->foreign('role_id')
                ->references('id')->on('user_roles')
                ->onUpdate('restrict')
                ->onDelete('set null');
            $table->foreign('referrer_id')
                ->references('id')->on('users')
                ->onUpdate('restrict')
                ->onDelete('set null');
        });
    }


    public function down()
    {
        $this->schema->table('users', function (Blueprint $table) {
            $table->dropForeign('users_role_id_foreign');
            $table->dropForeign('users_photo_id_foreign');
            $table->dropForeign('users_background_id_foreign');
            $table->dropForeign('users_referrer_id_foreign');
        });
        $this->schema->drop('users');
    }

}
