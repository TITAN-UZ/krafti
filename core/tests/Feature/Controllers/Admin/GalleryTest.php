<?php

namespace App\Tests\Feature\Controllers\Admin;

use App\Controllers\Admin\Gallery as Controller;
use App\Model\GalleryItem as Model;
use App\Tests\Feature\Controllers\ModelGetControllerTestTrait;
use App\Tests\TestCase;

class GalleryTest extends TestCase
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
        return '/api/admin/courses';
    }
}
