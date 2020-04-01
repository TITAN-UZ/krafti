<?php

namespace App\Processors\Security\Oauth2;

use App\Processors\Security\Oauth2;
use Slim\Http\Response;

class Instagram extends Oauth2
{
    /**
     * @return Response
     */
    public function get()
    {
        $this->setProperty('provider', 'instagram');

        return parent::get();
    }
}