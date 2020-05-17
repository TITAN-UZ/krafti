<?php

namespace App\Tests\Feature\Controllers\Web;

use App\Controllers\Web\Gallery as Controller;
use App\Model\GalleryItem as Model;
use App\Tests\Feature\Controllers\ModelGetControllerTestTrait;
use App\Tests\TestCase;
use Illuminate\Database\Eloquent\Builder;

class GalleryTest extends TestCase
{
    use ModelGetControllerTestTrait;

    protected $model = Model::class;

    protected function getController()
    {
        return Controller::class;
    }

    protected function getUri()
    {
        return '/api/web/gallery';
    }

    protected function modelWhere(Builder $builder)
    {
        return $builder->where(['active' => true]);
    }

    protected function getDefaultListQuery()
    {
        $record = $this->getModelRecord();
        return [
            'object_id' => $record->object_id,
            'object_name' => $record->object_name
        ];
    }

    public function testFirstModelRecordSuccess()
    {
        $this->markTestSkipped('Модель не имеет ID');
    }

    public function testNotFoundSuccess()
    {
        $this->markTestSkipped('Модель не имеет ID');
    }
}
