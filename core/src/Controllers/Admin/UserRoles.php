<?php

namespace App\Controllers\Admin;

use App\Model\UserRole;
use Illuminate\Database\Eloquent\Builder;
use Vesp\Controllers\ModelController;

class UserRoles extends ModelController
{
    protected $model = UserRole::class;
    protected $scope = 'user-roles';

    /**
     * @param Builder $c
     * @return Builder
     */
    protected function beforeCount(Builder $c): Builder
    {
        if ($query = trim($this->getProperty('query'))) {
            $c->where('title', 'LIKE', "%{$query}%");
        }

        return $c;
    }
}
