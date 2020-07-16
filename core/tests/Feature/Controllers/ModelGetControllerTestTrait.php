<?php

namespace App\Tests\Feature\Controllers;

use Illuminate\Database\Eloquent\Model;
use Psr\Http\Message\ResponseInterface;

trait ModelGetControllerTestTrait
{
    use RequestStatusTrait;

    protected $record;

    abstract protected function getController(): string;

    protected function getModelRecord(): ?Model
    {
        if (!property_exists($this, 'model')) {
            return null;
        }

        if ($this->record === null) {
            /** @var Model $class */
            $model = new $this->model();
            $c = $model->newQuery();

            if (method_exists($this, 'modelWhere')) {
                $c = $this->modelWhere($c);
            }

            $this->record = $c->firstOrFail();
        }

        return $this->record;
    }

    protected function getDefaultListQuery(): array
    {
        return [];
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->app->any($this->getUri(), [$this->getController(), 'process']);
    }

    public function testNotFoundSuccess(): void
    {
        /** @var ResponseInterface $response */
        $response = $this->sendRequest('GET', $this->makeRequestParams(false));

        self::assertEquals(404, $response->getStatusCode(), $response->getBody()->__toString());
    }

    public function testListSuccess(): void
    {
        $this->getSuccess($this->getDefaultListQuery());
    }

    public function testFirstModelRecordSuccess(): void
    {
        $this->getSuccess($this->makeRequestParams());
    }

    protected function makeRequestParams($exists = true): array
    {
        /** @var Model $model */
        $model = new $this->model();
        $key = $model->getKeyName();

        return [
            $key => $exists
                ? $this->getModelRecord()->getKey()
                : PHP_INT_MAX,
        ];
    }
}
