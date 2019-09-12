<?php

namespace App\Processors\Security\Oauth2;

class Instagram extends \App\Processors\Security\Oauth2
{
    /**
     * @return \Slim\Http\Response
     */
    public function get()
    {
        $this->setProperty('provider', 'instagram');

        return parent::get();
    }
}