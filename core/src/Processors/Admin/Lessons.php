<?php

namespace App\Processors\Admin;

use App\Model\File;
use App\Model\Lesson;
use Illuminate\Database\Eloquent\Builder;

class Lessons extends \App\ObjectProcessor
{

    protected $class = '\App\Model\Lesson';
    protected $scope = 'lessons';


    public function post()
    {
        if (!$action = $this->getProperty('action')) {
            return $this->failure('Вы доолжны указать action');
        }
        if (!$id = (int)$this->getProperty('id')) {
            return $this->failure('Вы должны указать id объекта');
        }
        /** @var Lesson $object */
        if (!$object = Lesson::query()->find($id)) {
            return $this->failure('Не могу загрузить объект');
        }

        switch ($action) {
            case 'move_up':
                if ($object->rank > 0) {
                    $object->rank -= 1;
                    /** @var Lesson $other */
                    if ($other = $object->course->lessons()->where(['rank' => $object->rank, 'section' => $object->section])->first()) {
                        $other->rank += 1;
                        $other->save();
                    }
                    $object->save();
                }
                break;
            case 'move_down':
                $object->rank += 1;
                /** @var Lesson $other */
                if ($other = $object->course->lessons()->where(['rank' => $object->rank, 'section' => $object->section])->first()) {
                    $other->rank -= 1;
                    $other->save();
                }
                $object->save();
                break;
            default:
                return $this->failure('Указан неверный action');
        }

        $section = $rank = 0;
        $records = $object->course->lessons()
            ->orderBy('section', 'asc')
            ->orderBy('rank', 'asc')
            ->orderBy('created_at', 'asc')
            ->get();
        /** @var Lesson $record */
        foreach ($records as $record) {
            if ($record->section > $section) {
                $rank = 0;
                $section = $record->section;
            }
            $record->rank = $rank;
            $record->save();
            $rank++;
        }

        return $this->success();
    }


    /**
     * @param Lesson $record
     *
     * @return bool|string
     */
    public function beforeSave($record)
    {
        if ($record->section == 0) {
            if (Lesson::query()->where(['section' => 0, 'course_id' => $record->course_id])->where('id', '!=', $record->id)->count()) {
                return 'У вас уже есть бонусное видео в этом курсе. Такое видео может быть только одно.';
            }
        }

        if ($archive = $this->getProperty('file')) {
            if (is_array($archive) && !empty($archive['file'])) {
                if (!$file = $record->file) {
                    $file = new File();
                }

                if ($id = $file->uploadBase64($archive['file'], $archive['metadata'])) {
                    $record->file_id = $id;
                }
            }
        }

        return parent::beforeSave($record);
    }


    /**
     * @param Builder $c
     *
     * @return mixed
     */
    protected function beforeCount($c)
    {
        if ($course_id = (int)$this->getProperty('course_id')) {
            $c->where(['course_id' => $course_id]);
        }
        if ($query = trim($this->getProperty('query'))) {
            $c->where(function (Builder $c) use ($query) {
                $c->where('title', 'LIKE', "%$query%");
                $c->orWhere('description', 'LIKE', "%$query%");
            });
        }
        $section = $this->getProperty('section');
        if ($section !== null) {
            $c->where(['section' => (int)$section]);
        }

        $c->orderBy('section', 'asc');
        $c->orderBy($this->getProperty('sort', 'rank'), $this->getProperty('dir') == 'desc' ? 'desc' : 'asc');

        return $c;
    }


    /**
     * @param Lesson $object
     *
     * @return array
     */
    public function prepareRow($object)
    {
        $array = [
            'id' => $object->id,
            'title' => $object->title,
            'description' => $object->description,
            'rank' => $object->rank,
            'section' => $object->section,
            'products' => $object->products,
            'course_id' => $object->course_id,
            'course' => $object->course
                ? $object->course->title
                : null,
            'video_id' => $object->video_id,
            'video' => $object->video
                ? $object->video->preview
                : null,
            'bonus_id' => $object->bonus_id,
            'bonus' => $object->bonus
                ? $object->bonus->preview
                : null,
            //'file_id' => $object->file_id,
            'file' => $object->file
                ? $object->file->getUrl()
                : null,
            'author_id' => $object->author_id,
            'author' => $object->author
                ? $object->author->fullname
                : null,
            'active' => $object->active,
            'extra' => $object->extra,
        ];

        return $array;
    }
}