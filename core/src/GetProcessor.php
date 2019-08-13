<?php

namespace App;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Slim\Http\Response;

class GetProcessor extends ObjectProcessor
{
    public function put()
    {
        return $this->failure('Указан несуществующий метод процессора', 404);
    }


    public function patch()
    {
        return $this->failure('Указан несуществующий метод процессора', 404);
    }


    public function delete()
    {
        return $this->failure('Указан несуществующий метод процессора', 404);
    }

}