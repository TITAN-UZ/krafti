<?php

namespace App\Tests\Feature\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait ModelGetControllerTestTrait
{
    use RequestStatusTrait;

    protected $record;

    /**
     * @return string
     */
    abstract protected function getController();

    protected function getModelRecord()
    {
        if (!property_exists($this, 'model')) {
            return null;
        }

        if ($this->record === null) {
            /** @var Model $class */
            $class = new $this->model;
            /** @var Builder $c */
            $c = $class->newQuery();

            if (method_exists($this, 'modelWhere')) {
               $c = $this->modelWhere($c);
            }

            $this->record = $c->firstOrFail();
        }

        return $this->record;
    }

    protected function getDefaultListQuery()
    {
        return [];
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->app->any($this->getUri(), [$this->getController(), 'process']);
    }

    public function testNotFoundSuccess()
    {
        $response = $this->sendRequest('GET', $this->makeRequestParams(false));

        $this->assertEquals(404, $response->getStatusCode(), 'Ожидается 404 ответ');
    }

    public function testListSuccess()
    {
        $this->getSuccess($this->getDefaultListQuery());
    }

    public function testFirstModelRecordSuccess()
    {
        $this->getSuccess($this->makeRequestParams());
    }

    protected function makeRequestParams($exists = true) : array
    {
        if ($exists) {
            return [
                'id' => $this->getModelRecord()->getKey()
            ];
        }

        return ['id' => PHP_INT_MAX];
    }
}
