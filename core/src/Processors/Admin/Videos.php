<?php

namespace App\Processors\Admin;

use App\GetProcessor;
use Illuminate\Database\Eloquent\Builder;

class Videos extends GetProcessor
{

    protected $class = '\App\Model\Video';
    protected $scope = 'videos';


    /**
     * @param Builder $c
     *
     * @return Builder
     */
    protected function beforeCount($c)
    {
        if ($query = trim($this->getProperty('query'))) {
            $c->where(function (Builder $c) use ($query) {
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
    protected function afterCount($c)
    {
        if ($this->getProperty('combo')) {
            $c->select('id', 'title', 'preview', 'duration');
        }

        return $c;
    }
}