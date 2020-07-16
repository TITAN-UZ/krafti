<?php

namespace App\Controllers\Security\Oauth2;

use App\Controllers\Security\Oauth2;
use Psr\Http\Message\ResponseInterface;

class Instagram extends Oauth2
{
    /**
     * @return ResponseInterface;
     */
    public function get(): ResponseInterface
    {
        $this->setProperty('provider', 'instagram');

        return parent::get();
    }
}
