<?php

namespace App\Processors\Security\Oauth2;

class Vkontakte extends \App\Processors\Security\Oauth2
{
    /**
     * @return \Slim\Http\Response
     */
    public function get()
    {
        $this->setProperty('provider', 'vkontakte');

        return parent::get();
    }
}