<?php

namespace App\Processors\Web\Course;

use App\Model\Course;
use App\Model\Homework;
use App\Model\Lesson;
use App\Model\UserLike;
use App\Model\UserProgress;

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

            /** @var UserProgress $progress */
            $progress = $this->container->user->getProgress($course);
            if ($progress->section == $lesson->section && $lesson->rank > $progress->rank) {
                $progress = $this->container->user->makeProgress($course, $lesson->section, $lesson->rank);
            }

            // Bonus
            if (!$lesson->section && $progress->section) {
                return $this->failure('Вы еще не открыли бонусный урок!');
            }

            /** @var UserLike $like */
            $like = $this->container->user->likes()->where(['lesson_id' => $id])->select('value')->first();

            $data = [
                'id' => $lesson->id,
                'title' => $lesson->title,
                'description' => $lesson->description,
                'products' => $lesson->products,
                'video' => $lesson->video
                    ? [
                        'vimeo' => $lesson->video->remote_key,
                        'title' => $lesson->bonus->title,
                        'description' => $lesson->bonus->description,
                        'preview' => $lesson->video->preview,
                    ]
                    : null,
                'bonus' => $lesson->bonus
                    ? [
                        'vimeo' => $lesson->video->remote_key,
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
                'progress' => [
                    'section' => $progress->section,
                    'rank' => $progress->rank,
                ],
                'next' => [],
                'comments' => [],
                'homework' => [],
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

            /** @var Homework $homework */
            if ($homework = $lesson->homeworks()->where(['user_id' => $this->container->user->id])->first()) {
                $data['homework'] = [
                    'id' => $homework->id,
                    'file' => $homework->file
                        ? $homework->file->getUrl()
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