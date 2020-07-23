<?php

namespace App\Controllers\Admin;

use App\Model\Setting;
use Illuminate\Database\Eloquent\Builder;
use Vesp\Controllers\ModelController;

class Settings extends ModelController
{
    protected $model = Setting::class;
    protected $scope = 'settings';
    protected $primaryKey = 'key';

    protected function beforeCount(Builder $c): Builder
    {
        if ($query = trim($this->getProperty('query'))) {
            $c->where('key', 'LIKE', "%{$query}%");
            $c->orWhere('title', 'LIKE', "%{$query}%");
            $c->orWhere('value', 'LIKE', "%{$query}%");
        }

        return $c;
    }
}