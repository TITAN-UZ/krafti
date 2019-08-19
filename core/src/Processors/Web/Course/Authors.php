<?php

namespace App\Processors\Web\Course;

use App\Model\Course;
use App\Model\Lesson;
use App\Model\User;

class Authors extends \App\Processor
{
    protected $class = '\App\Model\User';


    public function get()
    {
        /** @var Course $course */
        if (!$course = Course::query()->find((int)$this->getProperty('course_id'))) {
            return $this->failure('Не могу загрузить курс');
        }

        $authors = [];
        /** @var Lesson $lesson */
        foreach ($course->lessons()->where(['active' => true])->get() as $lesson) {
            /** @var User $author */
            if ($author = $lesson->author) {
                $authors[] = [
                    'fullname' => $author->fullname,
                    'company' => $author->company,
                    'description' => $author->description,
                    'photo' => $author->photo
                        ? $author->photo->getUrl()
                        : null,
                ];
            }
        }

        return $this->success([
            'rows' => $authors,
            'total' => count($authors),
        ]);
    }
}