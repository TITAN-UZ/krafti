<?php

namespace App\Controllers\Admin;

use App\Model\Promo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\JoinClause;
use Psr\Http\Message\ResponseInterface;
use Vesp\Controllers\ModelController;

class Promos extends ModelController
{
    protected $model = Promo::class;
    protected $scope = 'discounts';

    /**
     * @param Builder $c
     *
     * @return Builder
     */
    protected function beforeCount(Builder $c): Builder
    {
        if ($query = trim($this->getProperty('query'))) {
            $c->where('code', 'LIKE', "%$query%");
            $c->orWhere('title', 'LIKE', "%$query%");
        }

        return $c;
    }

    /**
     * @param Builder $c
     * @return Builder
     */
    protected function afterCount(Builder $c): Builder
    {
        $prefix = $this->eloquent->getDatabaseManager()->getTablePrefix();
        $c->leftJoin('orders', static function (JoinClause $c) {
            $c->on('orders.promo_id', '=', 'promos.id');
            $c->where('orders.status', 2); // Paid orders only
        });
        $c->groupBy('promos.id');

        $c->select('promos.id', 'code', 'promos.discount', 'percent', 'used', 'date_start', 'date_end', 'promos.limit');
        $c->selectRaw("SUM({$prefix}orders.cost) as orders_cost");
        $c->selectRaw("COUNT({$prefix}orders.id) as orders_count");

        return $c;
    }

    /**
     * @param Promo|Model $object
     *
     * @return array
     */
    public function prepareRow(Model $object): array
    {
        $array = $object->toArray();
        $array['active'] = $object->check() === true;

        return $array;
    }

    /**
     * @param Promo $record
     *
     * @return bool|ResponseInterface
     */
    public function beforeDelete($record)
    {
        if ($record->used > 0) {
            return 'Нельзя удалять уже использованные промокоды';
        }

        return true;
    }
}
