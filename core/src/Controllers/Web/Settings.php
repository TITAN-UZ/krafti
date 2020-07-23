<?php

namespace App\Controllers\Web;

use App\Model\Setting;
use Illuminate\Database\Eloquent\Builder;
use Vesp\Controllers\ModelGetController;

class Settings extends ModelGetController
{
    protected $model = Setting::class;
    protected $primaryKey = 'key';

    protected function beforeGet(Builder $c): Builder
    {
        return $this->beforeCount($c);
    }

    protected function beforeCount(Builder $c): Builder
    {
        $c->select('key', 'type', 'value');

        return $c;
    }
}