<?php

namespace App\Controllers\Security\Oauth2;

use App\Controllers\Security\Oauth2;
use Psr\Http\Message\ResponseInterface;

class Vkontakte extends Oauth2
{
    /**
     * @return ResponseInterface;
     */
    public function get(): ResponseInterface
    {
        $this->setProperty('provider', 'vkontakte');

        return parent::get();
    }
}
