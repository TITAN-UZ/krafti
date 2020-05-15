<?php

use Illuminate\Database\Schema\Blueprint;
use Vesp\Services\Migration;

class PromoCourses extends Migration
{
    public function up()
    {
        $this->schema->table('promos', function (Blueprint $table) {
            $table->json('courses')->after('title')->nullable();
        });
    }


    public function down()
    {
        $this->schema->table('promos', function (Blueprint $table) {
            $table->dropColumn('courses');
        });
    }
}
