<?php

namespace App\Tests\Feature\Controllers\Admin;

use App\Controllers\Admin\Homeworks as Controller;
use App\Model\GalleryItem as Model;
use App\Tests\Feature\Controllers\ModelGetControllerTestTrait;
use App\Tests\TestCase;

class HomeworksTest extends TestCase
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
        return '/api/admin/homeworks';
    }

    protected function getDefaultListQuery()
    {
        return [
            'course_id' => 3,
            'work_type' => 'work',
            'date' => '2019-09-11',
            'query' => '@mail'
        ];
    }
}
