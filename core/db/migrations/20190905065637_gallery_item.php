<?php

use Illuminate\Database\Schema\Blueprint;
use Vesp\Services\Migration;

class GalleryItem extends Migration
{
    public function up()
    {
        $this->schema->create('gallery_items', function (Blueprint $table) {
            $table->integer('file_id')->unsigned();
            $table->integer('object_id')->unsigned();
            $table->string('object_name', 100);
            $table->integer('rank')->nullable();
            $table->string('hash', 40)->index();
            $table->boolean('active')->default(true);

            $table->primary('file_id');
            $table->index(['object_id', 'object_name', 'active']);
            $table->index('rank');

            $table->foreign('file_id')
                ->references('id')->on('files')
                ->onUpdate('restrict')
                ->onDelete('cascade');
        });
    }


    public function down()
    {
        $this->schema->table('gallery_items', function (Blueprint $table) {
            $table->dropForeign('gallery_items_file_id_foreign');
        });
        $this->schema->drop('gallery_items');
    }
}
