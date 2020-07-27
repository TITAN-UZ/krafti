<?php

namespace App\Controllers\Admin;

use App\Model\Slider;
use Illuminate\Database\Eloquent\Builder;
use Vesp\Controllers\ModelController;

class Sliders extends ModelController
{
    protected $model = Slider::class;
    protected $scope = 'settings';

    protected function beforeCount(Builder $c): Builder
    {
        if ($query = trim($this->getProperty('query'))) {
            $c->where('title', 'LIKE', "%{$query}%");
            $c->orWhere('description', 'LIKE', "%{$query}%");
        }

        return $c;
    }

    protected function afterCount(Builder $c): Builder
    {
        $c->withCount('items');

        return $c;
    }
}