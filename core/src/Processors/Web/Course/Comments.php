<?php

namespace App\Processors\Web\Course;

use App\Model\Comment;
use App\Model\Course;
use App\Model\User;
use App\ObjectProcessor;
use Illuminate\Database\Eloquent\Builder;
use Slim\Http\Response;

class Comments extends ObjectProcessor
{

    protected $class = Comment::class;
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

        return parent::get();
    }

    /**
     * @return Response
     */
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
            } elseif ($user = User::query()->find(getenv('MANAGER_ID'))) {
                /** @var User $user */
                $user->sendMessage($reply->text, 'reply', $reply->user_id, [
                    'comment_id' => $reply->id,
                    'lesson_id' => $reply->lesson_id,
                    'course_id' => $reply->lesson->course_id,
                ]);
            }
        }

        return $put;
    }

    /**
     * @return Response
     */
    public function patch()
    {
        if ($this->container->user->role_id > 2) {
            return $this->failure('Вы не можете изменять комментарии');
        }

        return parent::patch();
    }


    /**
     * @param Builder $c
     *
     * @return Builder
     */
    protected function beforeGet($c)
    {
        return $this->beforeCount($c);
    }


    /**
     * @param Builder $c
     *
     * @return Builder
     */
    protected function beforeCount($c)
    {
        $c->where('lesson_id', (int)$this->getProperty('lesson_id'));
        if (!$this->container->user->hasScope('comments')) {
            $c->where('deleted', false);
        }

        return $c;
    }

    /**
     * @param Builder $c
     * @return Builder
     */
    protected function afterCount($c)
    {
        $c->select('id', 'parent_id', 'text', 'created_at', 'user_id');
        if ($this->container->user->hasScope('comments')) {
            $c->addSelect('deleted', 'review');
        }
        $c->with('user:id,fullname,photo_id', 'user.photo:id,updated_at');

        return $c;
    }

    /**
     * @return Response
     */
    public function delete()
    {
        return $this->failure('Указан несуществующий метод процессора', 404);
    }
}