<?php

namespace App\Processors\Security;

use App\Processor;

class Refresh extends Processor
{
    public function get()
    {
        return $this->failure('Not Implemented', 501);
    }
}