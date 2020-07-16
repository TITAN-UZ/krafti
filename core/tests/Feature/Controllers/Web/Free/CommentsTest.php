<?php

namespace App\Tests\Feature\Controllers\Web\Free;

use App\Controllers\Web\Free\Comments as Controller;
use App\Model\Comment as Model;
use App\Tests\Feature\Controllers\ModelGetControllerTestTrait;
use App\Tests\TestCase;
use Illuminate\Database\Eloquent\Builder;

class CommentsTest extends TestCase
{
    use ModelGetControllerTestTrait;

    protected $model = Model::class;

    protected function getController()
    {
        return Controller::class;
    }

    protected function getUri()
    {
        return '/api/web/free/comments';
    }

    protected function modelWhere(Builder $builder)
    {
        return $builder->whereHas('lesson', static function (Builder $c) {
            $c->where('free', true);
        })->where('deleted', '=', false);
    }

    protected function getDefaultListQuery()
    {
        $record = $this->getModelRecord();

        return [
            'lesson_id' => $record->lesson_id,
        ];
    }
}
