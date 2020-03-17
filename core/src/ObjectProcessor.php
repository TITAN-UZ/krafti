<?php

namespace App;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Slim\Http\Response;

class ObjectProcessor extends Processor
{
    /** @var Model $class */
    protected $class;
    protected $relations = [];

    protected $primaryKey = 'id';


    /**
     * @return Response
     */
    public function put()
    {
        try {
            $options = [];
            foreach ($this->relations as $v) {
                $options[$v] = $this->getProperty($v);
            }
            /** @var Model $record */
            $record = new $this->class();
            $record->fill($this->getProperties());

            $check = $this->beforeSave($record);
            if ($check !== true) {
                return $this->failure($check);
            }

            $record->save($options);
            $record = $this->afterSave($record);

            return $this->success($this->prepareRow($record));
        } catch (Exception $e) {
            return $this->failure($e->getMessage());
        }
    }


    /**
     * @return Response
     */
    public function patch()
    {
        if (!$id = $this->getProperty($this->primaryKey)) {
            return $this->failure('Вы должны указать id записи');
        }
        if (!$record = $this->class::query()->find($id)) {
            return $this->failure('Не могу найти запись');
        }

        try {
            $options = [];
            foreach ($this->relations as $v) {
                $options[$v] = $this->getProperty($v);
            }
            $record->fill($this->getProperties());

            $check = $this->beforeSave($record);
            if ($check !== true) {
                return $this->failure($check);
            }

            $record->save($options);
            $record = $this->afterSave($record);

            return $this->success($this->prepareRow($record));
        } catch (Exception $e) {
            return $this->failure($e->getMessage());
        }
    }


    /**
     * @param $record
     *
     * @return bool|string
     */
    protected function beforeSave($record)
    {
        return ($record instanceof Model) ? true : 'Не могу сохранить запись';
    }


    /**
     * @param Model $record
     *
     * @return Model
     */
    protected function afterSave(Model $record)
    {
        return $record;
    }


    /**
     * @return Response
     */
    public function get()
    {
        /** @var Model $class */
        $class = new $this->class();
        /** @var Builder $c */
        $c = $class->query();

        if ($id = $this->getProperty($this->primaryKey)) {
            $c = $this->beforeGet($c);
            if ($record = $c->find($id)) {
                $data = $this->prepareRow($record);

                return $this->success($data);
            }

            return $this->failure('Не могу найти запись с id ' . $id);
        }

        $c = $this->beforeCount($c);
        if ($limit = $this->getProperty('limit', 20)) {
            $page = $this->getProperty('page', 1);
            //$total = $c->count();
            $total = $this->container->db->table($this->container->db->raw("({$c->toSql()}) as sub"))
                ->mergeBindings($c->getQuery())
                ->count();
            if ($limit > getenv('QUERY_GET_LIMIT')) {
                $limit = getenv('QUERY_GET_LIMIT');
            }
            $c->forPage($page, $limit);
        }
        $c = $this->afterCount($c);

        $query = $c->getQuery();
        if (empty($query->{$query->unions ? 'unionOrders' : 'orders'}) && $sort = $this->getProperty('sort')) {
            $c->orderBy(/*$class->getTable() . '.' . */$sort, $this->getProperty('dir', 'asc'));
        }

        $rows = [];
        foreach ($c->get() as $object) {
            $rows[] = $this->prepareRow($object);
        }

        return $this->success([
            'total' => isset($total)
                ? $total
                : count($rows),
            'rows' => $rows,
        ]);
    }


    /**
     * Add conditions before get an object by id
     *
     * @param Builder $c
     *
     * @return mixed
     */
    protected function beforeGet($c)
    {
        return $c;
    }


    /**
     * Add joins and search filter
     *
     * @param Builder $c
     *
     * @return Builder
     */
    protected function beforeCount($c)
    {
        return $c;
    }


    /**
     * Add selects to query after total count
     *
     * @param Builder $c
     *
     * @return Builder
     */
    protected function afterCount($c)
    {
        return $c;
    }


    /**
     * @param Model $object
     *
     * @return array
     */
    public function prepareRow($object)
    {
        $data = $object->toArray();
        foreach ($this->relations as $relation) {
            $data[$relation] = [];
            foreach ($object->{$relation} as $item) {
                $data[$relation][] = $item['id'];
            }
        }

        return $data;
    }


    /**
     * @param Model $record
     *
     * @return bool
     */
    protected function beforeDelete($record)
    {
        return true;
    }


    /**
     * @return Response
     */
    public function delete()
    {
        if (!$id = $this->getProperty($this->primaryKey)) {
            return $this->failure('Не указан id записи');
        }

        /** @var Model $record */
        if (!$record = $this->class::query()->find($id)) {
            return $this->failure('Не могу найти запись');
        }

        $check = $this->beforeDelete($record);
        if ($check !== true) {
            return $this->failure($check);
        }

        try {
            $record->delete();

            return $this->success();
        } catch (Exception $e) {
            return $this->failure($e->getMessage());
        }
    }

}