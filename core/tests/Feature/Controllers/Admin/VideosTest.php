<?php

namespace App\Tests\Feature\Controllers\Admin;

use App\Controllers\Admin\Videos as Controller;
use App\Model\Video as Model;
use App\Tests\Feature\Controllers\ModelGetControllerTestTrait;
use App\Tests\TestCase;

class VideosTest extends TestCase
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
        return '/api/admin/videos';
    }
}
