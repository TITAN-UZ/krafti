<?php

namespace App;

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