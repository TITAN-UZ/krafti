<?php

namespace App\Service;

use Fenom\Provider;

class Fenom extends \Fenom
{
    public function __construct()
    {
        parent::__construct(new Provider(getenv('BASE_DIR') . 'core/templates/'));

        $this->setCompileDir(getenv('BASE_DIR') . 'tmp/');
        $this->setOptions([
            'disable_native_funcs' => true,
            'disable_cache' => false,
            'force_compile' => false,
            'auto_reload' => true,
            'auto_escape' => true,
            'force_verify' => true,
        ]);
    }
}
