<?php

namespace App\Tests\Feature\Controllers\User;

use App\Controllers\User\Favorites as Controller;
use App\Tests\Feature\Controllers\RequestStatusTrait;
use App\Tests\TestCase;

class FavoritesTest extends TestCase
{
    use RequestStatusTrait;

    protected $user = true;

    protected function getUri()
    {
        return '/api/user/favorites';
    }

    protected function getController()
    {
        return Controller::class;
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->app->any($this->getUri(), [$this->getController(), 'process']);
    }

    public function testPutSuccess()
    {
        $this->putSuccess(['course_id' => 1]);
    }

    public function testGetSuccess()
    {
        $this->getSuccess([]);
    }

    public function testDeleteSuccess()
    {
        $this->requestSuccess('DELETE', ['course_id' => 1]);
    }
}
