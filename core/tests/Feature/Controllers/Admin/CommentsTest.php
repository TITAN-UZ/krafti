<?php

namespace App\Tests\Feature\Controllers\Admin;

use App\Controllers\Admin\Comments as Controller;
use App\Model\Comment as Model;
use App\Tests\Feature\Controllers\ModelGetControllerTestTrait;
use App\Tests\TestCase;

class CommentsTest extends TestCase
{
    use ModelGetControllerTestTrait;

    protected $model = Model::class;
    protected $user = true;

    protected function getController()
    {
        return Controller::class;
    }

    protected function getUri()
    {
        return '/api/admin/comments';
    }

    protected function getDefaultListQuery()
    {
        return [
            'query' => 'понятно',
        ];
    }
}
