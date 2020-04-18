<?php

namespace App\Processors\Admin;

use App\Model\Template;
use App\ObjectProcessor;
use Illuminate\Database\Eloquent\Builder;

class Templates extends ObjectProcessor
{
    protected $class = Template::class;
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