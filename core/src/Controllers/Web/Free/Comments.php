<?php

namespace App\Controllers\Web\Free;

use App\Model\Comment;
use Illuminate\Database\Eloquent\Builder;
use Vesp\Controllers\ModelGetController;

class Comments extends ModelGetController
{
    protected $model = Comment::class;
    protected $scope = '';

    protected function beforeCount(Builder $c): Builder
    {
        $c->where([
            'lesson_id' => (int)$this->getProperty('lesson_id'),
            'deleted' => false,
        ]);
        $c->whereHas('lesson', static function (Builder $c) {
            $c->where('free', true);
        });

        return $c;
    }

    protected function afterCount(Builder $c): Builder
    {
        $c->select('id', 'parent_id', 'text', 'created_at', 'user_id');
        $c->with('user:id,fullname,photo_id', 'user.photo:id,updated_at');

        return $c;
    }
}
