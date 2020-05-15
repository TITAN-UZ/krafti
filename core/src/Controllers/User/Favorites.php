<?php

namespace App\Controllers\User;

use App\Model\Course;
use App\Model\User;
use App\Model\UserFavorite;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Vesp\Controllers\Controller;

class Favorites extends Controller
{
    protected $scope = 'profile';

    /** @var User $user */
    protected $user;

    /**
     * @return ResponseInterface
     */
    public function get()
    {
        $favorites = [];
        /** @var UserFavorite $obj */
        foreach ($this->user->favorites()->get() as $obj) {
            $favorites[] = $obj->course_id;
        }
        $courses = Course::query()
            ->whereIn('id', $favorites)
            ->where(['active' => true])
            ->with('cover:id,updated_at');
        $controller = new \App\Controllers\Web\Courses($this->eloquent);

        $rows = [];
        if ($total = $courses->count()) {
            foreach ($courses->get() as $course) {
                $rows[] = $controller->prepareRow($course);
            }
        }

        return $this->success([
            'total' => $total,
            'rows' => $rows,
        ]);
    }

    /**
     * @return ResponseInterface
     */
    public function put()
    {
        if (!$course_id = (int)$this->getProperty('course_id')) {
            return $this->failure('Вы должны указать id курса для добавления в избранное');
        }

        $key = [
            'user_id' => $this->user->id,
            'course_id' => $course_id,
        ];
        try {
            if (!UserFavorite::query()->where($key)->count()) {
                $obj = new UserFavorite($key);
                $obj->save();
            }
        } catch (Exception $e) {
            return $this->failure($e->getMessage());
        }

        return $this->success(['user' => $this->user->getProfile()]);
    }

    /**
     * @return ResponseInterface
     */
    public function delete()
    {
        if (!$course_id = (int)$this->getProperty('course_id')) {
            return $this->failure('Вы должны указать id курса для добавления в избранное');
        }

        $key = [
            'user_id' => $this->user->id,
            'course_id' => $course_id,
        ];
        try {
            /** @var UserFavorite $obj */
            if ($obj = UserFavorite::query()->where($key)->first()) {
                $obj->delete();
            }
        } catch (Exception $e) {
            return $this->failure($e->getMessage());
        }

        return $this->success(['user' => $this->user->getProfile()]);
    }
}
