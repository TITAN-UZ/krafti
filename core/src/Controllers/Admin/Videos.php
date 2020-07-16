<?php

namespace App\Controllers\Admin;

use App\Model\Video;
use Illuminate\Database\Eloquent\Builder;
use Vesp\Controllers\ModelGetController;

class Videos extends ModelGetController
{
    protected $model = Video::class;
    protected $scope = 'videos';

    /**
     * @param Builder $c
     *
     * @return Builder
     */
    protected function beforeCount(Builder $c): Builder
    {
        if ($query = trim($this->getProperty('query'))) {
            $c->where(static function (Builder $c) use ($query) {
                $c->where('title', 'LIKE', "%$query%");
                $c->orWhere('remote_key', 'LIKE', "$query%");
            });
        }

        return $c;
    }

    /**
     * @param Builder $c
     * @return Builder
     */
    protected function afterCount(Builder $c): Builder
    {
        if ($this->getProperty('combo')) {
            $c->select('id', 'title', 'preview', 'duration');
        }

        return $c;
    }
}
