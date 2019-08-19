<?php

namespace App\Processors\Web\Course;

use App\Model\Course;

class Bonus extends \App\Processor
{

    protected function get()
    {
        /** @var Course $course */
        if (!$course = Course::query()->find((int)$this->getProperty('course_id'))) {
            return $this->failure('Не могу загрузить курс');
        }
    }
}