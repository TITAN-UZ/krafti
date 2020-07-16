<?php

namespace App\Tests\Feature\Controllers\Web;

use App\Controllers\Web\Gallery as Controller;
use App\Model\GalleryItem;
use App\Model\GalleryItem as Model;
use App\Tests\Feature\Controllers\ModelGetControllerTestTrait;
use App\Tests\TestCase;
use Illuminate\Database\Eloquent\Builder;

class GalleryTest extends TestCase
{
    use ModelGetControllerTestTrait;

    protected $model = Model::class;

    protected function getController(): string
    {
        return Controller::class;
    }

    protected function getUri(): string
    {
        return '/api/web/gallery';
    }

    protected function modelWhere(Builder $builder): Builder
    {
        return $builder->where('active', true);
    }

    protected function getDefaultListQuery(): array
    {
        /** @var GalleryItem $record */
        $record = $this->getModelRecord();

        return [
            'object_id' => $record->object_id,
            'object_name' => $record->object_name,
        ];
    }
}
