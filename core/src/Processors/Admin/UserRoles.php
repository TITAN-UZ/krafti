<?php

namespace App\Processors\Admin;

use App\ObjectProcessor;
use Illuminate\Database\Eloquent\Builder;

class UserRoles extends ObjectProcessor
{

    protected $class = '\App\Model\UserRole';
    protected $scope = 'user-roles';

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

}