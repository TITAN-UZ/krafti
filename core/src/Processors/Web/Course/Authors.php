<?php

namespace App\Processors\Web\Course;

use App\Model\Course;
use App\Model\Lesson;
use App\Model\User;
use App\Processor;
use Slim\Http\Response;

class Authors extends Processor
{
    protected $class = User::class;
    protected $scope = 'profile';

    /**
     * @return Response
     */
    public function get()
    {
        /** @var Course $course */
        if (!$course = Course::query()->find((int)$this->getProperty('course_id'))) {
            return $this->failure('Не могу загрузить курс');
        }
        if (!$course->wasBought($this->container->user)) {
            return $this->failure(!$course->active ? 'Не могу загрузить курс' : 'Вы забыли оплатить этот курс');
        }

        $authors = [];
        /** @var Lesson $lesson */
        foreach ($course->lessons()->where(['active' => true])->get() as $lesson) {
            /** @var User $author */
            if ($author = $lesson->author) {
                $authors[$author->id] = [
                    'id' => $author->id,
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
            'rows' => array_values($authors),
            'total' => count($authors),
        ]);
    }
}