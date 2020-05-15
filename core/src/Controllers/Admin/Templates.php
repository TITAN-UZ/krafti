<?php

namespace App\Controllers\Admin;

use App\Model\Template;
use Illuminate\Database\Eloquent\Builder;
use Vesp\Controllers\ModelController;

class Templates extends ModelController
{
    protected $model = Template::class;
    protected $scope = 'courses';

    /**
     * @param Builder $c
     * @return Builder
     */
    protected function beforeCount($c)
    {
        if ($query = trim($this->getProperty('query'))) {
            $c->where('title', 'LIKE', "%{$query}%");
        }

        return $c;
    }

    /**
     * @param Builder $c
     * @return Builder
     */
    protected function afterCount($c)
    {
        $c->withCount('courses');

        return $c;
    }
}