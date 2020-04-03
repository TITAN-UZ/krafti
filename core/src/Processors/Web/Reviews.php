<?php

namespace App\Processors\Web;

use App\GetProcessor;
use App\Model\Comment;
use Illuminate\Database\Eloquent\Builder;

class Reviews extends GetProcessor
{

    protected $class = '\App\Model\Comment';


    /**
     * @param Builder $c
     *
     * @return Builder
     */
    public function beforeGet($c)
    {
        $c = $this->beforeCount($c);

        return $this->afterCount($c);
    }


    /**
     * @param Builder $c
     *
     * @return Builder
     */
    public function beforeCount($c)
    {
        $c->groupBy('user_id');
        $c->where(['review' => true, 'deleted' => false]);

        return $c;
    }

    /**
     * @param Builder $c
     * @return Builder
     */
    protected function afterCount($c)
    {
        $c->select('id', 'text', 'user_id');
        $c->with('user:id,fullname,company,photo_id', 'user.photo:id,updated_at');
        if ($this->getProperty('limit') < 20) {
            $c->orderByRaw('RAND()');
        }

        return $c;
    }

}