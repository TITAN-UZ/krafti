<?php

namespace App\Controllers\Admin;

use App\Model\Homework;
use Illuminate\Database\Eloquent\Builder;
use Psr\Http\Message\ResponseInterface;
use Vesp\Controllers\ModelController;

class Homeworks extends ModelController
{
    protected $model = Homework::class;
    protected $scope = 'homeworks';

    /**
     * @return ResponseInterface
     */
    public function put()
    {
        return $this->failure('Домашние работы создают пользователи');
    }

    /**
     * @param Builder $c
     * @return Builder|mixed
     */
    protected function beforeGet($c)
    {
        $c->with('file:id,updated_at');
        $c->with('user:id,fullname');
        $c->with('course:id,title');
        $c->with('lesson:id,title');

        return $c;
    }

    /**
     * @param Builder $c
     *
     * @return Builder
     */
    protected function beforeCount($c)
    {
        if ($query = trim($this->getProperty('query'))) {
            $c->where(static function (Builder $c) use ($query) {
                $c->whereHas('lesson', static function (Builder $c) use ($query) {
                    $c->where('title', 'LIKE', "%$query%");
                });
                $c->orWhereHas('user', static function (Builder $c) use ($query) {
                    $c->where('fullname', 'LIKE', "%$query%");
                    $c->orWhere('email', 'LIKE', "%$query%");
                });
            });
        }
        if ($course_id = $this->getProperty('course_id')) {
            $c->where('course_id', $course_id);
        }
        if ($work_type = $this->getProperty('work_type')) {
            switch ($work_type) {
                case 'work':
                    $c->whereNotNull('lesson_id');
                    break;
                case 'home':
                    $c->whereNull('lesson_id');
                    break;
            }
        }
        if ($date = $this->getProperty('date')) {
            $c->whereBetween('created_at', [$date[0] . ' 00:00:00', $date[1] . ' 23:59:59']);
        }

        return $c;
    }

    /**
     * @param Builder $c
     * @return Builder
     */
    protected function afterCount($c)
    {
        $c->with('user:id,fullname,photo_id', 'user.photo:id,updated_at');
        $c->with('file:id,updated_at');
        $c->with('course:id,title');
        $c->with('lesson:id,title');
        $c->orderBy('created_at', 'desc');

        return $c;
    }

    /**
     * @return ResponseInterface;
     */
    public function delete()
    {
        return $this->failure('Домашние работы нельзя удалять');
    }
}
