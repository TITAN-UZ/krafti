<?php

namespace App\Controllers\User;

use App\Model\Course;
use App\Model\File;
use App\Model\Homework;
use App\Model\User;
use Illuminate\Database\Eloquent\Builder;
use Psr\Http\Message\ResponseInterface;
use Vesp\Controllers\ModelGetController;

class Homeworks extends ModelGetController
{
    protected $scope = 'profile';
    protected $model = Homework::class;

    /** @var User $user */
    protected $user;

    /**
     * @param Builder $c
     *
     * @return Builder
     */
    public function beforeGet(Builder $c): Builder
    {
        $c->where('user_id', $this->user->id);
        $c->with('file:id,updated_at');

        return $c;
    }

    /**
     * @param Builder $c
     *
     * @return Builder
     */
    public function beforeCount(Builder $c): Builder
    {
        $c->where('user_id', $this->user->id);
        $c->select('id', 'course_id', 'lesson_id', 'file_id', 'section', 'comment');

        if ($course_id = (int)$this->getProperty('course_id')) {
            $c->where('course_id', $course_id);
        } else {
            $c->with('course:id,title');
            $c->with('lesson:id,title,section,rank');
        }

        return $c;
    }

    /**
     * @param Builder $c
     * @return Builder
     */
    protected function afterCount(Builder $c): Builder
    {
        $c->with('file:id,updated_at');

        return $c;
    }

    /**
     * @return ResponseInterface;
     */
    public function post(): ResponseInterface
    {
        /** @var Course $course */
        if (!$course_id = (int)$this->getProperty('course_id')) {
            return $this->failure('Вы должны указать id курса');
        } elseif (!$course = Course::query()->find($course_id)) {
            return $this->failure('Не могу загрузить указанный курс');
        } elseif (!$course->wasBought($this->user)) {
            return $this->failure('Вы должны купить этот курс перед отправкой своей работы');
        }

        if ($lesson_id = (int)$this->getProperty('lesson_id')) {
            $section = 0;
        } else {
            $section = (int)$this->getProperty('section');
        }

        $key = [
            'user_id' => $this->user->id,
            'course_id' => $course->id,
            'lesson_id' => $lesson_id ?: null,
            'section' => $section,
        ];

        $coins = false;
        /** @var Homework $homework */
        if (!$homework = Homework::query()->where($key)->first()) {
            $coins = true;
            $homework = new Homework($key);
        }

        if (!$file = $homework->file) {
            $file = new File($key);
        }

        if (!$uploaded = $this->request->getUploadedFiles()) {
            return $this->failure('Не могу загрузить файл');
        }
        $uploaded = array_pop($uploaded);

        $metadata = $this->getProperty('metadata');
        if (!empty($metadata) && $file->uploadFile($uploaded, json_decode($metadata, true))) {
            $homework->file_id = $file->id;
            $homework->save();
            $last_section = $homework->course->lessons()->max('section');

            if ($coins) {
                if (getenv('COINS_HOMEWORK')) {
                    $this->user->makeTransaction(getenv('COINS_HOMEWORK'), 'homework', [
                        'course_id' => $course->id,
                    ]);
                }
                if ($section == $last_section && getenv('COINS_PALETTE')) {
                    $this->user->makeTransaction(getenv('COINS_PALETTE'), 'palette', [
                        'course_id' => $course->id,
                    ]);
                }
            }

            $progress = $this->user->getProgress($course);
            if (!empty($section) && $progress->section == $section) {
                $new_section = $section >= $last_section
                    ? 0
                    : $section + 1;
                $this->user->makeProgress($course, $new_section, 0);
            }
        }
        $progress = $this->user->getProgress($course);

        return $this->success([
            'progress' => [
                'section' => $progress->section,
                'rank' => $progress->rank,
            ],
            'homework' => [
                'id' => $homework->id,
                'section' => $homework->section,
                'lesson_id' => $homework->lesson_id,
                'file' => [
                    'id' => $file->id,
                    'updated_at' => $file->updated_at,
                ],
            ],
        ]);
    }
}
