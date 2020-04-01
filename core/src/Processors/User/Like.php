<?php

namespace App\Processors\User;

use App\Model\UserLike;
use App\Processor;
use Exception;
use Slim\Http\Response;

class Like extends Processor
{
    protected $scope = 'profile';


    /**
     * @return Response
     */
    public function get()
    {
        $likes = [];
        /** @var UserLike $obj */
        foreach ($this->container->user->likes()->get() as $obj) {
            $likes[] = $obj->lesson_id;
        }

        return $this->success($likes);
    }


    /**
     * @return Response
     */
    public function post()
    {
        if (!$lesson_id = (int)$this->getProperty('lesson_id')) {
            return $this->failure('Вы должны указать id урока для оценки');
        }
        $action = $this->getProperty('action', 'like');

        $key = [
            'user_id' => $this->container->user->id,
            'lesson_id' => $lesson_id,
        ];

        try {
            /** @var UserLike $obj */
            if (!$obj = UserLike::query()->where($key)->first()) {
                $obj = new UserLike($key);
            }
            $obj->value = $action == 'like'
                ? 1
                : -1;
            $obj->save();

            return $this->success([
                'likes_count' => $obj->lesson->likes_count,
                'dislikes_count' => $obj->lesson->dislikes_count,
                'likes_sum' => $obj->lesson->course->likes_sum,
            ]);
        } catch (Exception $e) {
            return $this->failure($e->getMessage());
        }
    }

}
