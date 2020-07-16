<?php

namespace App\Controllers\User;

use App\Model\User;
use App\Model\UserLike;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Vesp\Controllers\Controller;

class Like extends Controller
{
    protected $scope = 'profile';

    /** @var User $user */
    protected $user;

    /**
     * @return ResponseInterface;
     */
    public function get(): ResponseInterface
    {
        $likes = [];
        /** @var UserLike $obj */
        foreach ($this->user->likes()->get() as $obj) {
            $likes[] = $obj->lesson_id;
        }

        return $this->success($likes);
    }

    /**
     * @return ResponseInterface;
     */
    public function post(): ResponseInterface
    {
        if (!$lesson_id = (int)$this->getProperty('lesson_id')) {
            return $this->failure('Вы должны указать id урока для оценки');
        }
        $action = $this->getProperty('action', 'like');

        $key = [
            'user_id' => $this->user->id,
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
