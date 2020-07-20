<?php

namespace App\Controllers\Web;

use App\Model\Comment;
use Illuminate\Database\Eloquent\Builder;
use Vesp\Controllers\ModelGetController;

class Reviews extends ModelGetController
{
    protected $model = Comment::class;

    /**
     * @param Builder $c
     *
     * @return Builder
     */
    public function beforeGet(Builder $c): Builder
    {
        $c = $this->beforeCount($c);

        return $this->afterCount($c);
    }

    /**
     * @param Builder $c
     *
     * @return Builder
     */
    public function beforeCount(Builder $c): Builder
    {
        $c->where(['review' => true, 'deleted' => false]);

        return $c;
    }

    /**
     * @param Builder $c
     * @return Builder
     */
    protected function afterCount(Builder $c): Builder
    {
        $c->select('id', 'text', 'user_id');
        $c->with('user:id,fullname,company,photo_id', 'user.photo:id,updated_at');
        if ($this->getProperty('limit') < 20) {
            $c->orderByRaw('RAND()');
        }

        return $c;
    }
}
