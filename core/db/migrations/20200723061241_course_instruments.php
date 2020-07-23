<?php

use Illuminate\Database\Schema\Blueprint;
use Vesp\Services\Migration;

class CourseInstruments extends Migration
{
    public function up(): void
    {
        $this->schema->table('courses', static function (Blueprint $table) {
            $table->text('instruments')->nullable()->after('description');
        });
    }


    public function down(): void
    {
        $this->schema->table('courses', static function (Blueprint $table) {
            $table->dropColumn('instruments');
        });
    }
}
