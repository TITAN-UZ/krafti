<?php

namespace App\Tests\Feature\Controllers\Web;

use App\Controllers\Web\Reviews as Controller;
use App\Model\Comment as Model;
use App\Tests\Feature\Controllers\ModelGetControllerTestTrait;
use App\Tests\TestCase;
use Illuminate\Database\Eloquent\Builder;

class ReviewsTest extends TestCase
{
    use ModelGetControllerTestTrait;

    protected $model = Model::class;

    protected function getController()
    {
        return Controller::class;
    }

    protected function getUri()
    {
        return '/api/web/reviews';
    }

    protected function modelWhere(Builder $builder)
    {
        return $builder->where(['review' => true, 'deleted' => false]);
    }

    protected function getDefaultListQuery()
    {
        return [
            'limit' => 10
        ];
    }
}
