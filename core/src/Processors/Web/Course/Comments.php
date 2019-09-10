<?php

namespace App\Processors\Web\Course;

use App\Model\Comment;
use App\Model\Course;
use App\Model\User;
use Illuminate\Database\Eloquent\Builder;

class Comments extends \App\ObjectProcessor
{

    protected $scope = 'profile';
    protected $class = '\App\Model\Comment';


    /**
     * @return string|bool
     */
    protected function checkScope()
    {
        if ($this->container->request->isOptions() || empty($this->scope)) {
            return true;
        }

        if (!$course_id = $this->getProperty('course_id')) {
            return 'Вы должны указать id курса';
        }
        /** @var Course $course */
        if (!$course = Course::query()->where(['active' => true])->find($course_id)) {
            return 'Не могу загрузить указанный курс';
        } elseif (!$course->wasBought($this->container->user->id)) {
            return 'У вас нет доступа к этому курсу';
        }

        return parent::checkScope();
    }


    /**
     * @param Builder $c
     *
     * @return Builder
     */
    protected function beforeGet($c)
    {
        $c->where(['lesson_id' => (int)$this->getProperty('lesson_id')]);
        if ($this->container->user->role_id > 2) {
            $c->where(['deleted' => false]);
        }

        return $c;
    }


    /**
     * @param Builder $c
     *
     * @return Builder
     */
    protected function beforeCount($c)
    {
        $c->with('user');
        $c->where(['lesson_id' => (int)$this->getProperty('lesson_id')]);
        if ($this->container->user->role_id > 2) {
            $c->where(['deleted' => false]);
        }

        return $c;
    }


    /**
     * @param Comment $object
     *
     * @return array
     */
    public function prepareRow($object)
    {
        $array = [
            'id' => $object->id,
            'parent_id' => $object->parent_id,
            'text' => $object->text,
            'created_at' => $object->created_at->toIso8601String(),
            'user' => [
                'id' => $object->user_id,
                'fullname' => $object->user->fullname,
                'photo' => $object->user->photo
                    ? $object->user->photo->getUrl()
                    : null,
            ],
        ];
        if ($this->container->user->role_id < 3) {
            $array['deleted'] = $object->deleted;
            $array['review'] = $object->review;
        }

        return $array;
    }


    public function put()
    {
        $this->setProperty('user_id', $this->container->user->id);
        $this->setProperty('text', strip_tags($this->getProperty('text')));
        $put = parent::put();

        $id = json_decode($put->getBody()->__toString())->id;
        /** @var Comment $reply */
        if ($reply = Comment::query()->find($id)) {
            if ($parent = $reply->parent) {
                if ($parent->user_id !== $reply->user_id) {
                    $parent->user->sendMessage($reply->text, 'reply', $reply->user_id, [
                        'comment_id' => $reply->id,
                        'lesson_id' => $reply->lesson_id,
                        'course_id' => $reply->lesson->course_id,
                    ]);
                }
            }
        }

        return $put;
    }


    public function patch()
    {
        if ($this->container->user->role_id > 2) {
            return $this->failure('Вы не можете изменять комментарии');
        }

        return parent::patch();
    }


    public function delete()
    {
        return $this->failure('Указан несуществующий метод процессора', 404);
    }
}