<?php

namespace App\Processors\Admin;

use App\Model\Promo;
use Illuminate\Database\Eloquent\Builder;

class Promos extends \App\ObjectProcessor
{

    protected $class = '\App\Model\Promo';
    protected $scope = 'discounts';


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

        /*if ($course_id = $this->getProperty('course_id')) {
            $c->where(['course_id' => $course_id]);
        }
        if ($date = $this->getProperty('date')) {
            $c->whereBetween('created_at', $date);
        }*/

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
        $array['date_start'] = $object->date_start
            ? $object->date_start->toIso8601String()
            : null;
        $array['date_end'] = $object->date_end
            ? $object->date_end->toIso8601String()
            : null;
        $array['created_at'] = $object->created_at->toIso8601String();
        $array['active'] = $object->check() === true;

        return $array;
    }
}