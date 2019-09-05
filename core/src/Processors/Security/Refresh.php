<?php

namespace App\Processors\Security;

use App\Model\User;

class Refresh extends \App\Processor
{
    public function get()
    {
        return $this->failure('Not Implemented', 501);
    }
}