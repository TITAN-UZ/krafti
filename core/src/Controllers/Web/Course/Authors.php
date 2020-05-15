<?php

namespace App\Controllers\Web\Course;

use App\Model\Course;
use App\Model\Lesson;
use App\Model\User;
use Psr\Http\Message\ResponseInterface;
use Vesp\Controllers\Controller;

class Authors extends Controller
{
    /** @var User $user */
    protected $user;

    /**
     * @return ResponseInterface;
     */
    public function get()
    {
        /** @var Course $course */
        if (!$course = Course::query()->find((int)$this->getProperty('course_id'))) {
            return $this->failure('Не могу загрузить курс');
        }

        $authors = [];
        /** @var Lesson $lesson */
        foreach ($course->lessons()->where(['active' => true])->get() as $lesson) {
            if ($author = $lesson->author) {
                $authors[$author->id] = [
                    'id' => $author->id,
                    'fullname' => $author->fullname,
                    'company' => $author->company,
                    'description' => $author->description,
                    'photo' => $author->photo_id
                        ? [
                            'id' => $author->photo->id,
                            'updated_at' => $author->photo->updated_at,
                        ]
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
