<?php

namespace App\Processors\Admin;

use App\Model\Promo;
use Illuminate\Database\Eloquent\Builder;

class Promos extends \App\ObjectProcessor
{

    protected $class = '\App\Model\Promo';
    protected $scope = 'discounts';


    /**
     * @param Builder $c
     *
     * @return Builder
     */
    protected function beforeCount($c)
    {
        if ($query = trim($this->getProperty('query', ''))) {
            $c->where('code', 'LIKE', "%$query%");
            $c->orWhere('title', 'LIKE', "%$query%");
        }

        return $c;
    }

    /**
     * @param Builder $c
     * @return Builder
     */
    protected function afterCount($c)
    {
        $c->withCount('orders');

        return $c;
    }

    /**
     * @param Promo $object
     *
     * @return array
     */
    public function prepareRow($object)
    {
        $array = $object->toArray();
        $array['active'] = $object->check() === true;

        return $array;
    }

    /**
     * @param Promo $record
     *
     * @return bool|\Slim\Http\Response
     */
    public function beforeDelete($record)
    {
        if ($record->used > 0) {
            return 'Нельзя удалять уже использованные промокоды';
        }

        return true;
    }
}