<?php

use App\Model\Course;
use App\Model\Template;
use Illuminate\Database\Schema\Blueprint;
use Vesp\Services\Migration;

class CourseTemplate extends Migration
{
    public function up()
    {
        $this->schema->create('templates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->unique();
            $table->boolean('course_palette')->default(false);
            $table->boolean('lesson_bonus')->default(false);
            $table->boolean('course_steps')->default(false);
            $table->boolean('course_homeworks')->default(false);
            $table->boolean('course_bonus')->default(false);
        });

        (new Template([
            'title' => 'Все функции',
            'course_palette' => true,
            'lesson_bonus' => true,
            'course_steps' => true,
            'course_homeworks' => true,
            'course_bonus' => true,
        ]))->save();
        (new Template(['title' => 'Минимальный набор']))->save();

        $this->schema->table('courses', function (Blueprint $table) {
            $table->integer('template_id')->unsigned()->nullable()->after('age');
        });

        /** @var Course $course */
        foreach (Course::all() as $course) {
            $course->template_id = $course->id === 1
                ? 1
                : 2;
            $course->save();
        }

        $this->schema->table('courses', function (Blueprint $table) {
            $table->foreign('template_id')
                ->references('id')->on('templates')
                ->onUpdate('restrict')
                ->onDelete('set null');
        });
    }


    public function down()
    {
        $this->schema->table('courses', function (Blueprint $table) {
            $table->dropForeign('courses_template_id_foreign');
            $table->dropColumn('template_id');
        });

        $this->schema->drop('templates');
    }
}
