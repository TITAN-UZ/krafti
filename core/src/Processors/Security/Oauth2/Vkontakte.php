<?php

namespace App\Processors\Security\Oauth2;

use App\Processors\Security\Oauth2;
use Slim\Http\Response;

class Vkontakte extends Oauth2
{
    /**
     * @return Response
     */
    public function get()
    {
        $this->setProperty('provider', 'vkontakte');

        return parent::get();
    }
}