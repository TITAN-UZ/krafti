<?php

namespace App\Processors\Admin;

use Illuminate\Database\Eloquent\Builder;

class Videos extends \App\GetProcessor
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
        if ($query = trim($this->getProperty('query', ''))) {
            $c->where(function (Builder $c) use ($query) {
                $c->where('title', 'LIKE', "%$query%");
            });
        }

        return $c;
    }
}