<?php

namespace App\Processors\Web\Course;

use App\Model\Course;
use App\Model\Lesson;
use App\Model\UserLike;

class Lessons extends \App\Processor
{
    protected $scope = 'lessons';


    protected function get()
    {
        /** @var Course $course */
        if (!$course = Course::query()->find((int)$this->getProperty('course_id'))) {
            return $this->failure('Не могу загрузить курс');
        } elseif (!$course->wasBought($this->container->user->id)) {
            return $this->failure('Вы забыли оплатить этот курс');
        }

        if ($id = (int)$this->getProperty('id')) {
            /** @var Lesson $lesson */
            if (!$lesson = Lesson::query()->find($id)) {
                return $this->failure('Не могу загрузить урок');
            }

            /** @var UserLike $like */
            $like = $this->container->user->likes()->where(['lesson_id' => $id])->select('value')->first();

            $data = [
                'id' => $lesson->id,
                'title' => $lesson->title,
                'description' => $lesson->description,
                'products' => $lesson->products,
                'video' => $lesson->video
                    ? $lesson->video->preview
                    : null,
                'bonus' => $lesson->bonus
                    ? [
                        'title' => $lesson->bonus->title,
                        'description' => $lesson->bonus->description,
                        'preview' => $lesson->bonus->preview,
                    ]
                    : null,
                'file' => $lesson->file
                    ? $lesson->file->getUrl()
                    : null,
                'author' => $lesson->author
                    ? [
                        'fullname' => $lesson->author->fullname,
                        'company' => $lesson->author->company,
                        'description' => $lesson->author->description,
                        'photo' => $lesson->author->photo
                            ? $lesson->author->photo->getUrl()
                            : null,
                    ]
                    : null,
                'likes_count' => $lesson->likes_count,
                'dislikes_count' => $lesson->dislikes_count,
                'like' => $like
                    ? $like->value
                    : null,
                'next' => [],
                'comments' => [],
            ];

            $next_lessons = $course->lessons()
                ->where(['section' => $lesson->section])
                ->where('rank', '>', $lesson->rank)
                ->get();
            /** @var Lesson $next */
            foreach ($next_lessons as $next) {
                $data['next'][] = [
                    'id' => $next->id,
                    'title' => $next->title,
                    'description' => $next->description,
                    'preview' => $next->video
                        ? $next->video->preview
                        : null,
                ];
            }

            return $this->success($data);
        }

        $lessons = [];
        /** @var Lesson $lesson */
        foreach ($course->lessons()->where(['active' => true])->get() as $lesson) {
            $lessons[] = [
                'id' => $lesson->id,
                'title' => $lesson->title,
                'description' => $lesson->description,
                'section' => $lesson->section,
                'views_count' => $lesson->views_count,
                'likes_count' => $lesson->likes_count,
                'preview' => $lesson->video
                    ? $lesson->video->preview
                    : [],
            ];
        }

        return $this->success([
            'rows' => $lessons,
            'total' => count($lessons),
        ]);
    }
}